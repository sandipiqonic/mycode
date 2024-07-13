<?php

namespace Modules\LiveTV\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Trait\ModuleTrait;
use Modules\LiveTV\Models\LiveTvCategory;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Modules\LiveTV\Http\Requests\TvCategoryRequest;

class LiveTvCatgeoryController extends Controller
{

    protected string $exportClass = '\App\Exports\LiveTvCategoryExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
         // Page Title
         $this->module_title = 'livetv.title';

         // module name
         $this->module_name = 'tv-category';

         $this->module_icon = 'fa-solid fa-clipboard-list';

        $this->traitInitializeModuleTrait(
            'livetv.title', // module title
            'tv-category', // module name
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
                'text' => 'Name',
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
        $export_url = route('backend.tv-category.export');

        return view('livetv::backend.category.index',compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Tv Category'; // Adjust as necessary for dynamic use

        return $this->performBulkAction(LiveTvCategory::class, $ids, $actionType, $moduleName);
    }



    public function index_data(Datatables $datatable, Request $request)
    {
        $module_name = $this->module_name;
        $query = LiveTvCategory::query();
        $filter = $request->filter;
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->withTrashed();
        }
        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }

            if (isset($filter['category'])) {
                $query->where('id', $filter['category']);
            }
        }

        $datatable = $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="tvcategory" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
            ->editColumn('image', function ($data) {
                return '<img src="' .($data->file_url) . '" alt="avatar" class="avatar avatar-40 rounded-pill">';
            })
            ->addColumn('action', function ($data) {
                return view('livetv::backend.category.action', compact('data'));
            })
            ->editColumn('status', function ($row) {
                $checked = '';
                if ($row->status) {
                    $checked = 'checked="checked"';
                }

                return '
                <div class="form-check form-switch ">
                    <input type="checkbox" data-url="'.route('backend.tv-category.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
                </div>
               ';
            })
            ->editColumn('updated_at', function ($data) {
                $module_name = $this->module_name;

                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('llll');
                }
            })
            ->orderColumns(['id'], '-:column $1');

        return $datatable->rawColumns(array_merge(['action', 'status', 'check','image']))
            ->toJson();
    }
    public function update_status(Request $request, LiveTvCategory $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module_title = __('livetv.add_title');
        $mediaUrls = getMediaUrls();
        return view('livetv::backend.category.create',compact('module_title','mediaUrls'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TvCategoryRequest $request)
    {
        $data = LiveTvCategory::create($request->all());
        $data['file_url'] = setDefaultImage($data['file_url']);


        if ($request->hasFile('file_url')) {
            $file = $request->file('file_url');
            StoreMediaFile($data, $file, 'file_url');

            $categoryData = LiveTvCategory::find($data->id);
            $data->file_url = $categoryData->file_url;
            $data->save();
        }


        $message = __('messages.create_form', ['form' => __($this->module_title)]);

        return redirect()->route('backend.tv-category.index')->with($message);
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
        $data = LiveTvCategory::where('id',$id)->first();
        $module_title = __('livetv.edit_title');
        $mediaUrls = getMediaUrls();
        return view('livetv::backend.category.edit',compact('data','module_title','mediaUrls'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TvCategoryRequest $request, $id)
    {
        $data = LiveTvCategory::where('id',$id)->first();




        if ($request->has('file_url_removed') && $request->file_url_removed == 1) {

            $data->clearMediaCollection('file_url');
        }


        if ($request->hasFile('file_url')) {
            StoreMediaFile($data, $request->file('file_url'), 'file_url');

            $data->file_url = $data->getFirstMediaUrl('file_url');
            $data->save();
       }


       $data->update($request->all());

       $message = __('messages.update_form', ['form' => __($this->module_title)]);

        return redirect()->route('backend.tv-category.index')->with($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = LiveTvCategory::where('id',$id)->first();

        $data->delete();

        $message = __('messages.delete_form', ['form' => __($this->module_title)]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }
}
