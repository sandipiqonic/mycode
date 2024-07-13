<?php

namespace Modules\Entertainment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Genres\Transformers\GenresResource;
use Modules\Season\Models\Season;
use Modules\Entertainment\Models\UserReminder;

class ComingSoonResource extends JsonResource
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
        $season = Season::where('entertainment_id', $this->id)->latest()->first();

        $remindData = UserReminder::where('entertainment_id', $this->id)->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'trailer_url_type' => $this->trailer_url_type,
            'type' => $this->type,
            'trailer_url' => $this->trailer_url,
            'language' => $this->language,
            'imdb_rating' => $this->IMDb_rating,
            'content_rating' => $this->content_rating,
            'release_date' => $this->release_date,
            'is_restricted' => $this->is_restricted,
            'season_name' => $season->name ?? null,
            'thumbnail_image' => $this->thumbnail_url,
            'is_remind' => $remindData->is_remind,
            'genres' => GenresResource::collection($genre_data),
        ];
    }
}
