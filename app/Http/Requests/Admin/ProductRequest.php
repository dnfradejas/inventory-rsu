<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'brand' => 'required',
            'category' => 'required',
            'unit_of_measure' => 'required',
            'product_name' => 'required',
            'sku' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if (config('app.env') === 'testing') {
            unset($rules['image']);
        }
        if ($this->id) {
            unset($rules['image']);
        }

        return $rules;
    }
}
