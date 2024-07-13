<?php

namespace Modules\Video\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
   public function rules()
    {

        return [
            'name' => ['required'],
            'duration'=> ['required'],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Title is required.',
            'duration.required' => 'Duration is required.',

        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
