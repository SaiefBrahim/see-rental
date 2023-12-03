<?php

namespace App\Http\Controllers\Admin;

use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use PDF;
use App\CPU\Helpers;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function App\CPU\translate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class ProductController extends Controller
{
    public function __construct(
        private Unit $unit,
        private Brand $brand,
        private Product $product,
        private Category $category,
        private Supplier $supplier
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function list(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        $sort_oqrderQty= $request['sort_oqrderQty'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->product->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('product_code', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->product
                ->when($request->sort_oqrderQty=='quantity_asc', function($q) use ($request){
                    return $q->orderBy('quantity', 'asc');
                })
                ->when($request->sort_oqrderQty=='quantity_desc', function($q) use ($request){
                    return $q->orderBy('quantity', 'desc');
                })
                ->when($request->sort_oqrderQty=='order_asc', function($q) use ($request){
                    return $q->orderBy('order_count', 'asc');
                })
                ->when($request->sort_oqrderQty=='order_desc', function($q) use ($request){
                    return $q->orderBy('order_count', 'desc');
                })
                ->when($request->sort_oqrderQty=='default', function($q) use ($request){
                    return $q->orderBy('id');
                });
        }
        $products = $query->latest()->paginate(Helpers::pagination_limit())->appends(['search'=>$search,'sort_oqrderQty'=>$request->sort_oqrderQty]);
        return view('admin-views.product.list',compact('products','search','sort_oqrderQty'));
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $categories = $this->category->where(['position' => 0])->where('status',1)->get();
        $brands = $this->brand->get();
        $suppliers = $this->supplier->get();
        $units = $this->unit->get();
        return view('admin-views.product.add', compact('categories','brands','suppliers','units'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_categories(Request $request): JsonResponse
    {
        $cat = $this->category->where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="' . 0 . '" disabled selected>---'.translate('Select').'---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->sub_category) {
                $res .= '<option value="' . $row->id . '" selected >' . $row->name . '</option>';
            } else {
                $res .= '<option value="' . $row->id . '">' . $row->name . '</option>';
            }
        }
        return response()->json([
            'options' => $res,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:products',
            'product_code'=> 'required|unique:products',
            'category_id' => 'required',
            'unit_type' => 'required',
            'unit_value' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'name.required' => translate('Product name is required'),
            'category_id.required' => translate('category  is required'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['selling_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['selling_price'] <= $dis) {

            Toastr::warning(translate('Discount can not be more than Selling price'));
            return back();
        }

        $products = $this->product;
        $products->name = $request->name;
        $products->product_code = $request->product_code;

        $category = [];
        if ($request->category_id != null) {
            $category[] = [
                'id' => $request->category_id,
                'position' => 1,
            ];
        }
        if ($request->sub_category_id != null) {
            $category[] = [
                'id' => $request->sub_category_id,
                'position' => 2,
            ];
        }

        $products->category_ids = json_encode($category);

        $products->purchase_price = $request->purchase_price;
        $products->selling_price = $request->selling_price;
        $products->unit_type = $request->unit_type;
        $products->unit_value = $request->unit_value;
        $products->brand = $request->brand_id;
        $products->discount_type = $request->discount_type;
        $products->discount = $request->discount??0;
        $products->tax = $request->tax??0;
        $products->quantity = $request->quantity;
        $products->order_count = 0;
        $products->image = Helpers::upload('product/', 'png', $request->file('image'));
        $products->supplier_id = $request->supplier_id;
        $products->save();
        Toastr::success(translate('Product Added Successfully'));

        return redirect()->route('admin.product.list');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $product = $this->product->find($id);
        $product_category = json_decode($product->category_ids);
        $categories = $this->category->where(['position' => 0])->get();
        $brands = $this->brand->get();
        $suppliers = $this->supplier->get();
        $units = $this->unit->get();
        return view('admin-views.product.edit', compact('product','categories','brands','product_category','suppliers','units'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $product = $this->product->find($id);
        $request->validate([
            'name' => 'required|unique:products,name,'.$product->id,
            'product_code'=> 'required|unique:products,product_code,'.$product->id,
            'category_id' => 'required',
            'unit_type' => 'required',
            'unit_value' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'name.required' => translate('Product name is required'),
            'category_id.required' => translate('category  is required'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['selling_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['selling_price'] <= $dis) {
            Toastr::warning(translate('Discount can not be more than Selling price'));
            return back();
        }

        $product->name = $request->name;
        $product->product_code = $request->product_code;

        $category = [];
        if ($request->category_id != null) {
            $category[] = [
                'id' => $request->category_id,
                'position' => 1,
            ];
        }
        if ($request->sub_category_id != null) {
            $category[] = [
                'id' => $request->sub_category_id,
                'position' => 2,
            ];
        }

        $product->category_ids = json_encode($category);

        $product->purchase_price = $request->purchase_price;
        $product->selling_price = $request->selling_price;
        $product->unit_type = $request->unit_type;
        $product->unit_value = $request->unit_value;
        $product->brand = $request->brand_id;
        $product->discount_type = $request->discount_type;
        $product->discount = $request->discount??0;
        $product->tax = $request->tax??0;
        $product->quantity = $request->quantity;
        $product->image = $request->has('image') ? Helpers::update('product/', $product->image, 'png', $request->file('image')) : $product->image;
        $product->supplier_id = $request->supplier_id;

        $product->save();

        Toastr::success(translate('Product Updated successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $product = $this->product->find($request->id);
        if (Storage::disk('public')->exists('product/' . $product->image)) {
            Storage::disk('public')->delete('product/' .  $product->image);
        }

        $product->delete();
        Toastr::success(translate('Product removed'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function barcode_generate(Request $request, $id): View|Factory|RedirectResponse|Application
    {
        if($request->limit >270)
        {
            Toastr::warning(translate('You can not generate more than 270 barcode'));
            return back();
        }
        $product = $this->product->where('id',$id)->first();
        $limit = $request->limit??4;
        return view('admin-views.product.barcode-generate',compact('product','limit'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function barcode($id): Factory|View|Application
    {
        $product = $this->product->where('id',$id)->first();
        $limit = 28;
        return view('admin-views.product.barcode',compact('product','limit'));
    }

    /**
     * @return Application|Factory|View
     */
    public function bulk_import_index(): Factory|View|Application
    {
        return view('admin-views.product.bulk-import');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulk_import_data(Request $request): RedirectResponse
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error(translate('You have uploaded a wrong format file, please upload the right file'));
            return back();
        }

        $col_key = ['name','product_code','unit_type','unit_value','brand','category_id','sub_category_id','purchase_price','selling_price','discount_type','discount','tax','quantity', 'supplier_id'];
        foreach ($collections as $key => $collection) {
            foreach ($collection as $key => $value) {
                if ($key!="" && !in_array($key, $col_key)) {
                    Toastr::error(translate('Please upload the correct format file.'));
                    return back();
                }
            }
        }

        foreach ($collections as $key => $collection) {
            if ($collection['name'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: name ');
                return back();
            } elseif ($collection['product_code'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: product_code ');
                return back();
            } elseif ($collection['unit_type'] ==="") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: product_code ');
                return back();
            } elseif ($collection['unit_value'] ==="") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: unit value ');
                return back();
            } elseif (!is_numeric($collection['unit_value'])) {
                Toastr::error('Unit Value of row ' . ($key + 2) . ' must be number');
                return back();
            } elseif ($collection['brand'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: brand ');
                return back();
            } elseif ($collection['category_id'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: category_id ');
                return back();
            } elseif ($collection['sub_category_id'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: sub_category_id ');
                return back();
            } elseif ($collection['purchase_price'] ==="") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: purchase price ');
                return back();
            } elseif (!is_numeric($collection['purchase_price'])) {
                Toastr::error('Purchase Price of row ' . ($key + 2) . ' must be number');
                return back();
            } elseif ($collection['selling_price'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: selling_price ');
                return back();
            } elseif (!is_numeric($collection['selling_price'])) {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: number ');
                return back();
            }  elseif ($collection['discount_type'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: discount type');
                return back();
            } elseif ($collection['discount'] ==="") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: discount ');
                return back();
            } elseif (!is_numeric($collection['discount'])) {
                Toastr::error('Discount of row ' . ($key + 2) . ' must be number');
                return back();
            } elseif ($collection['tax'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: tax ');
                return back();
            } elseif (!is_numeric($collection['tax'])) {
                Toastr::error('Tax of row ' . ($key + 2) . ' must be number');
                return back();
            } elseif ($collection['quantity'] === "") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: quantity ');
                return back();
            } elseif (!is_numeric($collection['quantity'])) {
                Toastr::error('Quantity of row ' . ($key + 2) . ' must be number');
                return back();
            } elseif ($collection['supplier_id'] ==="") {
                Toastr::error('Please fill row:' . ($key + 2) . ' field: supplier_id ');
                return back();
            } elseif (!is_numeric($collection['supplier_id'])) {
                Toastr::error('supplier_id of row ' . ($key + 2) . ' must be number');
                return back();
            }

            $product = [
                'discount_type' => $collection['discount_type'],
                'discount' => $collection['discount'],
            ];
            if ($collection['selling_price'] <= Helpers::discount_calculate($product, $collection['selling_price'])) {
                Toastr::error(translate('Discount can not be more or equal to the price in row '). ($key + 2));
                return back();
            }
            $product =  $this->product->where('product_code',$collection['product_code'])->first();
            if($product)
            {
                Toastr::warning(translate('product code row').' : ' . ($key + 2) .' '.translate('already exist'));
                return back();
            }
        }
        $data = [];
        foreach ($collections as $collection) {
          $product =  $this->product->where('product_code',$collection['product_code'])->first();
          if($product)
          {
              Toastr::success(translate('product code already exist'));
              return back();
          }
            $data[] = [
                'name' => $collection['name'],
                'product_code' => $collection['product_code'],
                'image' => json_encode(['def.png']),
                'unit_type' => $collection['unit_type'],
                'unit_value' => $collection['unit_value'],
                'brand' => $collection['brand'],
                'category_ids' => json_encode([['id' => $collection['category_id'], 'position' => 0], ['id' => $collection['sub_category_id'], 'position' => 1]]),
                'purchase_price' => $collection['purchase_price'],
                'selling_price' => $collection['selling_price'],
                'discount_type' => $collection['discount_type'],
                'discount' => $collection['discount'],
                'tax' => $collection['tax'],
                'quantity' => $collection['quantity'],
                'supplier_id' => $collection['supplier_id'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('products')->insert($data);
        Toastr::success(count($data) . ' - '.translate('Products imported successfully'));
        return back();
    }

    /**
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function bulk_export_data(): StreamedResponse|string
    {
        $products = $this->product->all();
        $storage = [];
        foreach($products as $item){
            $category_id = 0;
            $sub_category_id = 0;

            foreach(json_decode($item->category_ids, true) as $category)
            {
                if($category['position']==1)
                {
                    $category_id = $category['id'];
                }
                else if($category['position']==2)
                {
                    $sub_category_id = $category['id'];
                }
            }

            $storage[] = [
                'name' => $item['name'],
                'product_code' => $item['product_code'],
                'unit_type' => $item['unit_type'],
                'unit_value' => $item['unit_value'],
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'brand' => $item['brand'],
                'purchase_price' => $item['purchase_price'],
                'selling_price' => $item['selling_price'],
                'discount_type' => $item['discount_type'],
                'discount' => $item['discount'],
                'tax' => $item['tax'],
                'quantity' => $item['quantity'],
                'supplier_id' => $item['supplier_id'],
            ];
        }
        return (new FastExcel($storage))->download('products.xlsx');
    }

}
