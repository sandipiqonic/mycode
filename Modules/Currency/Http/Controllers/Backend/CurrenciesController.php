<?php

namespace Modules\Currency\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Currency\Models\Currency;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Currency\Http\Requests\CurrencyRequest;
use App\Trait\ModuleTrait;

class CurrenciesController extends Controller
{
    protected string $exportClass = '\App\Exports\CurrencyExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'currency.title', // module title
            'currencies', // module name
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
        $export_url = route('backend.currencies.export');

        return view('currency::backend.currency.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function update_status(Request $request, Currency $id)
    {

        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('currencies.status_update')]);
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Currency'; // Adjust as necessary for dynamic use
        $messageKey = __('Currency.Post_status'); // You might want to adjust this based on the action

        return $this->performBulkAction(Currency::class, $ids, $actionType, $messageKey, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $query = Currency::query()->withTrashed();

        $filter = $request->filter;

        if (isset($filter['name'])) {
            $query->where('name', $filter['name']);
        }

        return $datatable->eloquent($query)
          ->editColumn('name', fn($data) => $data->name)
          ->addColumn('check', function ($data) {
              return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" onclick="dataTableRowCheck('.$data->id.')">';
          })
          ->addColumn('action', function ($data) {
              return view('currency::backend.currency.action', compact('data'));
          })
          ->editColumn('status', function ($row) {
            $checked = '';
            if ($row->status) {
                $checked = 'checked="checked"';
            }

            return '
            <div class="form-check form-switch ">
                <input type="checkbox" data-url="'.route('backend.currencies.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
            </div>
           ';
        })
          ->editColumn('updated_at', fn($data) => $this->formatUpdatedAt($data->updated_at))
          ->rawColumns(['action', 'status', 'check'])
          ->orderColumns(['id'], '-:column $1')
          ->make(true);
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

      public function create()
    {
        $module_title = __('currency.lbl_add');
      return view('currency::backend.currency.create',compact('module_title'));
    }

    public function store(CurrencyRequest $request)
    {
        $data = $request->all();
        $currency = Currency::create($data);

        return redirect()->route('backend.currencies.index', $currency->id)->with('success', '$currency Added Successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Currency::findOrFail($id);
        $module_title = __('currency.lbl_edit');
    return view('currency::backend.currency.edit', compact('data','module_title'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CurrencyRequest $request, Currency $currency)
    {
        $requestData = $request->all();
        $currency->update($requestData);

        return redirect()->route('backend.currencies.index', $currency->id)->with('success', 'Currency Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Currency::findOrFail($id);
        $data->delete();
        $message = __('Taxes Deleted Successfully');
        return response()->json(['message' =>  $message, 'type' => 'DELETE_FORM']);
    }

    public function restore($id)
    {
        $data = Currency::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'Tax entry restored successfully']);
    }

    public function forceDelete($id)
    {
        $data = Currency::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'Tax entry permanently deleted']);
    }
}
