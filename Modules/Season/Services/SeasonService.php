<?php

namespace Modules\Season\Services;

use Modules\Season\Repositories\SeasonRepositoryInterface;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SeasonService
{
    protected $seasonRepository;

    public function __construct(SeasonRepositoryInterface $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;
    }

    public function getAll()
    {
        return $this->seasonRepository->all();
    }

    public function getById($id)
    {
        return $this->seasonRepository->find($id);
    }

    public function create(array $data)
    {
        $cacheKey = 'season_list';
        Cache::forget($cacheKey);
        $data['poster_url'] = setDefaultImage($data['poster_url']);
       $season = $this->seasonRepository->create($data);

        $notification_data = [
            'id' => $season->id,
            'name' => $season->name,
            'poster_url' => $season->poster_url ?? null,
            'type' => 'season',
            'description' => $season->description ?? null,
        ];
        $this->sendPushNotification($notification_data);

        return $season;
    }

    public function update($id, array $data)
    {
        $cacheKey = 'season_list';
        Cache::forget($cacheKey);
        return $this->seasonRepository->update($id, $data);
    }

    public function delete($id)
    {
        $cacheKey = 'season_list';
        Cache::forget($cacheKey);
        return $this->seasonRepository->delete($id);
    }

    public function restore($id)
    {
        $cacheKey = 'season_list';
        Cache::forget($cacheKey);
        return $this->seasonRepository->restore($id);
    }

    public function forceDelete($id)
    {
        $cacheKey = 'season_list';
        Cache::forget($cacheKey);
        return $this->seasonRepository->forceDelete($id);
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
        
                  ->addColumn('check', function ($data) {
                      return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" data-type="season" onclick="dataTableRowCheck('.$data->id.',this)">';
                  })
                  ->addColumn('action', function ($data) {
                      return view('season::backend.season.action', compact('data'));
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
            ->orderColumns(['id'], '-:column $1')
            ->rawColumns(['action', 'status', 'check','poster_url','entertainment_id'])
            ->toJson();
    }

    public function getFilteredData($filter)
    {
        $query = $this->seasonRepository->query();
        
         if (isset($filter['moive_name'])) {
              $query->where('name', 'like', '%' . $filter['moive_name'] . '%');
          }


          if (isset($filter['access'])) {
              $query->where('access', $filter['access']);
          }


          if (isset($filter['entertainment_id'])) {
              $query->where('entertainment_id', $filter['entertainment_id']);
          }


          if (isset($filter['plan_id'])) {
              $query->where('plan_id', $filter['plan_id']);
          }

          if (isset($filter['column_status'])) {
              $query->where('status', $filter['column_status']);
          }

        return $query;
    }

    public function getList($perPage, $searchTerm = null)
    {
        return $this->seasonRepository->list($perPage, $searchTerm);
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

    public function getSeasonsList($tv_show_id){

        $curl = curl_init();
 
        $api_key='55e89e24a03a87fa84d7d96abe40d4dd';
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.themoviedb.org/3/tv/'.$tv_show_id.'?api_key='.$api_key,
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

     public function getSeasonsDetails($tvshow_id,$season_id){

        $curl = curl_init();

        $api_key='55e89e24a03a87fa84d7d96abe40d4dd';
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.themoviedb.org/3/tv/'.$tvshow_id.'/season/'.$season_id.'?api_key='.$api_key,
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
        
        return $response ;

     }

     public function getSeasonVideos($tvshow_id,$season_id){

       $curl = curl_init();

       $api_key='55e89e24a03a87fa84d7d96abe40d4dd';
       
       curl_setopt_array($curl, array(

         CURLOPT_URL => 'https://api.themoviedb.org/3/tv/'.$tvshow_id.'/season/'.$season_id.'/videos?api_key='.$api_key,
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

       return $response ;

     }

     


     

    
}
