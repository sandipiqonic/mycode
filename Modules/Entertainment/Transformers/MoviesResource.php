<?php

namespace Modules\Entertainment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Genres\Transformers\GenresResource;
use Modules\Subscriptions\Transformers\PlanResource;
use Modules\Subscriptions\Models\Plan;

class MoviesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request) 
    {
        $genre_data = [];
        $genres = $this->entertainmentGenerMappings;
        foreach($genres as $genre){
            $genre_data[] = $genre->genredata;
        }
        $plans = [];
        $plan = $this->plan;
        if($plan){
            $plans = Plan::where('level', '<=', $plan->level)->get();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'trailer_url_type' => $this->trailer_url_type,
            'type' => $this->type,
            'trailer_url' => $this->trailer_url,
            'movie_access' => $this->movie_access,
            'plan_id' => $this->plan_id,
            'language' => $this->language,
            'imdb_rating' => $this->IMDb_rating,
            'content_rating' => $this->content_rating,
            'duration' => $this->duration,
            'release_date' => $this->release_date,
            'is_restricted' => $this->is_restricted,
            'video_upload_type' => $this->video_upload_type,
            'video_url_input' => $this->video_url_input,
            'download_status' => $this->download_status,
            'enable_quality' => $this->enable_quality,
            'download_url' => $this->download_url,
            'poster_image' => $this->poster_url,
            'thumbnail_image' => $this->thumbnail_url,
            'is_watch_list' => $this->is_watch_list ?? false,
            'genres' => GenresResource::collection($genre_data),
            'plans' => PlanResource::collection($plans),
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
