<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

class CategoryController extends Controller
{
    public function __construct(
        private Category $category
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getIndex(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $categories = $this->category->position()->latest()->paginate($limit, ['*'], 'page', $offset);
        $data =  [
            'total' => $categories->total(),
            'limit' => $limit,
            'offset' => $offset,
            'categories' => $categories->items()
        ];
        return response()->json($data, 200);
    }


    /**
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     */
    public function postStore(Request $request, Category $category): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        //uniqueness check
        $parent_id = $request->parent_id ?? 0;
        $all_category = $this->category->where(['parent_id' => $parent_id])->pluck('name')->toArray();

        if (in_array($request->name, $all_category)) {
            return response()->json(['success' => false, 'message' => 'Category already exists!'], 403);
        }

        //Image Upload
        if (!empty($request->file('image'))) {
            $image_name =  Helpers::upload('category/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        try {
            $category->name = $request->name;
            $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
            $category->position = 0;
            $category->image = $image_name;
            $category->save();
            return response()->json([
                'success' => true,
                'message' => 'Category saved successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Category not saved'
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' =>'required|unique:categories,name,'.$request->id
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $category = $this->category->findOrFail($request->id);
        $category->name = $request->name;
        $category->image = $request->has('image') ? Helpers::update('category/', $category->image, 'png', $request->file('image')) : $category->image;
        $category->update();
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $category = $this->category->findOrFail($request->id);
        if ($category->childes->count() == 0) {
            Helpers::delete('category/' . $category['image']);
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully',], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Remove subcategories first'], 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSearch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $categories = $this->category->active()->position()->where('name', 'LIKE', '%' . $request->name . '%')->paginate($limit, ['*'], 'page', $offset);
        $data =  [
            'limit' => $limit,
            'offset' => $offset,
            'categories' => $categories->items()
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus(Request $request): JsonResponse
    {
        $category = $this->category->find($request->id);
        $category->status = !$category['status'];
        $category->update();
        return response()->json([
            'message' => 'Status updated successfully',
        ], 200);
    }
}
