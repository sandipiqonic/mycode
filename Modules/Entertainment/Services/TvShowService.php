<?php

namespace Modules\Entertainment\Services;

use Modules\CastCrew\Models\CastCrew;
use Modules\Constant\Models\Constant;
use Modules\Entertainment\Repositories\TvshowRepositoryInterface;
use Modules\Genres\Models\Genres;

class TvShowService
{
    protected $tvshowRepository;

    public function __construct(TvshowRepositoryInterface $tvshowRepository)
    {
        $this->tvshowRepository = $tvshowRepository;
    }
       
        public function importTvshow($id)
        {
            $configurationData = $this->getConfiguration();
    
            if (isset($configurationData['success']) && $configurationData['success'] === false) {
                return $configurationData;
            }
    
            $movieDetail = $this->getTvshowDetail($id);

            if (isset($movieDetail['success']) && $movieDetail['success'] === false) {
                return  $movieDetail;
            }
    
            $movieVideoDetail = $this->getTvshowVideoDetail($id);
            if (isset($movieVideoDetail['success']) && $movieVideoDetail['success'] === false) {
                return  $movieVideoDetail;
            }
    
            $castcrewDetail = $this->getCastCrewDetail($id);

            return $this->formatTvshowData($id,$movieDetail, $movieVideoDetail, $castcrewDetail, $configurationData);
        
        }

        private function getConfiguration()
        {
            $configuration = $this->tvshowRepository->getConfiguration();
            $configurationData= json_decode($configuration, true);
    
            while ($configurationData === null) {
                $configuration = $this->tvshowRepository->getConfiguration();
                  $configurationData= json_decode($configuration, true);
            }
            return $configurationData;
        }
    
        private function getTvshowDetail($id)
        {
            $movieDetails = $this->tvshowRepository->getTvshowDetail($id);
            $movieDetail = json_decode($movieDetails, true);
    
            while ($movieDetail === null) {
                $movieDetails = $this->tvshowRepository->getMovieDetails($id);
                $movieDetail = json_decode($movieDetails, true);
            }
            return $movieDetail;
        }
    
        private function getTvshowVideoDetail($id)
        {
            $movieVideo = $this->tvshowRepository->getTvshowVideoDetail($id);
            $movieVideoDetail = json_decode($movieVideo, true);
    
            while ($movieVideoDetail === null) {
                $movieVideo = $this->tvshowRepository->getTvshowVideoDetail($id);
                $movieVideoDetail = json_decode($movieVideo, true);
            }
            return $movieVideoDetail;
        }
    
        private function getCastCrewDetail($id)
        {
            $castcrew = $this->tvshowRepository->getCastCrew($id);
            $castcrewDetail = json_decode($castcrew, true);
    
            while ($castcrewDetail === null) {
                $castcrew = $this->tvshowRepository->getCastCrew($id);
                $castcrewDetail = json_decode($castcrew, true);
            }
            return $castcrewDetail;
        }
    
