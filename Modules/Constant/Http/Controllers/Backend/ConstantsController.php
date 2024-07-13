<?php

namespace Modules\Constant\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Constant\Models\Constant;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Constant\Http\Requests\ConstantRequest;
use App\Trait\ModuleTrait;

class ConstantsController extends Controller
{
    protected string $exportClass = '\App\Exports\ConstantExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
    }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'constant.title', // module title
            'constants', // module name
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
         $constants = Constant::all(); 
         $module_action = 'List';
     
         $export_import = true;
         $export_columns = [
            [
                'value' => 'type',
                'text' => 'Type',
            ],
         ];
         $export_url = route('backend.constants.export');
     
         return view('constant::index', compact('constants', 'module_action', 'export_import', 'export_columns', 'export_url'));
     }
     




    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Constant';
        $messageKey = __('Constant.Post_status');

        return $this->performBulkAction(Constant::class, $ids, $actionType, $messageKey, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
{
    $query = Constant::query()->withTrashed();

    $filter = $request->filter;

    if (isset($filter['name'])) {
        $query->where('name', $filter['name']);
    }
    if (isset($filter['column_status'])) {
        $query->where('status', $filter['column_status']);
    }

    return $datatable->eloquent($query)
        ->editColumn('name', fn($data) => $data->name)
        ->addColumn('check', function ($data) {
            return '<input type="checkbox" class="form-check-input select-table-row" id="datatable-row-' . $data->id . '"
                name="datatable_ids[]" value="' . $data->id . '" onclick="dataTableRowCheck(' . $data->id . ')">';
        })
        ->addColumn('action', function ($data) {
            return view('constant::action', compact('data'));
        })
        ->editColumn('status', function ($row) {
            $checked = $row->status ? 'checked="checked"' : '';
            return '
            <div class="form-check form-switch">
                <input type="checkbox" data-url="' . route('backend.constants.update_status', $row->id) . '"
                    data-token="' . csrf_token() . '" class="switch-status-change form-check-input"
                    id="datatable-row-' . $row->id . '" name="status" value="' . $row->id . '" ' . $checked . '>
            </div>
            ';
        })
        ->editColumn('updated_at', function ($data) {
            $diff = Carbon::now()->diffInHours($data->updated_at);
            return $diff < 25 ? $data->updated_at->diffForHumans() : $data->updated_at->isoFormat('llll');
        })
        ->rawColumns(['action', 'status', 'check'])
        ->orderColumns(['id'], '-:column $1')
        ->make(true);
}

    public function update_status(Request $request, constant $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }


    private function formatUpdatedAt($updatedAt)
    {
        $diff = Carbon::now()->diffInHours($updatedAt);
        return $diff < 25 ? $updatedAt->diffForHumans() : $updatedAt->isoFormat('llll');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

     public function create(Request $request)
{
    $module_title = __('constant.constant_title');

    $types = Constant::distinct()->pluck('type'); 
    return view('constant::create', compact('module_title', 'types'));
}



    public function store(ConstantRequest $request)
    {
        $data = $request->all();

        $existingConstant = Constant::where('name', $data['name'])
            ->where('type', $data['type'])
            ->first();

        if ($existingConstant) {
            $existingConstant->update($data);
            $message = 'Constant updated successfully';
        } else {
            $constant = Constant::create($data);
            $message = 'Constant added successfully';
        }

        return redirect()->route('backend.constants.index', ['type' => $data['type']])->with('success', $message);
    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $module_title = __('constant.constant_edit');
        $data = Constant::find($id);
        $types = Constant::distinct()->pluck('type'); 
       
        return view('constant::edit', compact('module_title','data','types' ));

    
    }

   



    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = Constant::find($id);
        if (!$data) {
            return redirect()->route('backend.constants.index')->with('error', 'Constant not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        $data->update($validated);

        return redirect()->route('backend.constants.index', ['type' => $data->type])
            ->with('success', 'Constant updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Constant::findOrFail($id);
        $data->delete();
        $message = __('Taxes Deleted Successfully');
        return response()->json(['message' => $message, 'type' => 'DELETE_FORM']);
    }

    public function restore($id)
    {
        $data = Constant::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'Tax entry restored successfully']);
    }

    public function forceDelete($id)
    {
        $data = Constant::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'Tax entry permanently deleted']);
    }
}
