<?php

namespace Modules\Entertainment\Services;

use Modules\Entertainment\Repositories\EntertainmentRepositoryInterface;
use  Modules\Genres\Repositories\GenreRepositoryInterface;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;

class EntertainmentService
{
    protected $entertainmentRepository;
    protected $genresRepository;

    public function __construct( EntertainmentRepositoryInterface $entertainmentRepository, GenreRepositoryInterface $genresRepository)
    {
        $this->entertainmentRepository = $entertainmentRepository;
        $this->genresRepository = $genresRepository;
    }

    public function getAll()
    {
        return $this->entertainmentRepository->all();
    }

    public function getById($id) 
    {
        return $this->entertainmentRepository->find($id);
    }

    public function create(array $data)
    {

        $cacheKey1 = 'movie_list';
        $cacheKey2 = 'tvshow_list';
        
        Cache::forget($cacheKey1);
        Cache::forget($cacheKey2);
        
        $data['poster_url'] = setDefaultImage($data['poster_url']);
        $data['thumbnail_url'] = setDefaultImage($data['thumbnail_url']);

        $data['trailer_url'] = ($data['trailer_url_type'] == 'Local') ? $data['trailer_video'] : $data['trailer_url'];

        $entertainment = $this->entertainmentRepository->create($data);

        if (!empty($data['genres'])) {
            $this->entertainmentRepository->saveGenreMappings($data['genres'], $entertainment->id);
        }

        if (!empty($data['actors'])) {
            $this->entertainmentRepository->saveTalentMappings($data['actors'], $entertainment->id);
        }

        if (!empty($data['directors'])) {
            $this->entertainmentRepository->saveTalentMappings($data['directors'], $entertainment->id);
        }

        if (isset($data['enable_quality']) && $data['enable_quality'] == 1) {
            $this->entertainmentRepository->saveQualityMappings(
                $entertainment->id,
                $data['video_quality'],
                $data['quality_video_url_input'],
                $data['video_quality_type']
            );
        }


        $notification_data = [
            'id' => $entertainment->id,
            'name' => $entertainment->name,
            'posterimage' => $entertainment->poster_url ?? null,
            'type' => $entertainment->type,
            'release_date' => $entertainment->release_date ?? null,
            'description' => $entertainment->description ?? null,
        ];

        $this->SendPushNotification($notification_data);
        
        return $entertainment;
    }


    public function update($id, array $data)
    {
        $entertainment = $this->entertainmentRepository->find($id);

        if($entertainment->type=='movie'){

            $cacheKey = 'move_details_'.$id;
            Cache::forget($cacheKey);

          }else{

            $cacheKey = 'tvshow_details_'.$id;
            Cache::forget($cacheKey);

          } 
        
        $data['trailer_url'] = ($data['trailer_url_type'] == 'Local') ? $data['trailer_video'] : $data['trailer_url'];

        return $this->entertainmentRepository->update($id, $data);
    }

    public function delete($id)
    {
         $entertainment = $this->entertainmentRepository->find($id);

        if($entertainment->type=='movie'){

            $cacheKey = 'move_details_'.$id;
            Cache::forget($cacheKey);

          }else{

            $cacheKey = 'tvshow_details_'.$id;
            Cache::forget($cacheKey);

          } 

        return $this->entertainmentRepository->delete($id);
    }

    public function restore($id)
    {
        $entertainment = $this->entertainmentRepository->find($id);

        if($entertainment->type=='movie'){

            $cacheKey = 'move_details_'.$id;
            Cache::forget($cacheKey);

          }else{

            $cacheKey = 'tvshow_details_'.$id;
            Cache::forget($cacheKey);

          } 

        return $this->entertainmentRepository->restore($id);
    }

    public function forceDelete($id)
    {
        $entertainment = $this->entertainmentRepository->find($id);

        if($entertainment->type=='movie'){

            $cacheKey = 'move_details_'.$id;
            Cache::forget($cacheKey);

          }else{

            $cacheKey = 'tvshow_details_'.$id;
            Cache::forget($cacheKey);

          } 
        
        return $this->entertainmentRepository->forceDelete($id);
    }

    public function getDataTable(Datatables $datatable, $filter, $type)
    {
        $query = $this->getFilteredData($filter ,$type);
        return $datatable->eloquent($query)
        ->editColumn('thumbnail_url', function ($data) {
            return '<img src="' . $data->thumbnail_url . '" alt="avatar" class="avatar avatar-40 rounded-pill">';
        })

          ->addColumn('check', function ($data) {
              return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" onclick="dataTableRowCheck('.$data->id.')">';
          })
          ->addColumn('action', function ($data) {
              return view('entertainment::backend.entertainment.action', compact('data'));
          })
          ->editColumn('status', function ($row) {
            $checked = '';
            if ($row->status) {
                $checked = 'checked="checked"';
            }

            return '
            <div class="form-check form-switch ">
                <input type="checkbox" data-url="'.route('backend.entertainments.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
            </div>
           ';
        })
        ->editColumn('updated_at', fn($data) =>formatUpdatedAt($data->updated_at))
            ->orderColumns(['id'], '-:column $1')
            ->rawColumns(['action', 'status', 'check','thumbnail_url'])
            ->toJson();
    }

    public function getFilteredData($filter, $type)
    {
        $query = $this->entertainmentRepository->query();

        if($type!=null){

            $query = $query->where('type',$type);
        }
  
        if (isset($filter['moive_name'])) {
            $query->where('name', 'like', '%' . $filter['moive_name'] . '%');
        }

        if (isset($filter['plan_id'])) {
            $query->where('plan_id', $filter['plan_id']);
        }

        if (isset($filter['movie_access'])) {
            $query->where('movie_access', $filter['movie_access']);
        }

        if (isset($filter['language'])) {
            $query->where('language', $filter['language']);
        }

        if (isset($filter['gener'])) {
            $query->whereHas('entertainmentGenerMappings', function ($q) use ($filter) {
                $q->where('genre_id', $filter['gener']);
            });
        }

        if (isset($filter['column_status'])) {
            $query->where('status', $filter['column_status']);
        }

        return $query;
    }

    public function storeDownloads(array $data, $id)
    {
        return $this->entertainmentRepository->storeDownloads($data, $id);
    }


   
    public function getEntertainmentList($perPage, $searchTerm = null)
    {
        return $this->entertainmentRepository->list($perPage, $searchTerm);
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

    
}
