<?php

namespace Modules\Episode\Services;

use Modules\Episode\Repositories\EpisodeRepositoryInterface;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;

class EpisodeService
{
    protected $episodeRepository;

    public function __construct(EpisodeRepositoryInterface $episodeRepository)
    {
        $this->episodeRepository = $episodeRepository;
    }

    public function getAll()
    {
        return $this->episodeRepository->all();
    }

    public function getById($id)
    {
        return $this->episodeRepository->find($id);
    }

    public function create(array $data)
    {
        $cacheKey = 'episode_list';
        Cache::forget($cacheKey);
        $data['poster_url'] = setDefaultImage($data['poster_url']);
       $episode = $this->episodeRepository->create($data);

       if(isset($data['enable_quality']) && $data['enable_quality'] == 1) {
        $this->episodeRepository->saveQualityMappings(
            $episode->id,
            $data['video_quality'],
            $data['quality_video_url_input'],
            $data['video_quality_type']
        );
    }

        $notification_data = [
            'id' => $episode->id,
            'name' => $episode->name,
            'poster_url' => $episode->poster_url ?? null,
            'type' => 'Episode',
            'description' => $episode->description ?? null,
        ];
        $this->sendPushNotification($notification_data);

        return $episode;
    }

    public function update($id, array $data)
    {
        $cacheKey = 'episode_list';
        Cache::forget($cacheKey);
        return $this->episodeRepository->update($id, $data);
    }

    public function delete($id)
    {
        $cacheKey = 'episode_list';
        Cache::forget($cacheKey);
        return $this->episodeRepository->delete($id);
    }

    public function restore($id)
    {
        $cacheKey = 'episode_list';
        Cache::forget($cacheKey);
        return $this->episodeRepository->restore($id);
    }

    public function forceDelete($id)
    {
        $cacheKey = 'episode_list';
        Cache::forget($cacheKey);
        return $this->episodeRepository->forceDelete($id);
    }

    public function getDataTable(Datatables $datatable, $filter)
    {
        $query = $this->getFilteredData($filter);
        return $datatable->eloquent($query)
        ->editColumn('poster_url', function ($data) {
            return '<img src="' .($data->poster_url) . '" alt="avatar" class="avatar avatar-40 rounded-pill">';
        })

        ->editColumn('entertainment_id', function ($data) {
            return optional($data->entertainmentdata)->name;
        })


        ->editColumn('season_id', function ($data) {
            return optional($data->seasondata)->name;
        })


        ->filterColumn('season_id', function ($data, $keyword) {
            if (!empty($keyword)) {
                $data->whereHas('seasondata', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            }
        })

        ->filterColumn('entertainment_id', function($query, $keyword) {
            $query->where('entertainment_id', $keyword);
        })

        ->filterColumn('entertainment_id', function ($data, $keyword) {
            if (!empty($keyword)) {
                $data->whereHas('entertainmentdata', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            }
        })


        ->filterColumn('season_id', function($query, $keyword) {
            $query->where('season_id', $keyword);
        })

          ->addColumn('check', function ($data) {
              return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" data-type="episode" onclick="dataTableRowCheck('.$data->id.',this)">';
          })
          ->addColumn('action', function ($data) {
              return view('episode::backend.episode.action', compact('data'));
          })
          ->editColumn('status', function ($row) {
            $checked = '';
            if ($row->status) {
                $checked = 'checked="checked"';
            }

            return '
            <div class="form-check form-switch ">
                <input type="checkbox" data-url="'.route('backend.seasons.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
            </div>
           ';
        })
          ->editColumn('updated_at', fn($data) =>formatUpdatedAt($data->updated_at))
            ->orderColumns(['id'], '-:column $1')
            ->rawColumns(['action', 'status', 'check','poster_url','entertainment_id','season_id'])
            ->toJson();
    }

    public function getFilteredData($filter)
    {
        $query = $this->episodeRepository->query();

        if (isset($filter['name'])) {
            $query->where('name', $filter['name']);
        }

        if (isset($filter['column_status'])) {
            $query->where('status', $filter['column_status']);
        }


        if (isset($filter['entertainment_id'])) {
            $query->where('entertainment_id', $filter['entertainment_id']);
        }
        return $query;
    }

    public function getList($perPage, $searchTerm = null)
    {
        return $this->episodeRepository->list($perPage, $searchTerm);
    }


    public function storeDownloads(array $data, $id)
    {
        return $this->episodeRepository->storeDownloads($data, $id);
    }


    public function SendPushNotification($data)
    {
        $heading = [
            "en" => $data['name']
        ];

        $content = [
            "en" => $data['description']
        ];

        return fcm([
            'to' => 'all_user',
            'collapse_key' => 'type_a',
            'notification' => [
                'body' => $content,
                'title' => $heading,
            ],
        ]);
    }

    public function getEpisodeList($tvshow_id,$season_index){

     $curl = curl_init();

     $api_key='55e89e24a03a87fa84d7d96abe40d4dd';
       
     curl_setopt_array($curl, array(
       CURLOPT_URL => 'https://api.themoviedb.org/3/tv/'.$tvshow_id.'/season/'.$season_index.'?api_key='.$api_key,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'GET',
     ));
     
     $response = curl_exec($curl);
     
     curl_close($curl);

     return $response;

    }

    public function getConfiguration(){

        $api_key='55e89e24a03a87fa84d7d96abe40d4dd';

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.themoviedb.org/3/configuration?api_key='.$api_key,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
       $response = curl_exec($curl);

       curl_close($curl);

       return $response;

    }

    public function  getEpisodeDetails($tvshow_id,$season_id, $episode_id){

      $curl = curl_init();

     $api_key='55e89e24a03a87fa84d7d96abe40d4dd';
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.themoviedb.org/3/tv/'.$tvshow_id.'/season/'.$season_id.'/episode/'.$episode_id.'?api_key='.$api_key,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);

      return $response;
      
    }


    public function getEpisodevideo($tvshow_id,$season_id, $episode_id){

       $curl = curl_init();

       $api_key='55e89e24a03a87fa84d7d96abe40d4dd';
       
       curl_setopt_array($curl, array(
         CURLOPT_URL => 'https://api.themoviedb.org/3/tv/'.$tvshow_id.'/season/'.$season_id.'/episode/'.$episode_id.'/videos?api_key='.$api_key,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'GET',
         CURLOPT_HTTPHEADER => array(
           'accept: application/json'
         ),
       ));
       
       $response = curl_exec($curl);
       
       curl_close($curl);

       return $response;
    }

    
}
