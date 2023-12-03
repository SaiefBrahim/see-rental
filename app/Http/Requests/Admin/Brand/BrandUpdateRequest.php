<?php

namespace App\Http\Requests\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;

class BrandUpdateRequest extends FormRequest
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
            'name'          => 'required',
            'image'          => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Please Enter Your Brand Name!',
            'image.required' => 'Please Select Brand Image!'
        ];
    }
}
