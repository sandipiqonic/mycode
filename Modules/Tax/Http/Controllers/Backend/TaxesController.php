<?php

namespace Modules\Tax\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Modules\Tax\Models\Tax;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Tax\Http\Requests\TaxRequest;
use App\Trait\ModuleTrait;

class TaxesController extends Controller
{
    protected string $exportClass = '\App\Exports\TaxExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'tax.title', // module title
            'taxes', // module name
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
            ]
        ];
        $export_url = route('backend.taxes.export');

        return view('tax::backend.tax.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Tax'; // Adjust as necessary for dynamic use
       

        return $this->performBulkAction(Tax::class, $ids, $actionType, $moduleName);
    }

    public function update_status(Request $request, tax $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $query = Tax::query()->withTrashed();

        $filter = $request->filter;

        if (isset($filter['name'])) {
            $query->where('name', $filter['name']);
        }

        return $datatable->eloquent($query)
          ->editColumn('name', fn($data) => $data->name)
          ->addColumn('check', function ($data) {
              return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" data-type="taxes" onclick="dataTableRowCheck('.$data->id.',this)">';
          })
          ->addColumn('action', function ($data) {
              return view('tax::backend.tax.action', compact('data'));
          })
          ->editColumn('status', function ($row) {
            $checked = '';
            if ($row->status) {
                $checked = 'checked="checked"';
            }

            return '
            <div class="form-check form-switch ">
                <input type="checkbox" data-url="'.route('backend.taxes.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
            </div>
           ';
        })
          ->editColumn('updated_at', fn($data) => formatUpdatedAt($data->updated_at))
          ->rawColumns(['action', 'status', 'check'])
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
      return view('tax::backend.tax.create');
    }

    public function store(TaxRequest $request)
    {
        $data = $request->all();

        $tax = Tax::create($data);
        $message = __('messages.create_form', ['form' => __('tax.title')]);
        return redirect()->route('backend.taxes.index', $tax->id)->with($message);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Tax::findOrFail($id);
    return view('tax::backend.tax.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(TaxRequest $request, Tax $tax)
    {
        $requestData = $request->all();
        $tax->update($requestData);
        $message = __('messages.update_form', ['form' => __('tax.title')]);
        return redirect()->route('backend.taxes.index', $tax->id)->with($message );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Tax::findOrFail($id);
        $data->delete();
        $message = __('messages.delete_form', ['form' => __('tax.title')]);
        return response()->json(['message' =>  $message, 'type' => 'DELETE_FORM']);
    }

    public function restore($id)
    {
        $data = Tax::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'Tax entry restored successfully']);
    }

    public function forceDelete($id)
    {
        $data = Tax::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'Tax entry permanently deleted']);
    }
}
