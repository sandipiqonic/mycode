<?php

namespace Modules\Banner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
   public function rules()
    {

        return [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:movie,tvshow,livetv',
            // 'type_id' => 'required|integer',
            // 'type_name' => 'required|string',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title may not be greater than :max characters.',
            'type.required' => 'Type is required.',
            'type_id.required' => 'Type ID is required.',
            'type_name.required' => 'Type name is required.',
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