        private function formatTvshowData($id,$movieDetail, $movieVideoDetail, $castcrewDetail, $configurationData)
        {
            $actors = $this->processCast($castcrewDetail['cast'], $configurationData, 'Acting','actor');
            $directors = $this->processCast($castcrewDetail['crew'], $configurationData, 'Directing','director');
            $language = $this->processLanguage($movieDetail);
            $genres = $this->processGenres($movieDetail['genres']);
            $videoData = $this->processVideoData($movieVideoDetail);
    
            return [
                'id'=>$id,
                'poster_url' => $configurationData['images']['secure_base_url'] . 'original' . $movieDetail['poster_path'],
                'thumbnail_url' => $configurationData['images']['secure_base_url'] . 'original' . $movieDetail['backdrop_path'],
                'trailer_url_type' => $videoData['trailer_url_type'],
                'trailer_url' => $videoData['trailer_url'],
                'name' => $movieDetail['original_title'],
                'description' => $movieDetail['overview'],
                'language' => $language,
                'genres' => $genres,
                'is_restricted' => $movieDetail['adult'],
                'release_date' => $movieDetail['release_date'],
                'actors' => $actors,
                'directors' => $directors,
                'movie_access' => 'free',
                'all_actors' => CastCrew::where('type', 'actor')->get(),
                'all_directors' => CastCrew::where('type', 'director')->get(),
                'all_language' => Constant::where('type', 'movie_language')->get(),
                'all_genres' => Genres::where('status', 1)->get(),
            ];
        }

      
        private function processCast($castData, $configurationData, $department, $type)
        {
            $result = [];
            $count = 0;
            $maxCount = 5;
    
            foreach ($castData as $cast) {
                if ($count >= $maxCount) break;
                if ($cast['known_for_department'] == $department) {
                    $castDetails = $this->getCrewDetail($cast['id']);
                    if (!empty($castDetails)) {
                        $castRecord = CastCrew::updateOrCreate(
                            ['name' => $castDetails['name'], 'dob' => $castDetails['birthday'], 'type'=> $type],
                            [
                                'name' => $castDetails['name'],
                                'type' =>$type,
                                'file_url' => $configurationData['images']['secure_base_url'] . 'original' . $castDetails['profile_path'],
                                'bio' => $castDetails['biography'],
                                'place_of_birth' => $castDetails['place_of_birth'],
                                'dob' => $castDetails['birthday'],
                                'designation' => null,
                            ]
                        );
                        $result[] = $castRecord->id;
                        $count++;
                    }
                }
            }
            return $result;
        }
    
        private function getCrewDetail($id)
        {
            $crewDetails = $this->movieRepository->getCastCrewDetail($id);
            $crewDetail = json_decode($crewDetails, true);
    
            while ($crewDetail === null) {
                $crewDetails = $this->movieRepository->getCastCrewDetail($id);
                $crewDetail = json_decode($crewDetails, true);
            }
            return $crewDetail;
        }
    
        private function processLanguage($movieDetail)
        {
            $language = null;
    
            if (isset($movieDetail['spoken_languages']) && is_array($movieDetail['spoken_languages'])) {
                $spoken_languages = $movieDetail['spoken_languages'];
                if (!empty($spoken_languages)) {
                    $first_language = $spoken_languages[0];
                    $language = $first_language['name'];
    
                    Constant::updateOrCreate(
                        ['name' => $language, 'type' => 'movie_language'],
                        [
                            'name' => $language,
                            'value' => strtolower($language),
                            'type' => 'movie_language',
                            'status' => 1,
                        ]
                    );
                }
            }
            return $language;
        }
    
        private function processGenres($genres)
        {
            $genersIds = [];
            foreach ($genres as $genre) {
                $genreRecord = Genres::updateOrCreate(
                    ['name' => $genre['name']],
                    ['name' => $genre['name'], 'status' => 1]
                );
                $genersIds[] = $genreRecord->id;
            }
            return $genersIds;
        }
    
        private function processVideoData($movieVideoDetail)
        {
            $trailer_url_type = null;
            $trailer_url = null;
            $moive_list = [];

            $video_url_type=null;
            $video_url=null;
    
            if (isset($movieVideoDetail['results']) && is_array($movieVideoDetail['results'])) {
                foreach ($movieVideoDetail['results'] as $video) {
                    if ($video['type'] == 'Trailer') {
                        $trailer_url_type = $video['site'];
                        $trailer_url = 'https://www.youtube.com/watch?v=' . $video['key'];
                    } else {

                        $video_url_type=$video['site'];
                        $video_url='https://www.youtube.com/watch?v='.$video['key'];

                        $moive_list[] = [
                            'video_quality_type' => $video['site'],
                            'video_quality' => $video['size'],
                            'quality_video' => 'https://www.youtube.com/watch?v=' . $video['key'],
                        ];
                    }
                }
            }

            return ['trailer_url_type' => $trailer_url_type, 'trailer_url' => $trailer_url, 'moive_list' => $moive_list ,'video_url_type'=> $video_url_type,'video_url'=>$video_url];
        }
    
        private function formatDuration($minutes)
        {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            return sprintf('%02d:%02d', $hours, $minutes);
        }
    }