<?php

namespace App\Http\Requests\Admin\Unit;

use Illuminate\Foundation\Http\FormRequest;

class CouponStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" =>  "required",
            "coupon_type" => "required",
            "user_limit"=> "required",
            "coupon_code"=> "required",
            "start_date"=> "required",
            "expire_date"=> "required",
            "min_purchase"=> "required",
            "max_discount"=> "required",
            "discount"=> "required",
            "discount_type"=> "required"
        ];
    }
    public function messages()
    {
        return [
            "title.required" =>  "Please Coupon Name!",
            "coupon_type.required" => "Please Enter Coupon Type!",
            "user_limit.required"=> "Please Enter  Coupon Limite For Same User",
            "coupon_code.required"=> "Please Coupon Coupon Code!",
            "start_date.required"=> "Please Select Coupon Start Date!",
            "expire_date.required"=> "Please Select Coupon Expire Date!",
            "min_purchase.required"=> "Please min purchase!",
            "max_discount.required"=> "Please max discount!",
            "discount.required"=> "Please Coupon discount!",
            "discount_type.required"=> "Please select discount type!"
        ];
    }
}
