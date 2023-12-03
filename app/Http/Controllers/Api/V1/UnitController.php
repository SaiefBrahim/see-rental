<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\Helpers;
use App\Models\Unit;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Unit\UnitStoreRequest;
use App\Http\Requests\Admin\Unit\UnitUpdateRequest;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct(
        private Brand $brand,
        private Unit $unit
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getIndex(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $units = $this->unit->latest()->paginate($limit, ['*'], 'page', $offset);
        $data =  [
            'total' => $units->total(),
            'limit' => $limit,
            'offset' => $offset,
            'units' => $units->items()
        ];
        return response()->json($data, 200);
    }

    /**
     * @param UnitStoreRequest $request
     * @param Unit $unit
     * @return JsonResponse
     */
    public function postStore(UnitStoreRequest $request, Unit $unit): JsonResponse
    {
        try {
            $unit->unit_type = $request->unit_type;
            $unit->save();
            return response()->json([
                'success' => true,
                'message' => 'Unit saved successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unit not saved'
            ], 403);
        }
    }

    public function postUpdate(UnitUpdateRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $brand = $this->unit->findOrFail($request->id);
        $brand->unit_type = $request->unit_type;
        $brand->update();
        return response()->json([
            'success' => true,
            'message' => 'Unit updated successfully',
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function delete(Request $request)
    {
        try {
            $brand = $this->unit->findOrFail($request->id);
            if (!is_null($brand)) {
                $brand->delete();
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Unit deleted successfully',
                    ],
                    200
                );
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unit not deleted'
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function getSearch(Request $request): Response|Application|ResponseFactory
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $units = $this->unit->where('unit_type', 'Like', '%' . $request->name . '%')->latest()->paginate($limit, ['*'], 'page', $offset);
        $data =  [
            'total' => $units->total(),
            'limit' => $limit,
            'offset' => $offset,
            'units' => $units->items()
        ];
        return response($data, 200);
    }
}
