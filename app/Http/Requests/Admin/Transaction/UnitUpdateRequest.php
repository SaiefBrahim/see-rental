<?php

namespace App\Http\Requests\Admin\Unit;

use Illuminate\Foundation\Http\FormRequest;

class UnitUpdateRequest extends FormRequest
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
            'unit_type'          => 'required'
        ];
    }
    public function messages()
    {
        return [
            'unit_type.required' => 'Please Enter Unit Name!'

        ];
    }
}
