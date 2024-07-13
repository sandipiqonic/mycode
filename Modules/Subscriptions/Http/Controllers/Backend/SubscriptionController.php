<?php

namespace Modules\Subscriptions\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Subscriptions\Models\Subscription;
use Yajra\DataTables\DataTables;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Subscriptions';

        // module name
        $this->module_name = 'subscriptions';

        // module icon
        $this->module_icon = 'fa-solid fa-clipboard-list';

        view()->share([
            'module_title' => $this->module_title,
            'module_icon' => $this->module_icon,
            'module_name' => $this->module_name,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $module_action = 'User List';


        return view('subscriptions::backend.subscriptions.index', compact('module_action'));
    }

    public function index_data(Datatables $datatable)
    {
        $query = Subscription::query()
            ->with('user') 
            ->where('status', 'active');

        $datatable = $datatable->eloquent($query)
            ->editColumn('user_id', function ($data) {
                return optional($data->user)->first_name.' '.optional($data->user)->last_name ?? '-';
            })
            ->editColumn('start_date', function ($data) {
                return Carbon::parse($data->start_date)->format('Y-m-d');
            })
            ->editColumn('end_date', function ($data) {
                return Carbon::parse($data->end_date)->format('Y-m-d');
            })
            ->editColumn('amount', function ($data) {
                return number_format($data->amount, 2);
            })
            ->editColumn('name', function ($data) {
                return $data->name;
            })
            ->orderColumns(['id'], '-:column $1');

        return $datatable->rawColumns(array_merge(['user_id', 'start_date', 'end_date', 'amount', 'name']))
            ->toJson();
    }
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'subscription'; 
        $messageKey = __('subscription.Post_status'); 


        return $this->performBulkAction(subscription::class, $ids, $actionType, $messageKey, $moduleName);
    }

}
