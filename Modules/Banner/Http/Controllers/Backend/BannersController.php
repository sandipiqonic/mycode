<?php

namespace Modules\Banner\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Banner\Models\Banner;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Banner\Http\Requests\BannerRequest;
use App\Trait\ModuleTrait;
use Modules\Entertainment\Models\Entertainment;
use Modules\LiveTV\Models\LiveTV;
use Modules\LiveTV\Models\LiveTvChannel;

class BannersController extends Controller
{
    protected string $exportClass = '\App\Exports\BannerExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'banner.title', // module title
            'banners', // module name
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
                'value' => 'title',
                'text' => 'Title',
            ],
            [
                'value' => 'type',
                'text' => 'Type',
            ],
            [
                'value' => 'type_name',
                'text' => 'Name',
            ],
            [
                'value' => 'status',
                'text' => 'Status',
            ],

        ];
        $export_url = route('backend.banners.export');
        return view('banner::backend.banner.index', compact('module_action','filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Banner'; 
        $messageKey = __('Banner.Post_status'); 
        


        return $this->performBulkAction(Banner::class, $ids, $actionType, $messageKey, $moduleName);
    }

    public function update_status(Request $request, Banner $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('banner.status_update')]);
    }

    public function index_list($type)
    {
        $names = [];

        if ($type == 'movie' || $type == 'tvshow') {
            $names = Entertainment::where('type', $type)->pluck('name', 'id')->toArray();
        } else if ($type == 'livetv') {
            $names = LiveTvChannel::pluck('name', 'id')->toArray();
        }

        return response()->json($names);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $query = Banner::query()->withTrashed();

        $filter = $request->filter;

        if (isset($filter['name'])) {
            $query->where('title', 'like', '%' . $filter['name'] . '%');
        }

        if (isset($filter['column_status'])) {
            $query->where('status', $filter['column_status']);
        }

        return $datatable->eloquent($query)
            ->addColumn('check', function ($data) {
                return '<input type="checkbox" class="form-check-input select-table-row" id="datatable-row-' . $data->id . '" name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
            })
            ->addColumn('image', function ($data) {
                return '<img src="' . $data->file_url . '" alt="banner" class="avatar avatar-40 rounded-pill">';
            })
            ->addColumn('action', function ($data) {
                return view('banner::backend.banner.action', compact('data'));
            })
            ->editColumn('status', function ($data) {
                $checked = '';
                if ($data->status) {
                    $checked = 'checked="checked"';
                }
                return '
                    <div class="form-check form-switch">
                        <input type="checkbox" data-url="' . route('backend.banners.update_status', $data->id) . '" data-token="' . csrf_token() . '" class="switch-status-change form-check-input" id="datatable-row-' . $data->id . '" name="status" value="' . $data->id . '" ' . $checked . '>
                    </div>
                ';
            })
            ->editColumn('updated_at', function ($data) {
                $diff = \Carbon\Carbon::now()->diffInHours($data->updated_at);
                return $diff < 25 ? $data->updated_at->diffForHumans() : $data->updated_at->isoFormat('llll');
            })
            ->rawColumns(['action', 'status', 'check', 'image'])
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */


     public function create()
    {
        $module_title = __('banner.add_title');
        $types = ['movie' => 'Movie', 'tvshow' => 'TV Show', 'livetv' => 'Live TV'];
        $mediaUrls = getMediaUrls();
        return view('banner::backend.banner.create', compact('module_title', 'types','mediaUrls'));
    }


    public function store(BannerRequest $request)
    {

        $data = $request->all();

        $data['file_url'] = setDefaultImage($data['file_url']);
        $data['type_id'] = $request->input('type_id');
        $data['type_name'] = $request->input('type_name');

        $banner = Banner::create($data);

        if ($request->hasFile('file_url')) {
            $file = $request->file('file_url');

            // Store the media file
            StoreMediaFile($banner, $file, 'file_url');


            $bannerData = Banner::find($banner->id);
            $banner->file_url = $bannerData->file_url;
            $banner->save();
        }

        return redirect()->route('backend.banners.index')->with('success', 'Banner created successfully');
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Banner $banner)
    {
        $module_title = __('banner.edit_title');
        $types = ['movie' => 'Movie', 'tvshow' => 'TV Show', 'livetv' => 'Live TV'];
        $names = [];

        $mediaUrls = getMediaUrls();

        foreach ($types as $type => $label) {
            if ($type == 'movie' || $type == 'tvshow') {
                $names[$type] = Entertainment::where('type', $type)->pluck('name', 'id');
            } else if ($type == 'livetv') {
                $names[$type] = LiveTvChannel::pluck('name', 'id');
            }
        }

        return view('banner::backend.banner.edit', compact('module_title', 'types', 'names', 'banner','mediaUrls'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */


    public function update(BannerRequest $request, Banner $banner)
{
    $data = $request->all();
    $data['type_id'] = $request->input('type_id');
    $data['type_name'] = $request->input('type_name');

    $banner->update($data);

    if ($request->hasFile('file_url')) {
        $file = $request->file('file_url');
        StoreMediaFile($banner, $file, 'file_url');

        $banner->update(['file_url' => $banner->getFirstMediaUrl('file_url')]);
    }

    if ($request->input('file_url_removed') == 1) {
        $banner->clearMediaCollection('file_url');
        $banner->update(['file_url' => null]);
    }

    return redirect()->route('backend.banners.index')->with('success', 'Banner updated successfully');
}



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Banner::where('id',$id)->first();
        $data->delete();
        $message = __('Banner Deleted Successfully');
        return response()->json(['message' =>  $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $data = Banner::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'Banner entry restored successfully','status' => true], 200);
    }

    public function forceDelete($id)
    {
        $data = Banner::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'Banner entry permanently deleted']);
    }

}



