<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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

        $rules = [
            'store_name' => [
                'required',
                // Rule::unique('stores')->ignore($this->id),
                'unique_store_address'
            ],
            'address' => 'required|unique_store_address',
            'status' => 'required'
        ];
        return $rules;
    }
}
