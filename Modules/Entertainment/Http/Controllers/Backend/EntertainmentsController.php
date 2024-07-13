<?php

namespace Modules\Entertainment\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Entertainment\Models\Entertainment;
use Illuminate\Http\Request;
use Modules\Entertainment\Http\Requests\EntertainmentRequest;
use App\Trait\ModuleTrait;
use Modules\Constant\Models\Constant;
use Modules\Subscriptions\Models\Plan;
use Modules\Genres\Models\Genres;
use Modules\CastCrew\Models\CastCrew;
use Modules\Entertainment\Services\EntertainmentService;

class EntertainmentsController extends Controller
{
    protected string $exportClass = '\App\Exports\EntertainmentExport';


    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

        protected $entertainmentService;

        public function __construct(EntertainmentService $entertainmentService)
        {
            $this->entertainmentService = $entertainmentService;

            $this->traitInitializeModuleTrait(
                'castcrew.castcrew_title',
                'castcrew',
                'fa-solid fa-clipboard-list'
            );
        }


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
            ]
        ];
        $export_url = route('backend.entertainments.export');

        return view('entertainment::backend.entertainment.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Entertainment'; // Adjust as necessary for dynamic use

        return $this->performBulkAction(Entertainment::class, $ids, $actionType, $moduleName);
    }

    public function store(EntertainmentRequest $request)
     {
        $data = $request->all();

        $entertainment = $this->entertainmentService->create($data);
        $type = $entertainment->type;
        $message = trans('messages.entertainment_added', ['type' => $type]);

        if($type=='movie'){

            return redirect()->route('backend.movies.index')->with('success', $message);

        }else{

            return redirect()->route('backend.tvshows.index')->with('success', $message);
        }
    }

    public function update_status(Request $request, Entertainment $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

        $data = Entertainment::where('id', $id)
            ->with([
                'entertainmentGenerMappings',
                'entertainmentStreamContentMappings',
                'entertainmentTalentMappings'
            ])
            ->first();


        $constants = Constant::whereIn('type', ['upload_type', 'movie_language', 'video_quality'])->get();
        $upload_url_type = $constants->where('type', 'upload_type');
        $movie_language = $constants->where('type', 'movie_language');
        $video_quality = $constants->where('type', 'video_quality');

        $plan = Plan::where('status', 1)->get();
        $genres = Genres::where('status', 1)->get();
        $actors = CastCrew::where('type', 'actor')->get();
        $directors = CastCrew::where('type', 'director')->get();
        $mediaUrls = getMediaUrls();

        $numberOptions = collect(range(1, 10))->mapWithKeys(function ($number) {
            return [$number => $number];
        });

        $data['genres'] = $data->entertainmentGenerMappings->pluck('genre_id')->toArray();
        $data['actors'] = $data->entertainmentTalentMappings->pluck('talent_id')->toArray();
        $data['directors'] = $data->entertainmentTalentMappings->pluck('talent_id')->toArray();

        return view('entertainment::backend.entertainment.edit', compact(
            'data',
            'upload_url_type',
            'plan',
            'movie_language',
            'genres',
            'numberOptions',
            'actors',
            'directors',
            'video_quality',
            'mediaUrls'
        ));
    }


    public function update(EntertainmentRequest $request, $id)
    {
        $request_data=$request->all();
        $entertainment = $this->entertainmentService->getById($id);
        $data = $this->entertainmentService->update($id, $request_data);
        $type = $entertainment->type;
        $message = trans('messages.entertainment_edit', ['type' => $type]);

        if ($type == 'movie') {
            return redirect()->route('backend.movies.index')
                ->with('success', $message);
        } else if ($type == 'tvshow') {
            return redirect()->route('backend.tvshows.index')
                ->with('success', $message);
        }
    }


    public function destroy($id)
    {
       $entertainment = $this->entertainmentService->getById($id);
       $type=$entertainment->type;
       $entertainment->delete();
       $message = trans('messages.delete_form', ['form' => $type]);
       return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $entertainment = $this->entertainmentService->getById($id);
        $type=$entertainment->type;
        $entertainment->restore();
        $message = trans('messages.restore_form', ['form' =>$type]);
        return response()->json(['message' => $message, 'status' => true], 200);

    }

    public function forceDelete($id)
    {
        $entertainment = $this->entertainmentService->getById($id);
        $type=$entertainment->type;
        $entertainment->forceDelete();
        $message = trans('messages.delete_form', ['form' =>$type]);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function downloadOption(Request $request, $id){

        $data = Entertainment::where('id',$id)->with('entertainmentDownloadMappings')->first();

        $module_title = 'Download Movie';

        $upload_url_type=Constant::where('type','upload_type')->get();
        $video_quality=Constant::where('type','video_quality')->get();

        return view('entertainment::backend.entertainment.download', compact('data','module_title','upload_url_type','video_quality'));

    }


   public function storeDownloads(Request $request, $id)
    {
        $data = $request->all();
        $this->entertainmentService->storeDownloads($data, $id);
        $message = trans('messages.set_download_url');

        return redirect()->route('backend.movies.index')->with('success', $message);
    }


    public function details($id)
    {
        $data = Entertainment::with([
            'entertainmentGenerMappings',
            'entertainmentStreamContentMappings',
            'entertainmentTalentMappings',
            'entertainmentReviews',
            'season',

        ])->findOrFail($id);

        $data->formatted_release_date = Carbon::parse($data->release_date)->format('d M, Y');
        $module_title = __('movie.details');
        return view('entertainment::backend.entertainment.details', compact('data','module_title'));
    }



}
