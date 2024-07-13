<?php

namespace Modules\Video\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Video\Models\Video;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Video\Http\Requests\VideoRequest;
use App\Trait\ModuleTrait;
use Modules\Constant\Models\Constant;
use Modules\Genres\Models\Genres;
use Modules\Subscriptions\Models\Plan;
use Modules\Video\Models\VideoStreamContentMapping;
use App\Services\StreamContentService;
use Modules\Video\Services\VideoService;

class VideosController extends Controller
{
    protected string $exportClass = '\App\Exports\VideoExport';
    protected $videoService;

    protected $streamContentService;
    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
    }

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
        $this->traitInitializeModuleTrait(
            'video.title', // module title
            'videos', // module name
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
                'text' => ' Video Access',
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
        $export_url = route('backend.videos.export');

        return view('video::backend.video.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Video'; // Adjust as necessary for dynamic use

        return $this->performBulkAction(Video::class, $ids, $actionType, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $filter = $request->filter;
        return $this->videoService->getDataTable($datatable, $filter);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

    public function create()
    {

        $constants = Constant::whereIn('type', ['upload_type', 'movie_language', 'video_quality'])->get()->groupBy('type');

        $upload_url_type = $constants->get('upload_type', collect());
        $video_quality = $constants->get('video_quality', collect());
        $plan = Plan::where('status', 1)->get();
        $module_title = __('video.add_title');
        $mediaUrls = getMediaUrls();
        return view('video::backend.video.create', compact('upload_url_type', 'plan', 'video_quality', 'module_title', 'mediaUrls'));


    }

    public function store(VideoRequest $request)
    {
        $data = $request->all();
        $data['poster_url'] = setDefaultImage($data['poster_url']);
        $video = Video::create($data);

        if ($request->hasFile('poster_url')) {
            StoreMediaFile($video, $request->file('poster_url'), 'poster_url');
        }
        // $this->streamContentService->handleQualityVideoUrlInput($request, new VideoStreamContentMapping(), 'video_id');

        if ($request->has('enable_quality') && $request->enable_quality == 1) {

            $Quality_video_url = $request->quality_video_url_input;
            $videoQuality = $request->video_quality;
            $videoQualityType = $request->video_quality_type;


            if (!empty($videoQuality) && !empty($Quality_video_url) && !empty($videoQualityType)) {

                foreach ($videoQuality as $index => $videoquality) {

                    if ($videoquality != '' && $Quality_video_url[$index] != '' && $videoQualityType[$index]) {

                        $url = $Quality_video_url[$index] ?? null;
                        $type = $videoQualityType[$index] ?? null;
                        $quality = $videoquality;

                        VideoStreamContentMapping::create(['episode_id' => $video->id, 'url' => $url, 'type' => $type, 'quality' => $quality]);
                    }
                }
            }
        }

        $message = trans('messages.entertainment_added', ['type' => 'Viedo']);

        return redirect()->route('backend.videos.index')->with('success', $message);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Video::findOrFail($id);

        $module_title = __('video.edit_title');

        $data = Video::where('id', $id)->with('VideoStreamContentMappings')->first();

        $upload_url_type = Constant::where('type', 'UPLOAD_URL_TYPE')->get();

        $plan = Plan::where('status', 1)->get();


        $numberOptions = collect(range(1, 10))->mapWithKeys(function ($number) {
            return [$number => $number];
        });

        $video_quality = Constant::where('type', 'video_quality')->get();

        $mediaUrls = getMediaUrls();

        return view('video::backend.video.edit', compact('data', 'upload_url_type', 'plan', 'numberOptions', 'video_quality', 'module_title', 'mediaUrls'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(VideoRequest $request, $id)
    {
        $requestData = $request->all();

        $data = Video::where('id', $id)->first();

        if ($requestData['access'] == 'free') {

            $requestData['plan_id'] = null;
        }

        $data->update($requestData);

        if ($request->hasFile('poster_url')) {
            StoreMediaFile($data, $request->file('poster_url'), 'poster_url');
        }
        if ($request->has('enable_quality') && $request->enable_quality == 1) {

            $Quality_video_url = $request->quality_video_url_input;
            $videoQuality = $request->video_quality;
            $videoQualityType = $request->video_quality_type;


            if (!empty($videoQuality) && !empty($Quality_video_url) && !empty($videoQualityType)) {
                VideoStreamContentMapping::where('episode_id', $data->id)->forceDelete();
                foreach ($videoQuality as $index => $videoquality) {

                    if ($videoquality != '' && $Quality_video_url[$index] != '' && $videoQualityType[$index]) {

                        $url = $Quality_video_url[$index] ?? null;
                        $type = $videoQualityType[$index] ?? null;
                        $quality = $videoquality;

                        VideoStreamContentMapping::create(['episode_id' => $data->id, 'url' => $url, 'type' => $type, 'quality' => $quality]);
                    }
                }
            }
        }

        $message = trans('messages.entertainment_edit', ['type' => 'Video']);

        return redirect()->route('backend.videos.index')->with('success', $message);
    }

    public function update_status(Request $request, Video $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Video::where('id', $id)->first();
        $data->delete();
        $message = trans('messages.delete_form', ['form' => 'Video']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $data = Video::withTrashed()->where('id', $id)->first();
        $data->restore();
        $message = trans('messages.restore_form', ['form' => 'Video']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $data = Video::withTrashed()->where('id', $id)->first();
        $data->forceDelete();
        $message = trans('messages.delete_form', ['form' => 'Video']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }


    public function downloadOption(Request $request, $id)
    {
        $data = Video::with('videoDownloadMappings')->find($id);

        if (!$data) {
            return redirect()->route('backend.video.index')->with('error', 'Video not found.');
        }

        $module_title = 'Video Download';

        $upload_url_type = Constant::where('type', 'upload_type')->pluck('name', 'value');
        $video_quality = Constant::where('type', 'video_quality')->pluck('name', 'value');

        return view('video::backend.video.download', compact('data', 'module_title', 'upload_url_type', 'video_quality'));
    }

    public function storeDownloads(Request $request, $id)
    {
        $data = $request->all();
        $this->videoService->storeDownloads($data, $id);
        $message = trans('messages.set_download_url');

        return redirect()->route('backend.videos.index')->with('success', $message);
    }


}




