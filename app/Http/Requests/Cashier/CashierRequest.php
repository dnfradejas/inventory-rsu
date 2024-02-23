<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class CashierRequest extends FormRequest
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
            'id' => 'required',
            'quantity' => 'required|numeric',
            'store' => 'required'
        ];

        if ($this->has('code')) {
            unset(
                $rules['id'],
                $rules['quantity']
            );
        }

        return $rules;
    }
}
