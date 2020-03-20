<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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

        return [
            'title' => $required_or_not.'|min:2|max:200',
            'body' => $required_or_not.'|min:6|max:2000',
            'latitude' => $required_or_not.'|min:-90|max:90|numeric',
            'longitude' => $required_or_not.'|min:-180|max:180|numeric',
            'type_id' => $required_or_not.'|exists:types,id',
            'offer_help' => $required_or_not.'|in:0,1,true,false',
            'image' => 'nullable|image|mimes:jpeg,png',

        ];
    }


}
