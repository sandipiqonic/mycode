<?php

namespace Modules\Subscriptions\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Subscriptions\Http\Requests\PlanRequest;
use Modules\Subscriptions\Models\Plan;
use Modules\Subscriptions\Models\PlanLimitation;
use Modules\Subscriptions\Models\PlanLimitationMapping;
use Yajra\DataTables\DataTables;
use Currency;
use Modules\Constant\Models\Constant;
use App\Trait\ModuleTrait;

class PlanController extends Controller
{
    protected string $exportClass = '\App\Exports\PlanExport';
    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }
    public function __construct()
    {
        // // Page Title
        // $this->module_title = 'plan.title';

        // // module name
        // $this->module_name = 'plans';

        // // module icon
        // $this->module_icon = 'fa-solid fa-clipboard-list';

        // view()->share([
        //     'module_title' => $this->module_title,
        //     'module_icon' => $this->module_icon,
        //     'module_name' => $this->module_name,
        // ]);
        $this->traitInitializeModuleTrait(
            'plan.title', // module title
            'plans', // module name
            'fa-solid fa-clipboard-list' // module icon
        );
    }

    // public function bulk_action(Request $request)
    // {
    //     $ids = explode(',', $request->rowIds);

    //     $actionType = $request->action_type;

    //     $message = __('messages.bulk_update');

    //     switch ($actionType) {
    //         case 'change-status':
    //             $service_providers = Plan::whereIn('id', $ids)->update(['status' => $request->status]);
    //             $message = __('messages.bulk_plan_update');
    //             break;

    //         case 'delete':
    //             Plan::whereIn('id', $ids)->delete();
    //             $message = __('messages.bulk_plan_delete');
    //             break;

    //         default:
    //             return response()->json(['status' => false, 'message' => __('service_providers.invalid_action')]);
    //             break;
    //     }

    //     return response()->json(['status' => true, 'message' => __('messages.bulk_update')]);
    // }
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Plan'; // Adjust as necessary for dynamic use

        return $this->performBulkAction(Plan::class, $ids, $actionType, $moduleName);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $module_action = 'List';
        $module_name = 'plans';

        $filter = [
            'status' => $request->status,
        ];

        $plan= Plan::count();

        $minPrice = Plan::min('price');
        $maxPrice = Plan::max('price');

        $export_import = true;
        $export_columns = [
            [
                'value' => 'name',
                'text' => 'Name',
            ],
            [
                'value' => 'duration_value',
                'text' => 'Duration Value',
            ],
            [
                'value' => 'duration',
                'text' => 'Duration',
            ],
            [
                'value' => 'price',
                'text' => 'Price',
            ],
            [
                'value' => 'level',
                'text' => 'Level',
            ],
            [
                'value' => 'status',
                'text' => 'Status',
            ],
        ];
        $export_url = route('backend.plans.export');
        return view('subscriptions::backend.plan.index', compact('module_action', 'module_name' , 'export_import', 'export_columns', 'export_url','filter','plan','minPrice','maxPrice'));
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $query = Plan::withTrashed();

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }

            if (isset($filter['name'])) {
                $namePattern = '%' . $filter['name'] . '%';
               $query->where('name', 'like', $namePattern);
            }

            if (isset($filter['price'])) {

                $priceRange = explode(' - ', $filter['price']);

                if (count($priceRange) == 2) {
                    $minPrice = (float) $priceRange[0];
                    $maxPrice = (float) $priceRange[1];

                    $query->whereBetween('price', [$minPrice, $maxPrice]);
                }
            }


            if (isset($filter['level'])) {
                $query->where('level', $filter['level']);
            }
        }



        $datatable = $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="plan" onclick="dataTableRowCheck('.$row->id.', this)">';
            })
            ->addColumn('action', function ($data) {
                return view('subscriptions::backend.plan.action_column', compact('data'));
            })

            ->editColumn('price', function ($data) {
                return Currency::format($data->price);
            })


            ->editColumn('level', function ($data) {
                return __("plan.lbl_level").' '.$data->level;
            })


            ->editColumn('duration', function ($data) {
                return $data->duration_value.' '.$data->duration;
            })


            ->editColumn('status', function ($row) {
                $checked = '';
                if ($row->status) {
                    $checked = 'checked="checked"';
                }

                return '
                <div class="form-check form-switch ">
                    <input type="checkbox" data-url="'.route('backend.plans.update_status', $row->id).'" data-token="'.csrf_token().'" class="switch-status-change form-check-input"  id="datatable-row-'.$row->id.'"  name="status" value="'.$row->id.'" '.$checked.'>
                </div>
               ';
            })
            ->editColumn('updated_at', function ($data) {
                $diff = Carbon::now()->diffInHours($data->updated_at);

                if ($diff < 25) {
                    return $data->updated_at->diffForHumans();
                } else {
                    return $data->updated_at->isoFormat('llll');
                }
            })
            ->orderColumns(['id'], '-:column $1');

        return $datatable->rawColumns(array_merge(['action','name', 'type', 'duration', 'amount', 'planlimitation', 'status', 'check']))
            ->toJson();
    }

    public function index_list(Request $request)
    {
        $term = trim($request->q);

        $query_data = PlanLimitation::where('status', 1)
            ->where(function ($q) {
                if (! empty($term)) {
                    $q->orWhere('name', 'LIKE', "%$term%");
                }
            })->get();

        $data = [];

        foreach ($query_data as $row) {
            $data[] = [
                'id' => $row->id,
                'name' => $row->name,
                'limit' => $row->limit,
            ];
        }

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Renderable
     */


     public function create()
     {
        $planLimits=PlanLimitation::where('status',1)->get();

        $downloadoptions=Constant::where('type','video_quality')->get();

        $module_title = __('plan.lbl_add_new_plan');

       return view('subscriptions::backend.plan.form',compact('planLimits','downloadoptions','module_title'));
     }


    public function store(PlanRequest $request)
    {
        $data = $request->all();

        $data['identifier'] = strtolower(str_replace(' ', '_', $data['name']));

        $plan_level=Plan::max('level');

        if($plan_level){

            $data['level']=$plan_level+1;

        }else{

            $data['level']=1;

        }

        $plandata = Plan::create($data);

        // if($request->has('limits') && !empty($request->limits)){

        //     foreach ($request->input('limits') as $limit) {
        //         $additionalLimit = null;
        //         if ($limit['limitation_slug'] == 'download-status') {
        //             $additionalLimit = json_encode($request->input('download_options', []));
        //         } elseif ($limit['limitation_slug'] == 'device-limit') {
        //             $additionalLimit = $request->input('device_limit_value');
        //         }
    
        //         PlanLimitationMapping::create([
        //             'plan_id' => $plandata->id,
        //             'planlimitation_id' => $limit['planlimitation_id'],
        //             'limitation_slug' => $limit['limitation_slug'],
        //             'limitation_value' => $limit['value'],
        //             'limit' => $additionalLimit,
        //         ]);
        //     }

        // }

        if ($request->has('limits') && !empty($request->limits)) {
            foreach ($request->input('limits') as $limit) {
                $additionalLimit = null;
                
                if ($limit['limitation_slug'] == 'download-status') {
                    // Convert download options to integers
                    $downloadOptions = array_map('intval', $request->input('download_options', []));
                    $additionalLimit = json_encode($downloadOptions);
                } elseif ($limit['limitation_slug'] == 'device-limit') {
                    $additionalLimit = $request->input('device_limit_value');
                }
        
                PlanLimitationMapping::create([
                    'plan_id' => $plandata->id,
                    'planlimitation_id' => $limit['planlimitation_id'],
                    'limitation_slug' => $limit['limitation_slug'],
                    'limitation_value' => $limit['value'],
                    'limit' => $additionalLimit,
                ]);
            }
        }

        $message = __('messages.create_form', ['form' => __('plan.singular_title')]);

        return redirect()->route('backend.plans.index')->with('success', $message);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Plan::findOrFail($id);

        $plan= Plan::max('level');

        $plan=$plan;

        $planLimits = PlanLimitationMapping::where('plan_id', $id)->get();

        // $planLimits=PlanLimitation::where('status',1)->get();

        $downloadoptions=Constant::where('type','video_quality')->get();
        $module_title = __('plan.lbl_edit_plan');
       
        return view('subscriptions::backend.plan.edit_form',compact('plan','data','planLimits','downloadoptions','module_title'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(PlanRequest $request, $id)
    {

        $request_data = $request->all();

        $data = Plan::where('id', $id)->first();

        $level=$data->level;

          $data->update($request_data);


        if ($level < $request_data['level']) {

            $plansToUpdate = Plan::where('level', '>',$level)->where('id','!=',$id)->get();

            foreach ($plansToUpdate as $plan) {
                $plan->update(['level' => $plan->level + 1]);
            }
        }


        if($level > $request_data['level']){

            $plansToUpdate = Plan::where('level', '<',$level)->where('id','!=',$id)->get();

            foreach ($plansToUpdate as $plan) {
                $plan->update(['level' => $plan->level + 1]);
            }

        }

   
        if($request->has('limits') && !empty($request->limits)) {
            foreach ($request->input('limits') as $limit) {
           
                $downloadOptions = [];
        
                if ($limit['limitation_slug'] === 'download-status') {
                    $downloadOptions = array_map('intval', $request->input('download_options', []));
                }
        
                PlanLimitationMapping::updateOrCreate(
                    [
                        'plan_id' => $id,
                        'planlimitation_id' => $limit['planlimitation_id'],
                        'limitation_slug' => $limit['limitation_slug']
                    ],
                    [
                        'limitation_value' => $limit['value'],
                        'limit' => $limit['limitation_slug'] === 'device-limit' ? 
                                   ($limit['value'] == 1 ? $request->input('device_limit_value') : 0) : 
                                   ($limit['limitation_slug'] === 'download-status' ? json_encode($downloadOptions) : null),
                    ]
                );
            }
        }

        $message = __('messages.update_form', ['form' => __('plan.singular_title')]);

        return redirect()->route('backend.plans.index')->with('success', $message );


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */

    
    public function destroy($id)
    {
        $data = Plan::findOrFail($id);
        $data->delete();
        $message = trans('messages.delete_form', ['form' => 'plan']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $data = Plan::withTrashed()->findOrFail($id);
        $data->restore();
        $message = trans('messages.restore_form', ['form' => 'plan']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $data = Plan::withTrashed()->findOrFail($id);
        $data->forceDelete();
        $message = trans('messages.delete_form', ['form' => 'plan']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }


    public function update_status(Request $request, Plan $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }
}
