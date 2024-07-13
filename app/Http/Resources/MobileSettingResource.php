<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Entertainment\Models\Entertainment;
use Modules\Constant\Models\Constant;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\CastCrew\Models\CastCrew;
use Modules\Genres\Models\Genres;
use Modules\Entertainment\Transformers\MoviesResource;
use Modules\LiveTV\Transformers\LiveTvChannelResource;
use Modules\CastCrew\Transformers\CastCrewListResource;
use Modules\Genres\Transformers\GenresResource;

class MobileSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $data = null;
        if($this->value !== Null){
            switch($this->slug){
                case 'banner':
                    $data = [];
                    break;
                case 'top-10':
                    $topMovieIds = json_decode($this->value);
                    $topMovies = Entertainment::whereIn('id',$topMovieIds)->get();
                    $data = MoviesResource::collection($topMovies);
                    break;
                case 'latest-movies':
                    $latestMovieIds = json_decode($this->value);
                    $latestMovies = Entertainment::whereIn('id',$latestMovieIds)->get();
                    $data = MoviesResource::collection($latestMovies);
                    break;
                case 'enjoy-in-your-native-tongue':
                    $languageIds = json_decode($this->value);
                    $languages = Constant::whereIn('id',$languageIds)->get();
                    $data = $languages;
                    break;
                case 'popular-movies':
                    $popularMovieIds = json_decode($this->value);
                    $popularMovies = Entertainment::whereIn('id',$popularMovieIds)->get();
                    $data = MoviesResource::collection($popularMovies);
                    break;
                case 'top-channels':
                    $channelIds = json_decode($this->value);
                    $channels = LiveTvChannel::whereIn('id',$channelIds)->get();
                    $data = LiveTvChannelResource::collection($channels);
                    break;
                case 'your-favorite-personality':
                    $castIds = json_decode($this->value);
                    $casts = CastCrew::whereIn('id',$castIds)->get();
                    $data = CastCrewListResource::collection($casts);
                    break;
                case '500-free-movies':
                    $movieIds = json_decode($this->value);
                    $movies = Entertainment::whereIn('id',$movieIds)->get();
                    $data = MoviesResource::collection($movies);
                    break;
                case 'genre':
                    $genreIds = json_decode($this->value);
                    $genres = Genres::whereIn('id',$genreIds)->get();
                    $data = GenresResource::collection($genres);
                    break;
                case 'rate-our-app':
                    $data = [];
                    break;
            }
        }


        return [
            'name' => $this->name,
            'section_type' => $this->slug,
            'data' => $data
        ];
    }
}
