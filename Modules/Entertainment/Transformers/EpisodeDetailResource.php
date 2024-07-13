<?php

namespace Modules\Entertainment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Subscriptions\Transformers\PlanResource;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Genres\Transformers\GenresResource;
use Modules\Episode\Models\Episode;
use Modules\Entertainment\Transformers\EpisodeResource;
use Modules\Season\Models\Season;
use Modules\Entertainment\Models\EntertainmentGenerMapping;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Transformers\TvshowResource;
use Modules\Entertainment\Transformers\ContinueWatchResource;

class EpisodeDetailResource extends JsonResource
{
    protected $userId;
    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }
    
    public function toArray($request): array
    {

    
        $seasons = Season::where('entertainment_id', $this->entertainment_id)->get();
        $tvShowLinks = [];
        foreach($seasons as $season){
            $episodes = Episode::where('season_id', $season->id)->get();
            $totalEpisodes = $episodes->count();
            $episodes = $episodes->where('id','>',$this->id);

            $tvShowLinks[] = [
                'season_id' => $season->id,
                'name' => $season->name,
                'short_desc' => $season->short_desc,
                'description' => $season->description,
                'poster_image' => $season->poster_url,
                'trailer_url_type' => $season->trailer_url_type,
                'trailer_url ' => $season-> trailer_url ,
                'total_episodes' => $totalEpisodes,
                'episodes' => EpisodeResource::collection(
                                    $episodes->take(5)->map(function ($episode) {
                                        return new EpisodeResource($episode, $this->user_id);
                                    })
                                ),
            ]; 

        }
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'entertainment_id' => $this->entertainment_id,
            'season_id' => $this->season_id,
            'trailer_url_type' => $this->trailer_url_type,
            'trailer_url' => $this->trailer_url,
            'access' => $this->access,
            'plan_id' => $this->plan_id,
            'imdb_rating' => $this->IMDb_rating,
            'content_rating' => $this->content_rating,
            'watched_time' => optional($this->continue_watch)->watched_time ?? null,
            'duration' => $this->duration,
            'release_date' => $this->release_date,
            'is_restricted' => $this->is_restricted,
            'short_desc' => $this->short_desc,
            'description' => $this->description,
            'video_upload_type' => $this->video_upload_type,
            'video_url_input' => $this->video_url_input,
            'enable_quality' => $this->enable_quality,
            'is_download' => $this->is_download ?? false,
            'download_status' => $this->download_status,
            'download_type' => $this->download_type,
            'download_url' => $this->download_url,
            'enable_download_quality' => $this->enable_download_quality,
            'download_quality' => $this->episodeDownloadMappings ?? null,
            'poster_image' => $this->poster_url,
            'language' => optional($this->entertainmentdata)->language,
            'video_links' => $this->EpisodeStreamContentMapping ?? null,
            'plan' => new PlanResource($this->plan),
            'genres' => GenresResource::collection($this->genre_data),
            'tvShowLinks' => $tvShowLinks,
            'more_items' => TvshowResource::collection($this->moreItems),
        ];
    }
}
