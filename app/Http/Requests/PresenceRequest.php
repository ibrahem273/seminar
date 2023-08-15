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
            'user_id' => 'required|array|exists:users,id',
            'user_id_absence' => 'required|array|exists:users,id',
//            'user_ids_array' => 'array'

            //
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'the user is not exist',
            'user_id_absence.required' => 'user_id_absence is required',
        'subject_id.exists' => 'the subject is not exist'

        ];
    }
}
