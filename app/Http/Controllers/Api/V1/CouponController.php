<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\CPU\Helpers;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function __construct(
        private Order $order,
        private Coupon $coupon
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getIndex(Request $request): JsonResponse
    {
        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;

        $coupons = $this->coupon->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total' => $coupons->total(),
            'limit' => $limit,
            'offset' => $offset,
            'coupons' => $coupons->items(),
        ];
        return response()->json($data, 200);
    }

    /**
     * @param Request $request
     * @param Coupon $coupon
     * @return JsonResponse
     */
    public function poststore(Request $request, Coupon $coupon): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'coupon_type' => 'required',
            'code' => 'required|unique:coupons',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'discount_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        try {
            $coupon->title = $request->title;
            $coupon->code = $request->code;
            $coupon->user_limit = $request->coupon_type != 'default' ? 1 : $request->user_limit;
            $coupon->coupon_type = $request->coupon_type;
            $coupon->start_date = $request->start_date;
            $coupon->expire_date = $request->expire_date;
            $coupon->min_purchase = $request->min_purchase != null ? $request->min_purchase : 0;
            $coupon->max_discount = $request->max_discount != null ? $request->max_discount : $request->discount;
            $coupon->discount = $request->discount_type == 'amount' ? $request->discount : $request['discount'];
            $coupon->discount_type = $request->discount_type;
            $coupon->status = 1;
            $coupon->save();
            return response()->json([
                'success' => true,
                'message' => 'Coupon saved successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not saved',
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
            'id' => 'required',
            'title' => 'required',
            'coupon_type' => 'required',
            'code' => 'required|unique:coupons,code,' . $request->id,
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $coupon = $this->coupon->findOrFail($request->id);

        $coupon->title = $request->title;
        $coupon->code = $request->code;
        $coupon->user_limit = $request->coupon_type != 'default' ? 1 : $request->user_limit;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->min_purchase = $request->min_purchase != null ? $request->min_purchase : 0;
        $coupon->max_discount = $request->max_discount != null ? $request->max_discount : $request->discount;
        $coupon->discount = $request->discount_type == 'amount' ? $request->discount : $request['discount'];
        $coupon->discount_type = $request->discount_type;
        $coupon->status = 1;
        $coupon->save();
        return response()->json([
            'success' => true,
            'message' => 'Coupon updated successfully',
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function delete(Request $request)
    {
        try {
            $coupon = $this->coupon->findOrFail($request->id);
            if (!is_null($coupon)) {
                $coupon->delete();
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Coupon deleted successfully',
                    ],
                    200
                );
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not deleted',
            ], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $coupon = $this->coupon->find($request->id);
        $coupon->status = !$coupon['status'];
        $coupon->save();
        return response()->json(['success' => 'Status updated successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function checkCoupon(Request $request): Response|JsonResponse|Application|ResponseFactory
    {
        $coupon = $this->coupon->where(['code' => $request->code])
            ->where('expire_date', '>=', Carbon::now())
            ->where('start_date', '<=', Carbon::now())
            ->where('status', '!=', 0)
            ->first();
        if (empty($coupon)) {
            return response(['message' => 'Sorry, that coupon is not valid.'], 202);
        }
        $order_coupon_count = $this->order->with('coupon')->where('coupon_code', $coupon->code)->where('user_id', $request->user_id)->count();
        if ($order_coupon_count >= $coupon->user_limit) {
            return response(['message' => 'Opps Coupon availed by you!'], 202);
        } elseif ($request->order_amount < $coupon->min_purchase) {
            return response(['message' => 'Does not satisfy minmun purchase amount!'], 202);
        } else {
            $data =  ['coupon' => $coupon];
            return response()->json($data, 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getSearch(Request $request): JsonResponse
    {

        $limit = $request['limit'] ?? 10;
        $offset = $request['offset'] ?? 1;
        $search = $request->name;
        if (!empty($search)) {
            $result = $this->coupon->where('title', 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%')->latest()->paginate($limit, ['*'], 'page', $offset);
            $data = [
                'total' => $result->total(),
                'limit' => $limit,
                'offset' => $offset,
                'coupons' => $result->items(),
            ];
        } else {
            $data = [
                'total' => 0,
                'limit' => $limit,
                'offset' => $offset,
                'coupons' => [],
            ];
        }
        return response()->json($data, 200);
    }
}
