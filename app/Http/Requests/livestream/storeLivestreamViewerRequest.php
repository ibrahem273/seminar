<?php

namespace App\Http\Requests\livestream;

use Illuminate\Foundation\Http\FormRequest;

class storeLivestreamViewerRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'livestream_id' => 'required:exists:livestream,id',
            'viewer_id' => 'required:exists:users,id'
        ];
    }

    public function messages()
    {

        return [
            'livestream_id.required' => 'livestream_id is required',
            'livestream_id.exists:livestream,id' => 'livestream_id is not valid',
            'viewer_id.required' => 'viewer_id is required',
            'viewer_id.exists:users,id' => 'viewer_id is not valid',
        ];

    }
}
