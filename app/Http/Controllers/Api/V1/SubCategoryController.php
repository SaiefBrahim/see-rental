<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Categoy\CategoryUpdateRequest;

class SubCategoryController extends Controller
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
        $category_id = $request['category_id'] ?? 1;

        try {
            $subCategories = $this->category->where(['position' => 1,'parent_id' => $category_id])->latest()->paginate($limit, ['*'], 'page', $offset);
            $data =  [
                'total' => $subCategories->total(),
                'limit' => $limit,
                'offset' => $offset,
                'subCategories' => $subCategories->items()
            ];
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['Result' => 'No Data not found'], 404);
        }
    }

    /**
     * @param Request $request
     * @param Category $subCategory
     * @return JsonResponse
     */
    public function postStore(Request $request, Category $subCategory): JsonResponse
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
            return response()->json(['success' => false, 'message' => 'Sub category already exists!'], 403);
        }

        //Image Upload
        if (!empty($request->file('image'))) {
            $image_name =  Helpers::upload('category/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        try {
            $subCategory->name = $request->name;
            //$subCategory->parent_id = $request->parent;
            $subCategory->parent_id = $request->parent_id;
            $subCategory->position = 1;
            $subCategory->image = $image_name;
            $subCategory->save();
            return response()->json([
                'success' => true,
                'message' => 'Sub Category saved successfully',
            ], 200);
        } catch (\Exception $th) {
            info($th);
            return response()->json([
                'success' => false,
                'message' => 'Category not saved'
            ], 403);
        }
    }

    /**
     * @param CategoryUpdateRequest $request
     * @return JsonResponse
     */
    public function postUpdate(CategoryUpdateRequest $request): JsonResponse
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
     * @return JsonResponse|void
     */
    public function delete(Request $request)
    {
        try {
            $category = $this->category->findOrFail($request->id);
            $image_path  = public_path('storage/category/') . $category->image;
            if (!is_null($category)) {
                $category->delete();
                unlink($image_path);
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Category deleted successfully',
                    ],
                    200
                );
            }
        } catch (\Exception $th) {
            info($th);
            return response()->json([
                'success' => false,
                'message' => 'Category not deleted',
                'err' > $th
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSearch(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $result = $this->category->active()->where('name', 'LIKE', '%' . $request->name . '%')->get();
        if (count($result)) {
            return Response()->json($result, 200);
        } else {
            return response()->json(['message' => 'No Data not found'], 404);
        }
    }
}
