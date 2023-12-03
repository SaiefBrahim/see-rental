<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\CPU\Helpers;
use App\Models\Brand;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;

class BrandController extends Controller
{
    public function __construct(
        private Brand $brand
    ){}

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $brands = $this->brand->latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.brand.index', compact('brands'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required',
            'image'=>'required'
        ], [
            'name.required' => translate('Name is required'),
        ]);

        if (!empty($request->file('image'))) {
            $image_name =  Helpers::upload('brand/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        $brand = $this->brand;
        $brand->name = $request->name;
        $brand->image = $image_name;
        $brand->save();

        Toastr::success(translate('Brand stored successfully'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $brand = $this->brand->find($id);
        return view('admin-views.brand.edit',compact('brand'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => translate('Name is required'),
        ]);

        $brand = $this->brand->find($id);
        $brand->name = $request->name;
        $brand->image = $request->has('image') ? Helpers::update('brand/', $brand->image, 'png', $request->file('image')) : $brand->image;
        $brand->save();

        Toastr::success(translate('Brand updated successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $brand = $this->brand->find($request->id);
        Helpers::delete('brand/' . $brand['image']);
        $brand->delete();

        Toastr::success(translate('Brand deleted successfully'));
        return back();
    }
}
