<?php

namespace Modules\LiveTV\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Trait\ModuleTrait;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Modules\Constant\Models\Constant;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\Subscriptions\Models\Plan;
use Modules\LiveTV\Models\LiveTvCategory;
use Modules\LiveTV\Http\Requests\TvChannelRequest;
use Modules\LiveTV\Models\TvChannelStreamContentMapping;

class LiveTvChannelController extends Controller
{
    protected string $exportClass = '\App\Exports\TvChannelExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }
    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'livetv.tvchannel', // module title
            'tv-channel', // module name
            'fa-solid fa-clipboard-list' // module icon
        );
    }
    /**
     * Display a listing of the resource.
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
                'value' => 'type',
                'text' => ' Type',
            ],
            [
                'value' => 'stream_type',
                'text' => ' Stream Type',
            ],
            [
                'value' => 'server_url',
                'text' => ' Server URL',
            ],
            [
                'value' => 'server_url1',
                'text' => ' Server URL1',
            ],
            [
                'value' => 'description',
                'text' => 'Description',
            ],
            [
                'value' => 'status',
                'text' => 'Status',
            ],
        ];
        $export_url = route('backend.tv-channel.export');

        return view('livetv::backend.channel.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));

    }
    public function bulk_action(Request $request)
{
    $ids = explode(',', $request->rowIds);
    $actionType = $request->action_type;
    $moduleName = 'Tv Channel'; // Adjust as necessary for dynamic use

    return $this->performBulkAction(LiveTvChannel::class, $ids, $actionType, $moduleName);
}


    public function index_data(Datatables $datatable, Request $request)
    {
        $query = LiveTvChannel::query()->with('TvCategory', 'TvChannelStreamContentMappings')->withTrashed();

        $filter = $request->filter;

        if (isset($filter['name'])) {
            $query->where('name', $filter['name']);
        }

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }

            if (isset($filter['category'])) {
                $query->where('category_id', $filter['category']);
            }
        }

        return $datatable->eloquent($query)
            ->editColumn('name', fn($data) => $data->name)
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" data-type="entertainment" onclick="dataTableRowCheck('.$data->id.',this)">';
            })
            ->addColumn('action', function ($data) {
                return view('livetv::backend.channel.action', compact('data'));
            })
            ->editColumn('status', function ($data) {
                $checked = '';
                if ($data->status) {
                    $checked = 'checked="checked"';
                }
                return '
                    <div class="form-check form-switch">
                        <input type="checkbox" data-url="' . route('backend.tv-channel.update_status', $data->id) . '" data-token="' . csrf_token() . '" class="switch-status-change form-check-input" id="datatable-row-' . $data->id . '" name="status" value="' . $data->id . '" ' . $checked . '>
                    </div>
                ';
            })
            ->editColumn('image', function ($data) {
                return '<img src="' .($data->poster_url) . '" alt="avatar" class="avatar avatar-40 rounded-pill">';
            })
            ->editColumn('updated_at', fn($data) => $this->formatUpdatedAt($data->updated_at))
            ->rawColumns(['action', 'status', 'check', 'image'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }

    private function formatUpdatedAt($updatedAt)
    {
        $diff = Carbon::now()->diffInHours($updatedAt);
        return $diff < 25 ? $updatedAt->diffForHumans() : $updatedAt->isoFormat('llll');
    }

    public function update_status(Request $request, LiveTvChannel $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plan=Plan::where('status',1)->get();
        $tvcategory=LiveTvCategory::where('status',1)->get();
        $embedded = Constant::where('type', 'STREAM_TYPE')->where('name', 'Embedded')->get();
        $url=Constant::where('type','STREAM_TYPE')->where('name', '!=', 'Embedded')->get();

        $streamMapping = null;
        $module_title = __('livetv.add_tvchannel');
        $mediaUrls = getMediaUrls();
        return view('livetv::backend.channel.create', compact('plan','tvcategory','embedded','url','streamMapping','module_title','mediaUrls'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TvChannelRequest $request)
    {
        $data = $request->all();
        $data['poster_url'] = setDefaultImage($data['poster_url']);


        if ($request->type === 't_url') {
            $data['stream_type'] = $request->input('stream_type');
            $data['server_url'] = $request->input('server_url');
            $data['server_url1'] = $request->input('server_url1');
            $data['embedded'] = null;
        } else if ($request->type === 't_embedded') {
            $data['stream_type'] = $request->input('stream_type');
            $data['server_url'] = null;
            $data['server_url1'] = null;
            $data['embedded'] = $request->input('embedded');
        }

        $LiveTvChannel = LiveTvChannel::create($data);


        if ($request->hasFile('poster_url')) {
            $file = $request->file('poster_url');
            StoreMediaFile($LiveTvChannel, $file, 'poster_url');

            $bannerData = LiveTvChannel::find($LiveTvChannel->id);
            $LiveTvChannel->poster_url = $bannerData->poster_url;
            $LiveTvChannel->save();
        }


        if (!empty($LiveTvChannel) && !empty($data['stream_type'])) {
            $mappingstream = [
                'tv_channel_id' => $LiveTvChannel->id,
                'type' => $data['type'],
                'stream_type' => $data['stream_type'],
                'embedded' => $data['embedded'],
                'server_url' => $data['server_url'],
                'server_url1' => $data['server_url1'],
            ];

            TvChannelStreamContentMapping::create($mappingstream);
        }

        $message = trans('livetv.tvchannel_added');
        return redirect()->route('backend.tv-channel.index')
            ->with('success', $message);
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('livetv::show');
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function edit($id)
    {
        $data = LiveTvChannel::withTrashed()->with('TvChannelStreamContentMappings')->findOrFail($id);
        $plan = Plan::where('status', 1)->get();
        $tvcategory = LiveTvCategory::where('status', 1)->get();
        $embedded = Constant::where('type', 'STREAM_TYPE')->where('name', 'Embedded')->get();
        $url = Constant::where('type', 'STREAM_TYPE')->where('name', '!=', 'Embedded')->get();
        $module_title = __('livetv.edit_tvchannel');
        $mediaUrls = getMediaUrls();
        return view('livetv::backend.channel.edit', compact('data', 'plan', 'tvcategory', 'embedded', 'url','module_title','mediaUrls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TvChannelRequest $request, $id): RedirectResponse
{
    $data = $request->all();

    $liveTvChannel = LiveTvChannel::findOrFail($id);

    if ($request->type === 't_url') {
        $data['stream_type'] = $request->input('stream_type');
        $data['server_url'] = $request->input('server_url');
        $data['server_url1'] = $request->input('server_url1');
        $data['embedded'] = null;
    } else if ($request->type === 't_embedded') {
        $data['stream_type'] = $request->input('stream_type');
        $data['server_url'] = null;
        $data['server_url1'] = null;
        $data['embedded'] = $request->input('embedded');
    }

    $liveTvChannel->update($data);

    if ($request->hasFile('poster_url')) {
        StoreMediaFile($liveTvChannel, $request->file('poster_url'), 'poster_url');

        $liveTvChannel->poster_url = $liveTvChannel->getFirstMediaUrl('poster_url');
        $liveTvChannel->save();
    }


    $mappingstream = TvChannelStreamContentMapping::where('tv_channel_id', $id)->first();

    if (!empty($mappingstream) && !empty($data['stream_type'])) {
        $mappingstream->update([
            'type' => $data['type'],
            'stream_type' => $data['stream_type'],
            'embedded' => $data['embedded'],
            'server_url' => $data['server_url'],
            'server_url1' => $data['server_url1'],
        ]);
    }

    $message = trans('livetv.tvchannel_updated');
    return redirect()->route('backend.tv-channel.index')->with('success', $message);
}

    /**
     * Remove the specified resource from storage.
     */


    public function destroy($id)
    {
        $data = LiveTvChannel::where('id',$id)->first();
        $data->delete();
        $message = __('LiveTvChannel Deleted Successfully');
        return response()->json(['message' =>  $message, 'status' => true], 200);
    }

    /**
     * Restore the specified resource from trash.
     */


    public function restore($id)
    {
        $data = LiveTvChannel::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'LiveTvChannel entry restored successfully','status' => true], 200);
    }

    /**
     * Permanently delete the specified resource.
     */


    public function forceDelete($id)
    {
        $data =  LiveTvChannel::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'LiveTvChannel entry permanently deleted']);
    }
}
