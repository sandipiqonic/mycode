<?php

namespace Modules\Coupon\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Coupon\Models\Coupon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Coupon\Http\Requests\CouponRequest;
use App\Trait\ModuleTrait;

class CouponsController extends Controller
{
    protected string $exportClass = '\App\Exports\CouponExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'coupon.title', // module title
            'coupons', // module name
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
        $export_url = route('backend.coupons.export');

        return view('coupon::backend.coupon.index', compact('module_action', 'filter','export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Coupon'; // Adjust as necessary for dynamic use
        $messageKey = __('Coupon.Post_status'); // You might want to adjust this based on the action

        return $this->performBulkAction(Coupon::class, $ids, $actionType, $messageKey, $moduleName);
    }

    public function update_status(Request $request, Coupon $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('country.status_update')]);
    }
    public function index_data(Datatables $datatable, Request $request)
    {
        $query = Coupon::query()->withTrashed();

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
              return view('coupon::backend.coupon.action', compact('data'));
          })
          ->editColumn('status', function ($row) {
            $checked = '';
            if ($row->status) {
                $checked = 'checked="checked"';
            }

            return '
            <div class="form-check form-switch ">
                <input type="checkbox" data-url="'.route('backend.coupons.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
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
        $module_title = __('coupon.add_title');
      return view('coupon::backend.coupon.create',compact('module_title'));
    }

    public function store(CouponRequest $request)
    {
        $data = $request->all();
        $coupon = Coupon::create($data);

        return redirect()->route('backend.coupons.index', $coupon->id)->with('success', '$coupon Added Successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Coupon::findOrFail($id);
        $module_title = __('coupon.edit_title');
    return view('coupon::backend.coupon.edit', compact('data','module_title'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $requestData = $request->all();
        $coupon->update($requestData);

        return redirect()->route('backend.coupons.index', $coupon->id)->with('success', 'Coupon Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Coupon::findOrFail($id);
        $data->delete();
        $message = __('Taxes Deleted Successfully');
        return response()->json(['message' =>  $message, 'type' => 'DELETE_FORM']);
    }

    public function restore($id)
    {
        $data = Coupon::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'Tax entry restored successfully']);
    }

    public function forceDelete($id)
    {
        $data = Coupon::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'Tax entry permanently deleted']);
    }
}
