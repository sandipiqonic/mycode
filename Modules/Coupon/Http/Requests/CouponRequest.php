<?php

namespace Modules\Coupon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function rules()
    {

        return [
            'coupon_code' =>'required|string|max:6',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric',
            'coupon_usage' => 'numeric',
        ];
    }


    public function messages()
    {
        return [
            'coupon_code.required' => 'Name is required.',
            'type.required' => 'Type is required.',
            'value.required' => 'Value must be numeric.',
            'coupon_usage.numeric' => 'Value must be numeric.'
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
