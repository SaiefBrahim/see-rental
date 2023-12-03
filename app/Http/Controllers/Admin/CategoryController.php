<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    public function __construct(
        private Category $category
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $categories = $this->category;
        $query_param = [];
        $search = $request['search'];

        if($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories=$categories->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $categories = $categories->where(['position'=>0])->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.index',compact('categories', 'search'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function sub_index(Request $request): Factory|View|Application
    {
        $categories = $this->category;
        $query_param = [];
        $search = $request['search'];

        if($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories=Category::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $categories = $categories->with(['parent'])->where(['position'=>1])->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.category.sub-index',compact('categories', 'search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);

        //uniqueness check
        $parent_id = $request->parent_id ?? 0;
        $all_category = $this->category->where(['parent_id' => $parent_id])->pluck('name')->toArray();

        if (in_array($request->name, $all_category)) {
            Toastr::error(translate(($request->parent_id == null ? 'Category' : 'Sub_category') . ' already exists!'));
            return back();
        }

        if (!empty($request->file('image'))) {
            $image_name =  Helpers::upload('category/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        $category = $this->category;
        $category->name = $request->name;
        $category->image = $image_name;
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->position = $request->position;
        $category->save();

        Toastr::success(translate('Category stored successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        $category->status = $request->status;
        $category->save();
        Toastr::success(translate('Category status updated'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): View|Factory|Application
    {
        $category = $this->category->find($id);
        return view('admin-views.category.edit', compact('category'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit_sub($id): Factory|View|Application
    {
        $category = $this->category->find($id);
        return view('admin-views.category.edit-sub-category', compact('category'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' =>'required|unique:categories,name,'.$request->id
        ], [
            'name.required' => translate('Name is required'),
        ]);
        $category = $this->category->find($id);
        $category->name = $request->name;
        $category->image = $request->has('image') ? Helpers::update('category/', $category->image, 'png', $request->file('image')) : $category->image;
        $category->save();

        Toastr::success(translate('Category updated successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update_sub(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' =>'required|unique:categories,name,'.$request->id
        ], [
            'name.required' => translate('Name is required'),
        ]);
        $category = $this->category->find($id);
        $category->name = $request->name;
        $category->save();

        Toastr::success(translate('Sub Category updated successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);

        if ($category->childes->count()==0){
            Helpers::delete('category/' . $category['image']);
            $category->delete();
            Toastr::success(translate('Category removed'));
        }else{
            Toastr::warning(translate('Remove subcategories first'));
        }
        return back();
    }
}
