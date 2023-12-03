<?php

namespace App\Http\Requests\Admin\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TransactionSaveRequest extends FormRequest
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
            'unit_type' => 'required'
            //'unit_type' => 'required|unique:units,unit_type',
        ];
    }
    public function messages()
    {
        return [
            'unit_type.required' => 'Please Enter Unit Name!'

        ];
    }
}
