<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresenceRequest extends FormRequest
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
            'subject_id' => 'required|exists:subjects,id',
//            'user_ids' => 'required|exists:users,id',
            'user_ids_array' => 'array'

            //
        ];
    }

    public function messages(): array
    {
        return [
            'user_ids' => 'the user is not exist',
            'user_ids_array' => 'user_id must be array',
            'subject_id' => 'the subject is not exist'

        ];
    }
}
