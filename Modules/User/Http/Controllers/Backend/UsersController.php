<?php

namespace Modules\User\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Http\Requests\PasswordRequest;
use App\Trait\ModuleTrait;
use Hash;
use Modules\Subscriptions\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExpiringSubscriptionEmail;

class UsersController extends Controller
{
    protected string $exportClass = '\App\Exports\UserExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'users.title', // module title
            'users', // module name
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
        $type=$request->type;
        $export_import = true;
        $export_columns = [
            [
                'value' => 'first_name',
                'text' => 'First Name',
            ],
            [
                'value' => 'last_name',
                'text' => 'Last Name',
            ],
            [
                'value' => 'email',
                'text' => 'Email',
            ],
            [
                'value' => 'mobile',
                'text' => 'Contact Number',
            ],
            [
                'value' => 'gender',
                'text' => 'Gender',
            ]
        ];
        $export_url = route('backend.users.export');

        return view('user::backend.users.index_datatable', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url', 'type'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'User'; // Adjust as necessary for dynamic use
        
        return $this->performBulkAction(User::class, $ids, $actionType, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $query = User::where('user_type','user');
        $filterValue  = $request->type;
        if($filterValue == 'soon-to-expire'){
            $query = User::role('user');
            $currentDate = Carbon::now();
            $expiryThreshold = $currentDate->copy()->addDays(7);
            $subscriptions = Subscription::with('user')
            ->where('status', 'active')
            ->whereDate('end_date','<=',$expiryThreshold)
            ->get();
            $userIds = $subscriptions->pluck('user_id');
            $query = User::where('user_type','user')->whereIn('id', $userIds);
        }
        $filter = $request->filter;

        if(isset($filter['name'])) {
            $fullName = $filter['name'];

            $query->where(function($query) use ($fullName) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$fullName%"]);
            });
        }
        if(isset($filter['email'])) {

            $query->where('email',$filter['email']);
        }

        if (isset($filter['column_status'])) {
            $query->where('status', $filter['column_status']);
        }
        return $datatable->eloquent($query)

          ->addColumn('check', function ($data) {
              return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" data-type="users" onclick="dataTableRowCheck('.$data->id.', this)">';
          })
          ->editColumn('name', function ($data) {
              return view('user::backend.users.user_details', compact('data'));
          })
          ->editColumn('action', function ($data) {
             return view('user::backend.users.action_column', compact('data'));
          })
          ->editColumn('expire_date', function ($data) {
            return \Carbon\Carbon::parse(optional($data->subscriptionPackage)->end_date)->format('d-m-Y');
        })
          ->filterColumn('name', function ($query, $keyword) {
            if (!empty($keyword)) {
                $query->where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
            }
        })
        ->orderColumn('name', function ($query, $order) {
            $query->orderByRaw("CONCAT(first_name, ' ', last_name) $order");
        }, 1)


        ->editColumn('status', function ($row) {
            $checked = '';
            if ($row->status) {
                $checked = 'checked="checked"';
            }

            return '
                <div class="form-check form-switch ">
                    <input type="checkbox" data-url="' . route('backend.users.update_status', $row->id) . '" data-token="' . csrf_token() . '" class="switch-status-change form-check-input"  id="datatable-row-' . $row->id . '"  name="status" value="' . $row->id . '" ' . $checked . '>
                </div>
            ';
        })

        ->editColumn('gender', function ($data) {
            return ucwords($data->gender);
        })


          ->editColumn('updated_at', fn($data) => $this->formatUpdatedAt($data->updated_at))
          ->rawColumns(['action','name', 'status', 'check','gender'])
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
        $module_title = __('users.lbl_add_new_user');
      return view('user::backend.users.form',compact('module_title'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->except('profile_image');

        $data['password']=Hash::make($data['password']);
        $data['user_type']='user';

        $user = User::create($data);
        $user->assignRole('user');

         if ($request->hasFile('profile_image')) {
             StoreMediaFile($user, $request->file('profile_image'),'profile_image');
         }

        return redirect()->route('backend.users.index')->with('success', 'user Added Successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        $module_title = __('users.lbl_edit_user');
    return view('user::backend.users.form', compact('data','module_title'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UserRequest $request, User $user)
    {
        $requestData = $request->except('feature_image');
        $user->update($requestData);

        if ($request->feature_image == 'null') {
            $user->clearMediaCollection('feature_image');
        }

        if ($request->hasFile('feature_image')) {
            storeMediaImage($user, $request->file('feature_image'), 'feature_image');
        }
        return redirect()->route('backend.users.index')->with('error', __('messages.permission_denied'));
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = User::find($id);
        $data->delete();
        $message = 'Users Deleted Successfully';
        return response()->json(['message' =>  $message, 'type' => 'DELETE_FORM']);
    }

    public function update_status(Request $request, User $id)
    {
        $id->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }

    public function changepassword($id){

        $id = $id;
        return view('user::backend.users.changepassword', compact('id'));

    }

    public function updatePassword(PasswordRequest $request,  $id){

        $user = User::where('id', $id)->first();

        if ($user == "") {
            $message = __('messages.user_not_found');
            return redirect()->route('backend.users.changepassword',['id' => $id])->with('error', $message);
        }

        $hashedPassword = $user->password;

        $match = Hash::check($request->old_password, $hashedPassword);

        $same_exits = Hash::check($request->password, $hashedPassword);
        if ($match) {
            if ($same_exits) {
                $message = __('messages.old_new_pass_same');
                return redirect()->route('backend.users.changepassword',['id' => $user->id])->with('error', $message);
            }

            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            $message = __('messages.password_change');
            return redirect()->route('backend.users.index', $user->id)->with('success', $message);

        } else {
            $message = __('messages.invalid_password');
            return redirect()->route('backend.users.changepassword',['id' => $user->id])->with('error', $message);
        }


    }

        // expire user send mail
        public function sendEmail(Request $request)
        {
            // Get user IDs with subscriptions expiring within 7 days
            $expiryThreshold = Carbon::now()->addDays(7);
            // $userIds = Subscription::where('status', '1')
            //     ->where('end_date', '<=', $expiryThreshold)
            //     ->pluck('user_id')
            //     ->toArray();
            $subscriptions = Subscription::with('user')
            ->where('status', 'active')
            ->whereDate('end_date','<=',$expiryThreshold)
            ->get();
            $userIds = $subscriptions->pluck('user_id');

            // Get users with the retrieved user IDs
            $users = User::whereIn('id', $userIds)->get();
           
            // Send email to each user
            foreach ($users as $user) {
                // Customize email send
                Mail::to($user->email)->send(new ExpiringSubscriptionEmail($user));
            }

            $message = __('customer.email_sent');
            return response()->json(['message' => $message, 'status' => true], 200);
        }

}
