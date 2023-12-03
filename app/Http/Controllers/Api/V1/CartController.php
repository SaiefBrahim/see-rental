<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * @param $id
     * @return JsonResponse
     */
    public function addToCart($id): JsonResponse
    {
        $product = DB::table('products')->where('id', $id)->first();
        $order_details = DB::table('order_details')->where('product_id', $id)->first();
        return response()->json([
            'success' => true, 'message' => "You Product", 'product' => $product, 'order_details' => $order_details
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function removeCart(Request $request, $id): JsonResponse
    {
        DB::table('poss')->where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Cart item removed successfully',
        ], 200);
    }
}
