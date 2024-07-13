<?php

namespace Modules\Tax\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
{
    public function rules()
    {

        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric',

        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'Name is required.',
            'type.required' => 'Type is required.',
            'value.required' => 'Value must be numeric.',
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
