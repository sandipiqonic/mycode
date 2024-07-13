<?php

namespace Modules\Entertainment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Genres\Transformers\GenresResource;
use Modules\Subscriptions\Transformers\PlanResource;
use Modules\Subscriptions\Models\Plan;

class WatchlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request) 
    {
        $genre_data = [];
        $genres = optional($this->entertainment)->entertainmentGenerMappings;
        foreach($genres as $genre){
            $genre_data[] = $genre->genredata;
        }
        $plans = [];
        $plan = optional($this->entertainment)->plan;
        if($plan){
            $plans = Plan::where('level', '<=', $plan->level)->get();
        }


        return [
            'id' => $this->id,
            'entertainment_id' => $this->entertainment_id,
            'user_id' => $this->user_id,

            'entertainment_id' => optional($this->entertainment)->id,
            'name' => optional($this->entertainment)->name,
            'description' => optional($this->entertainment)->description,
            'trailer_url_type' => optional($this->entertainment)->trailer_url_type,
            'type' => optional($this->entertainment)->type,
            'trailer_url' => optional($this->entertainment)->trailer_url,
            'movie_access' => optional($this->entertainment)->movie_access,
            'plan_id' => optional($this->entertainment)->plan_id,
            'language' => optional($this->entertainment)->language,
            'imdb_rating' => optional($this->entertainment)->IMDb_rating,
            'content_rating' => optional($this->entertainment)->content_rating,
            'duration' => optional($this->entertainment)->duration,
            'release_date' => optional($this->entertainment)->release_date,
            'is_restricted' => optional($this->entertainment)->is_restricted,
            'video_upload_type' => optional($this->entertainment)->video_upload_type,
            'video_url_input' => optional($this->entertainment)->video_url_input,
            'download_status' => optional($this->entertainment)->download_status,
            'enable_quality' => optional($this->entertainment)->enable_quality,
            'download_url' => optional($this->entertainment)->download_url,
            'poster_image' => optional($this->entertainment)->poster_url,
            'thumbnail_image' => optional($this->entertainment)->thumbnail_url,
            'genres' => GenresResource::collection($genre_data),
            'plans' => PlanResource::collection($plans),
            'status' => optional($this->entertainment)->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
