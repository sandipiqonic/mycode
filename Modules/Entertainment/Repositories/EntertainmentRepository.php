<?php

namespace Modules\Entertainment\Repositories;

use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Models\EntertainmentStreamContentMapping;
use Modules\Entertainment\Models\EntertainmentGenerMapping;
use Modules\Entertainment\Models\EntertainmentTalentMapping;
use Modules\Entertainment\Models\EntertainmnetDownloadMapping;

use Auth;

class EntertainmentRepository implements EntertainmentRepositoryInterface
{
    public function all()
    {
        return Entertainment::all();
    }

    public function find($id)
    {
        $entertainment = Entertainment::query();

        if (Auth::user()->hasRole('user')) {
            $entertainment->whereNull('deleted_at'); 
        }

        $genre = $entertainment->withTrashed()->findOrFail($id);

        return $genre;
    }

    public function create(array $data)
    {
        return Entertainment::create($data);
    }

    public function update($id, array $data)
    {
        $entertainment = Entertainment::findOrFail($id);
    
        if ($data['movie_access'] == 'free') {
            $data['plan_id'] = null;
        }

        $entertainment->update($data);

        if (isset($data['genres'])) {
            $this->updateGenreMappings($entertainment->id, $data['genres']);
        }

        if (isset($data['actors'])) {
            $this->updateTalentMappings($entertainment->id, $data['actors'], 'actor');
        }

        if (isset($data['directors'])) {
            $this->updateTalentMappings($entertainment->id, $data['directors'], 'director');
        }

        if (isset($data['enable_quality']) && $data['enable_quality'] == 1) {
            $this->updateQualityMappings($entertainment->id, $data);
        }

        return $data;
        return $entertainment;
    }

    public function delete($id)
    {
        $entertainment = Entertainment::findOrFail($id);
        $entertainment->delete();
        return $entertainment;
    }

    public function restore($id)
    {
        $entertainment = Entertainment::withTrashed()->findOrFail($id);
        $entertainment->restore();
        return $entertainment;
    }

    public function forceDelete($id)
    {
        $entertainment = Entertainment::withTrashed()->findOrFail($id);
        $entertainment->forceDelete();
        return $entertainment;
    }

    public function query()
    {
        
        $entertainemnt=Entertainment::query()->with('entertainmentGenerMappings')->withTrashed();

        if(Auth::user()->hasRole('user') ) {
            $entertainemnt->whereNull('deleted_at'); 
        }
    
        return $entertainemnt;
       
    }

    public function list($filters)
    {
    
        $query = Entertainment::with([
            'entertainmentGenerMappings',
            'plan',
            'entertainmentReviews',
            'entertainmentTalentMappings',
            'entertainmentStreamContentMappings',
            'entertainmentDownloadMappings'
        ])->where('status', 1);
        
        // if (!empty($filters['search'])) {
        //     $searchTerm = $filters['search'];
        //     $query->where('name', 'like', "%{$searchTerm}%");
        // }

        // if (!empty($filters['genre_id'])) {
        //     $query->whereHas('entertainmentGenerMappings', function ($q) use ($filters) {
        //         $q->where('genre_id', $filters['genre_id']);
        //     });
        // }

        // if (!empty($filters['actor_id'])) {
        //     $query->whereHas('entertainmentTalentMappings', function ($q) use ($filters) {
        //         $q->where('talent_id', $filters['actor_id']);
        //     });
        // }
             
        return $query;
    }


    public function saveGenreMappings(array $genres, $entertainmentId)
    {
        foreach ($genres as $genre) {
            $genre_data = [
                'entertainment_id' => $entertainmentId,
                'genre_id' => $genre
            ];

            EntertainmentGenerMapping::create($genre_data);
        }
        
    }

    public function saveTalentMappings(array $talents, $entertainmentId)
    {
        foreach ($talents as $talent) {
            $talent_data = [
                'entertainment_id' => $entertainmentId,
                'talent_id' => $talent
            ];

            EntertainmentTalentMapping::create($talent_data);
        } 
    }

    public function saveQualityMappings($entertainmentId, array $videoQuality, array $qualityVideoUrl, array $videoQualityType)
    {
        foreach ($videoQuality as $index => $quality) {
            if ($quality != '' && $qualityVideoUrl[$index] != '' && $videoQualityType[$index] != '') {
                EntertainmentStreamContentMapping::create([
                    'entertainment_id' => $entertainmentId,
                    'url' => $qualityVideoUrl[$index],
                    'type' => $videoQualityType[$index],
                    'quality' => $quality,
                ]);
            }
        }
    }

    protected function updateGenreMappings($entertainmentId, $genres)
    {
        EntertainmentGenerMapping::where('entertainment_id', $entertainmentId)->forceDelete();
        $this->saveGenreMappings($genres, $entertainmentId);
    }

    protected function updateTalentMappings($entertainmentId, $talents, $type)
    {
        EntertainmentTalentMapping::where('entertainment_id', $entertainmentId)
        ->whereHas('talentprofile', function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->forceDelete();

        $this->saveTalentMappings($talents, $entertainmentId);
    }

    protected function updateQualityMappings($entertainmentId, $requestData)
    {
        $Quality_video_url = $requestData['quality_video_url_input'];
        $videoQuality = $requestData['video_quality'];
        $videoQualityType = $requestData['video_quality_type'];

        if (!empty($videoQuality) && !empty($Quality_video_url) && !empty($videoQualityType)) {
            EntertainmentStreamContentMapping::where('entertainment_id', $entertainmentId)->forceDelete();
            foreach ($videoQuality as $index => $videoquality) {
                if ($videoquality != '' && $Quality_video_url[$index] != '' && $videoQualityType[$index]) {
                    $url = $Quality_video_url[$index] ?? null;
                    $type = $videoQualityType[$index] ?? null;
                    $quality = $videoquality;

                    EntertainmentStreamContentMapping::create([
                        'entertainment_id' => $entertainmentId,
                        'url' => $url,
                        'type' => $type,
                        'quality' => $quality
                    ]);
                }
            }
        }
    }

    public function storeDownloads(array $data, $id)
    {
        $entertainment = Entertainment::findOrFail($id);
        $entertainment->update($data);

        EntertainmnetDownloadMapping::where('entertainment_id', $id)->forceDelete();

        if (isset($data['enable_download_quality']) && $data['enable_download_quality'] == 1) {
            $quality_video_download_type = $data['quality_video_download_type'];
            $video_download_quality = $data['video_download_quality'];
            $download_quality_video_url = $data['download_quality_video_url'];

            if (!empty($quality_video_download_type) && !empty($video_download_quality) && !empty($download_quality_video_url)) {
                foreach ($quality_video_download_type as $index => $qualityType) {
                    if ($qualityType != '' && $video_download_quality[$index] != '' && $download_quality_video_url[$index] != '') {
                        EntertainmnetDownloadMapping::create([
                            'entertainment_id' => $entertainment->id,
                            'url' => $download_quality_video_url[$index],
                            'type' => $qualityType,
                            'quality' => $video_download_quality[$index] 
                        ]);
                    }
                }
            }
        }
    }

      
}
