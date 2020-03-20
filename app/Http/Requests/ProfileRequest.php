<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $required_or_not = request()->method == 'POST' ? 'required' : 'sometimes';
        $except_unique =  request()->method == 'POST' ?  '' : ','.auth()->id();
        return [
            'name' => $required_or_not.'|min:2|max:200',
            'email' => $required_or_not.'|email|unique:users,email'.$except_unique,
            'password' => $required_or_not.'|max:100|min:6|confirmed',
            'phone' => array($required_or_not, 'regex:/^(010|011|012|015)[0-9]{8}$/' , 'unique:users,phone'.$except_unique),
        ];
    }
}
