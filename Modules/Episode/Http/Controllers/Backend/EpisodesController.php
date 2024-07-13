<?php

namespace Modules\Episode\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Modules\Episode\Models\Episode;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Episode\Http\Requests\EpisodeRequest;
use App\Trait\ModuleTrait;
use Modules\Constant\Models\Constant;
use Modules\Entertainment\Models\Entertainment;
use Modules\Episode\Models\EpisodeDownloadMapping;
use Modules\Episode\Models\EpisodeStreamContentMapping;
use Modules\Season\Models\Season;
use Modules\Subscriptions\Models\Plan;
use Modules\Episode\Trait\EpisodeTrait;
use Modules\Episode\Services\EpisodeService;

class EpisodesController extends Controller
{
    protected string $exportClass = '\App\Exports\EpisodeExport';
    use EpisodeTrait;
    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    protected $episodeService;

    public function __construct(EpisodeService $episodeService)
    {
        $this->episodeService = $episodeService;

        $this->traitInitializeModuleTrait(
            'episode.title', // module title
            'episodes', // module name
            'fa-solid fa-clipboard-list' // module icon
        );
    }



    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];

        $module_action = 'List';

        $export_import = true;
        $export_columns = [
            [
                'value' => 'name',
                'text' => ' Name',
            ],
            [
                'value' => 'access',
                'text' => ' Episode Access',
            ],

            [
                'value' => 'entertainment_id',
                'text' => 'TV show',
            ],


            [
                'value' => 'season_id',
                'text' => 'Season',
            ],


            [
                'value' => 'IMDb_rating',
                'text' => 'IMDb Rating',
            ],

            [
                'value' => 'content_rating',
                'text' => 'Content Rating',
            ],

            [
                'value' => 'duration',
                'text' => 'Duration',
            ],

            [
                'value' => 'release_date',
                'text' => 'Release Date',
            ],


            [
                'value' => 'is_restricted',
                'text' => 'Restricted',
            ],

            [
                'value' => 'status',
                'text' => 'Status',
            ]

        ];
        $export_url = route('backend.episodes.export');


        $tvshows = Entertainment::where('type','tvshow')->get();

        $seasons=Season::where('status', 1)->get();

        return view('episode::backend.episode.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url','tvshows','seasons'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Episode'; // Adjust as necessary for dynamic use

        return $this->performBulkAction(Episode::class, $ids, $actionType, $moduleName);
    }


    public function index_data(Datatables $datatable, Request $request)
    {
        $filter = $request->filter;
    
        return $this->episodeService->getDataTable($datatable, $filter);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

      public function create()
     {

        $upload_url_type=Constant::where('type','upload_type')->get();

        $plan=Plan::where('status',1)->get();

        $numberOptions = collect(range(1, 10))->mapWithKeys(function ($number) {
            return [$number => $number];
        });

        $video_quality=Constant::where('type','video_quality')->get();
        $tvshows=Entertainment::Where('type','tvshow')->where('status', 1)->get();
        $seasons=Season::where('status', 1)->get();


        $imported_tvshow = Entertainment::where('type', 'tvshow')
        ->where('status', 1)
        ->whereNotNull('tmdb_id')
        ->get();


        $module_title = __('episode.add_title');
        $mediaUrls =  getMediaUrls();

        return view('episode::backend.episode.create', compact('upload_url_type','plan','numberOptions','video_quality','tvshows','seasons','module_title','mediaUrls','imported_tvshow'));
        
    }

    public function store(EpisodeRequest $request)
    {

        $data = $request->except('video_quality_url');

        $episode = $this->episodeService->create($data);

        $message = trans('messages.entertainment_added', ['type'=>'Episode']);

        return redirect()->route('backend.episodes.index')->with('success', $message);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Episode::where('id',$id)->with('EpisodeStreamContentMapping')->first();

        $upload_url_type=Constant::where('type','upload_type')->get();

        $plan=Plan::where('status',1)->get();

        $numberOptions = collect(range(1, 10))->mapWithKeys(function ($number) {
            return [$number => $number];
        });

        $video_quality=Constant::where('type','video_quality')->get();
        $tvshows=Entertainment::Where('type','tvshow')->where('status', 1)->get();
        $seasons=Season::where('status', 1)->get();

        $module_title = __('episode.edit_title');

        $mediaUrls =  getMediaUrls();

       return view('episode::backend.episode.edit', compact('data','upload_url_type','plan','numberOptions','video_quality','tvshows','seasons','module_title','mediaUrls'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */

    public function update(EpisodeRequest $request, $id)
    {

        $requestData = $request->except('video_quality_url');

        if($requestData['access']=='free'){

            $requestData['plan_id']=null;
        }

        $data = $this->episodeService->update($id, $requestData);

        $message = trans('messages.entertainment_edit', ['type' => "Episode"]);

        return redirect()->route('backend.episodes.index')->with('success', $message );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = $this->episodeService->delete($id);
        $message = trans('messages.delete_form', ['form' => 'Episode']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $data = $this->episodeService->restore($id);
        $message = trans('messages.restore_form', ['form' => 'Episode']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $data = $this->episodeService->forceDelete($id);
        $message = trans('messages.delete_form', ['form' => 'Episode']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function update_status(Request $request, Episode $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }

    public function downloadOption(Request $request, $id){

        $data = Episode::where('id', $id)->with('episodeDownloadMappings')->first();

        $module_title = 'Download Episode';

        $upload_url_type=Constant::where('type','upload_type')->get();
        $video_quality=Constant::where('type','video_quality')->get();

        return view('episode::backend.episode.download', compact('data','module_title','upload_url_type','video_quality'));


    }

    public function storeDownloads(Request $request, $id)
    {
        $data = $request->all();
        $this->episodeService->storeDownloads($data, $id);
        $message = trans('messages.set_download_url');

        return redirect()->route('backend.episodes.index')->with('success', $message);
    }

    public function ImportSeasonlist(Request $request){

        $tvshow_id=$request->tmdb_id;

        $seasons=Season::where('status', 1)->where('tmdb_id',$tvshow_id)->get();

        return response()->json($seasons);

    }

    public function ImportEpisodelist(Request $request){

        $tvshow_id=$request->tvshow_id;
        $season_index=$request->season_id;

        $episodejson = $this->episodeService->getEpisodeList($tvshow_id,$season_index);
        $episodelist = json_decode($episodejson, true);

        while($episodelist === null) {

            $episodejson = $this->episodeService->getEpisodeList($tvshow_id,$season_index);
            $episodelist = json_decode($episodejson, true);

    
        }

        if (isset($episodelist['success']) && $episodelist['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $episodelist['status_message']
            ], 400);
        }
 
        $episodeData= [];

        if(isset($episodelist['episodes']) && is_array($episodelist['episodes'])) {

            foreach ($episodelist['episodes'] as $episode) {
                $episodedata = [
                    'name' => $episode['name'],
                    'episode_number'=>$episode['episode_number'],   
                ];

                $episodeData[] = $episodedata;
            }
         }
        return response()->json($episodeData);

    }

    public function ImportEpisode(Request $request){


        $tvshow_id = $request->tvshow_id;
        $season_id = $request->season_id;
        $episode_id = $request->episode_id;

        $episode=Episode::where('tmdb_id', $tvshow_id)->where('tmdb_season',$season_id)->where('episode_number', $episode_id )->first();


        if(!empty($season)){

            $message = __('episode.already_added_episode');

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);

        }

        $episode_details = null;
    
        $configuration =$this->episodeService->getConfiguration();
        $configurationData = json_decode($configuration, true);
    
        while($configurationData === null) {
    
            $configuration =$this->episodeService->getConfiguration();
            $configurationData = json_decode($configuration, true);
        }
    
        if(isset($configurationData['success']) && $configurationData['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $configurationData['status_message']
            ], 400);
        }
    
    
        $episode_details = $this->episodeService->getEpisodeDetails($tvshow_id,$season_id, $episode_id);
        $EpisodeDetail = json_decode($episode_details, true);
    
        while($EpisodeDetail === null) {
            $episode_details = $this->episodeService->getEpisodeDetails($tvshow_id,$season_id, $episode_id);
            $EpisodeDetail = json_decode($episode_details, true);
        }
    
        if (isset($EpisodeDetail['success']) && $EpisodeDetail['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $EpisodeDetail['status_message']
            ], 400);
        }
    
        $episode_video = $this->episodeService->getEpisodevideo($tvshow_id,$season_id, $episode_id);
        $EpisodeVideoDetail = json_decode($episode_video, true);
    
        while($EpisodeVideoDetail === null) {
    
            $episode_video = $this->episodeService->getEpisodevideo($tvshow_id,$season_id, $episode_id);
            $EpisodeVideoDetail = json_decode($episode_video, true);
        }
    
        if (isset($EpisodeVideoDetail['success']) && $EpisodeVideoDetail['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $EpisodeVideoDetail['status_message']
            ], 400);
        }
    
    
        $trailer_url_type=null;
        $trailer_url=null;
        $episode_video_list=[];

        $video_url_type=null;
        $video_url=null;
    
        if(isset($EpisodeVideoDetail['results']) && is_array($EpisodeVideoDetail['results'])) {
    
            foreach ($EpisodeVideoDetail['results'] as $video) {
    
                if($video['type'] == 'Trailer' ||  $video['type'] == 'Clip' ){
    
                    $trailer_url_type= $video['site'];
                    $trailer_url='https://www.youtube.com/watch?v='.$video['key'];
    
                }else{

                      
                     $video_url_type=$video['site'];

                     $video_url='https://www.youtube.com/watch?v='.$video['key'];

                 
                    $episode_video_list[]=[
    
                       'video_quality_type'=>$video['site'],
                       'video_quality'=>$video['size'],
                       'quality_video'=>'https://www.youtube.com/watch?v='.$video['key'],
                    ];
    
                }
    
            }
        }

        $enable_quality=false;

        if(!empty($episode_video_list)){

            $enable_quality=true;

        }

    
        function formatDuration($minutes) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        $tvshows = Entertainment::where('tmdb_id',$tvshow_id)->first();
        $season = Season::where('tmdb_id',$tvshow_id)->where('season_index',$season_id)->first();
    
        $data = [
    
            'poster_url' => $configurationData['images']['secure_base_url'] . 'original' . $EpisodeDetail['still_path'],
            'trailer_url_type'=>$trailer_url_type,
            'trailer_url'=>$trailer_url,
            'name' => $EpisodeDetail['name'],
            'description' => $EpisodeDetail['overview'],
            'duration' => formatDuration($EpisodeDetail['runtime']),
            'is_restricted' => 0,
            'release_date' => $EpisodeDetail['air_date'],
            'movie_access'=>'free',
            'enable_quality'=>$enable_quality,
            'entertainment_id'=>$tvshows->id ?? null,
            'season_id'=>$season->id ?? null,
            'episode_number'=>$episode_id,
            'tmdb_id'=>$tvshow_id,
            'tmdb_season'=>$season_id,
            'video_url_type'=> $video_url_type ?? 'Local',
            'video_url'=> $video_url,
            'episodeStreamContentMappings'=>$episode_video_list,
    
        ];

             return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);

        }

        


}
