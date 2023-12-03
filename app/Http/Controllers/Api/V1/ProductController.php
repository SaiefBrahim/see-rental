<?php

namespace App\Http\Controllers\Api\V1;

use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Excel;
use App\CPU\Helpers;
use App\Models\Product;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Resources\ProductsResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryProductsResource;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function __construct(
        private product $product,
        private BusinessSetting $business_setting
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function bulk_import_data(Request $request): JsonResponse
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            return response()->json(['message' => 'You have uploaded a wrong format file, please upload the right file']);
        }

        foreach ($collections as $key => $collection) {
            if ($collection['name'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: name']);
            } elseif ($collection['product_code'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: product_code']);
            } elseif ($collection['unit_type'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: unit_type']);
            } elseif ($collection['unit_value'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: unit value']);
            } elseif (!is_numeric($collection['unit_value'])) {
                return response()->json(['message' => 'Unit Value of row ' . ($key + 2) . ' must be number']);
            } elseif ($collection['brand'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: brand']);
            } elseif ($collection['category_id'] === "") {

                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: category_id']);
            } elseif ($collection['sub_category_id'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: sub_category_id']);
            } elseif ($collection['purchase_price'] === "") {

                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: purchase price ']);
            } elseif (!is_numeric($collection['purchase_price'])) {
                return response()->json(['message' => 'Purchase Price of row ' . ($key + 2) . ' must be number']);
            } elseif ($collection['selling_price'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: selling_price ']);
            } elseif (!is_numeric($collection['selling_price'])) {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: number ']);
            } elseif ($collection['discount_type'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: discount type']);
            } elseif ($collection['discount'] === "") {

                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: discount ']);
            } elseif (!is_numeric($collection['discount'])) {
                return response()->json(['message' => 'Discount of row ' . ($key + 2) . ' must be number']);
            } elseif ($collection['tax'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: tax ']);
            } elseif (!is_numeric($collection['tax'])) {
                return response()->json(['message' => 'Tax of row ' . ($key + 2) . ' must be number']);
            } elseif ($collection['quantity'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: quantity ']);
            } elseif (!is_numeric($collection['quantity'])) {
                return response()->json(['message' => 'Quantity of row ' . ($key + 2) . ' must be number ']);
            } elseif ($collection['supplier_id'] === "") {
                return response()->json(['message' => 'Please fill row:' . ($key + 2) . ' field: supplier_id ']);
            } elseif (!is_numeric($collection['supplier_id'])) {
                return response()->json(['message' => 'supplier_id of row ' . ($key + 2) . ' must be number']);
            }

            $product = [
                'discount_type' => $collection['discount_type'],
                'discount' => $collection['discount'],
            ];
            if ($collection['selling_price'] <= Helpers::discount_calculate($product, $collection['selling_price'])) {
                return response()->json(['message' => 'Discount can not be more or equal to the price in row' . ($key + 2)]);
            }
            $product =  $this->product->where('product_code', $collection['product_code'])->first();
            if ($product) {
                return response()->json(['message' => 'Product code row ' . ($key + 2) . ' already exist']);
            }
        }
        $data = [];
        foreach ($collections as $collection) {
            $product =  $this->product->where('product_code', $collection['product_code'])->first();
            if ($product) {
                return response()->json(['message' => 'Product code already exist']);
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

            ];
        }
        DB::table('products')->insert($data);
        return response()->json(['code' => 200, 'message' => 'Products imported successfully']);
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
        $products = $this->product->with('supplier', 'category', 'brand')->latest()->get();
        $format = \APP\CPU\ProductLogic::format_export_products($products);
        return (new FastExcel($format))->download('product_list.xlsx');
    }

    /**
     * @return JsonResponse
     */
    public function downloadExcelSample(): JsonResponse
    {
        $path = asset('public/assets/product_bulk_format.xlsx');
        return response()->json(['product_bulk_file' => $path]);
    }

    public function barcode_generate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required',
        ], [
            'id.required' => 'Product ID is required',
            'quantity.required' => 'Barcode quantity is required',
        ]);

        if ($request->limit > 270) {
            return response()->json(['code' => 403, 'message' => 'You can not generate more than 270 barcode']);
        }
        $product = $this->product->where('id', $request->id)->first();
        $quantity = $request->quantity ?? 30;
        $pdf = app()->make(PDF::class);
        $pdf->loadView('admin-views.product.barcode-pdf', compact('product', 'quantity'));

        return $pdf->stream();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function categoryWiseProduct(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $stock_limit = $this->business_setting->where('key', 'stock_limit')->first()->value;

        $category_wise_product = $this->product->with('supplier')->active()
            ->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
                $query->whereJsonContains('category_ids', [['id' => (string) $request['category_id']]]);
            })->latest()->paginate($limit, ['*'], 'page', $offset);
        $category_wise_product = CategoryProductsResource::collection($category_wise_product);
        return response()->json($category_wise_product);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function codeSearch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        $product_by_code = $this->product->where('product_code', 'LIKE', '%' . $request->product_code . '%')->latest()->paginate($limit, ['*'], 'page', $offset);

        $products = ProductsResource::collection($product_by_code);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function productSort(Request $request)
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $sort = $request['sort'] ? $request['sort'] : 'ASC';
        $sort_products = $this->product->orderBy('selling_price', $sort)->latest()->paginate($limit, ['*'], 'page', $offset);
        $products = ProductsResource::collection($sort_products);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function propularProductSort(Request $request): JsonResponse
    {
        $sort = $request['sort'] ? $request['sort'] : 'ASC';
        $products = $this->product->orderBy('order_count', $sort)->get();
        $products = ProductsResource::collection($products);
        return response()->json($products, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function supplierWiseProduct(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $product = $this->product->where('supplier_id', $request->supplier_id)->latest()->paginate($limit, ['*'], 'page', $offset);
        $products = ProductsResource::collection($product);
        $data = [
            'total' => $products->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $products->items(),
        ];
        return response()->json($data, 200);
    }
}
