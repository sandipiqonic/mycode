<?php

namespace Modules\Page\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Page\Models\Page;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Page\Http\Requests\PageRequest;
use App\Trait\ModuleTrait;

class PagesController extends Controller
{
    protected string $exportClass = '\App\Exports\PageExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'page.title', // module title
            'pages', // module name
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
        $export_url = route('backend.pages.export');

        return view('page::backend.pages.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Page'; // Adjust as necessary for dynamic use
        $messageKey = __('Page.Post_status'); // You might want to adjust this based on the action

        return $this->performBulkAction(Page::class, $ids, $actionType, $messageKey, $moduleName);
    }

    public function update_status(Request $request, Page $id)
    {
        $id->update(['status' => $request->status]);
    
        return response()->json(['status' => true, 'message' => __('page.status_updated')]);
    }
    public function index_data(Datatables $datatable, Request $request)
    {   $query = Page::query()->withTrashed();
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
              return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$data->id.'"  name="datatable_ids[]" value="'.$data->id.'" onclick="dataTableRowCheck('.$data->id.')">';
          })
          ->addColumn('action', function ($data) {
              return view('page::backend.pages.action', compact('data'));
          })
          ->editColumn('status', function ($row) {
            $checked = $row->status ? 'checked="checked"' : '';
            return '
                <div class="form-check form-switch ">
                    <input type="checkbox" data-url="' . route('backend.pages.update_status', $row->id) . '" data-token="' . csrf_token() . '
                    " class="switch-status-change form-check-input" id="datatable-row-' . $row->id . '" name="status" value="
                    ' . $row->id . '" ' . $checked . '>
                </div>
                
            ';  
          })
        //   ->editColumn('updated_at', fn($data) => formatUpdatedAt($data->updated_at))
          ->editColumn('updated_at', function ($data) {
            $diff = \Carbon\Carbon::now()->diffInHours($data->updated_at);
            return $diff < 25 ? $data->updated_at->diffForHumans() : $data->updated_at->isoFormat('llll');
            })
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
      return view('page::backend.pages.create');
    }

    
    public function page($slug )
    {
        $data = Page::where('slug', $slug)->first();
        $navs = Page::all();
        
        return view('page::backend.pages.page',array_merge(compact('data', 'navs'), ['noLayout' => true]));
    }

    public function store(PageRequest $request)
    {
            $data = $request->all();
            $page = Page::create($data);

            return redirect()->route('backend.pages.index', $page->id)->with('success', '$page Added Successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */

    
    public function edit($id)
    {
        $data = Page::findOrFail($id);
    return view('page::backend.pages.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PageRequest $request, Page $page)
    {
        $requestData = $request->all();
        $page->update($requestData);

        return redirect()->route('backend.pages.index', $page->id)->with('success', 'Page Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Page::findOrFail($id);
        $data->delete();
        $message = __('Taxes Deleted Successfully');
        return response()->json(['message' =>  $message, 'type' => 'DELETE_FORM']);
    }

    public function restore($id)
    {
        $data = Page::withTrashed()->findOrFail($id);
        $data->restore();
        return response()->json(['message' => 'Tax entry restored successfully']);
    }

    public function forceDelete($id)
    {
        $data = Page::withTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json(['message' => 'Tax entry permanently deleted']);
    }
}
