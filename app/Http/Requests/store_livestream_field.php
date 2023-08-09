<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class store_livestream_field extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id'=>'required|exists:users,id',
            'livestream_id' => 'required'
        ];
    }
public function messages()
{
    return [
        'user_id.required'=>'user_id required',
        'user_id.exists'=>'user_id not exist',

        'livestream_id.required' => 'livestream required',
        'livestream_id.integer' => 'livestream_id must be integer'
    ];
}
}
