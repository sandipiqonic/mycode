<?php

namespace Modules\Subscriptions\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
            $rules = [
                'name' => ['required'],
                'duration' => ['required'],
                'duration_value' => ['required'],
                'price' => ['required'],

            ];

            if ($this->isMethod('put')) {
                $rules['level'] = ['required'];
            }

            return $rules;

    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'level.required' =>'Level is required',
            'duration.required' =>'Duration is required',
            'duration_value.required' =>'Duration value is required',
            'price.required' =>'Price value is required',
           
        ];
    }
}
