<?php

namespace App\Http\Requests;

use App\Rules\year_rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
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
            'year'=> Rule::in([1,2,3,4,5]),
            'duration' =>'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'name'=>'required |string',
            'specialty'=>'required|string',
            'passed'=>'required|boolean',

        ];
    }
}
