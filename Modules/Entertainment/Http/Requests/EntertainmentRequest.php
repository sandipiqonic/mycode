<?php

namespace Modules\Entertainment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EntertainmentRequest extends FormRequest
{
   public function rules()
    {

        return [
            'name' => ['required'],
            'language'=> ['required'],
            'genres'=> ['required'],
            'actors'=> ['required'],
            'directors'=> ['required'],
            'video_upload_type'=>['required'],
            'video_url_input'=>['required'],
       
        ];

        if ($this->input('type') == 'movie') {
            $rules['duration'] = 'required';
        }

    }

   


    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'language.required' => 'Language is required.',
            'genres.required' => 'Genres is required.',
            'actors.required' => 'Actors is required.',
            'directors.required' => 'Directors is required.',
            'duration.required' => 'Duration is required.',
            'video_upload_type.required'=>'Video Type is required.',
            'video_url_input.required'=>'Video is required.'
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
