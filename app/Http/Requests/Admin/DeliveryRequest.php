<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
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
            'delivery_date' => 'required|date',
            'supplier' => 'required',
            'product' => 'required',
            'quantity' => 'required|numeric',
            // 'barcode' => [                
            //     Rule::unique('delivery_details')->ignore($this->id)
            // ],
            'barcode' => 'unique_barcode',
            'production_date' => 'required|date',
            'expiration_date' => 'required|date|not_yesterday',
        ];
    }
}
