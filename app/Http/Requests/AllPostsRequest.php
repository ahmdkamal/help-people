<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AllPostsRequest extends FormRequest
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
            'latitude' => 'required|min:-90|max:90|numeric',
            'longitude' => 'required|min:-180|max:180|numeric',
            'type_id' => 'nullable|exists:types,id'
        ];
    }
}
