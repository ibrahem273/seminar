<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class find_user_request extends FormRequest
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
            'id'=>'required|exists:users,id'
        ];
    }
    public function messages()
    {

     return[
         'id.required'=>"the id is required",
        'id.exists'=>'the id not exist'
     ];
    }
}
