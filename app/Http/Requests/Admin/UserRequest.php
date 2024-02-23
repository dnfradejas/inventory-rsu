<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'role' => 'required',
            'fullname' => 'required',
            'username' => [
                'required',
                Rule::unique('admin_users')->ignore($this->id)
            ],
            'password' => 'required|min:6'
        ];

        if ($this->id) {
            $rules['password'] = 'nullable|min:6';
        }
        
        return $rules;
    }
}
