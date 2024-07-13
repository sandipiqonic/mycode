<?php

namespace Modules\Episode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EpisodeRequest extends FormRequest
{
   public function rules()
    {
        return [
            'name' => ['required'],
            'entertainment_id'=> ['required'],
            'season_id'=> ['required'],
            'duration'=> ['required'],
            'release_date'=> ['required'],
        
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'entertainment_id.required' => 'TV Show is required.',
            'season_id.required' => 'Season is required.',
            'duration.required' => 'Duration is required.',
            'release_date.required' => 'Release Date is required.',
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
