<?php

namespace Modules\CastCrew\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CastCrew\Models\CastCrew;
use Modules\CastCrew\Http\Requests\CastCrewRequest;
use Yajra\DataTables\DataTables;
use App\Trait\ModuleTrait;
use Modules\CastCrew\Services\CastCrewService;
class CastCrewController extends Controller
{

    protected string $exportClass = '\App\Exports\CastCrewExport';
    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

    protected $castcrewService;

    public function __construct(CastCrewService $castcrewService)
    {
        $this->castcrewService = $castcrewService;

        $this->traitInitializeModuleTrait(
            'castcrew.castcrew_title',
            'castcrew', 
            'fa-solid fa-clipboard-list' 
        );
    }

        public function index(Request $request)
        {
            $module_action = 'List';
            $type = $request->type;

            switch($type) {

                case 'actor':

                    $module_title = 'castcrew.actors';

                    break;

                case 'director':
                    $module_title = 'castcrew.directors';

                    break;

                default:

                     $module_title = 'castcrew.castcrew_title';

                    break;

            }

            $export_import = true;
            $export_columns = [
                [
                    'value' => 'name',
                    'text' => 'Name',
                ],
                [
                    'value' => 'type',
                    'text' => 'Type',
                ],
                [
                    'value' => 'place_of_birth',
                    'text' => 'Birth Place',
                ],
                [
                    'value' => 'dob',
                    'text' => 'Birth Date',
                ],
                 [
                    'value' => 'bio',
                    'text' => 'Bio',
                ],

            ];

            $export_url = route('backend.castcrew.export');

            return view('castcrew::backend.castcrew.index', compact('module_action','module_title', 'export_import', 'export_columns', 'export_url','type'));

        }


    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Cast Crew';

        return $this->performBulkAction(CastCrew::class, $ids, $actionType, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $filter = $request->filter;
        $type=$request->type;
        return $this->castcrewService->getDataTable($datatable, $filter, $type);
    }
    

    public function create(Request $request)
    {
        $type=$request->type;

        switch($type) {

            case 'actor':

                $module_title = 'castcrew.actors';

                break;

            case 'director':
                $module_title = 'castcrew.directors';

                break;

            default:

                 $module_title = 'castcrew.castcrew_title';

                break;

        }

        $mediaUrls =  getMediaUrls();

        return view('castcrew::backend.castcrew.create',compact('type','module_title','mediaUrls'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CastCrewRequest $request)
    {

        $data = $request->all();

        $this->castcrewService->create($data);

         $message = __('messages.create_form', ['form' =>  $data['type'] ]);

         return redirect()->route('backend.castcrew.index', ['type' =>$data['type']])->with('success',$message);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('castcrew::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $data = $this->castcrewService->getById($id);

        $type=$data->type;

        switch($type) {

            case 'actor':

                $module_title = 'castcrew.actors';

                break;

            case 'director':
                $module_title = 'castcrew.directors';

                break;

            default:

                 $module_title = 'castcrew.castcrew_title';

                break;

        }

        $mediaUrls = getMediaUrls();

        return view('castcrew::backend.castcrew.edit',compact('data','type','module_title','mediaUrls'));
    }

   
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        $castcrew = $this->castcrewService->getById($id);

        if ($request->has('file_url') && $request->input('file_url') !== null) {

            $fileUrl = $request->input('file_url');

            $data['file_url'] = $fileUrl;

        } else {
            $data['file_url'] = $castcrew->file_url;
        }

        $this->castcrewService->update($id, $data);

        $message = __('messages.update_form', ['form' => $castcrew['type']]);

        return redirect()->route('backend.castcrew.index', ['type' => $castcrew['type']])->with('success',$message);

    }

    public function destroy($id)
    {
        $castcrew = $this->castcrewService->getById($id);

        $type=$castcrew->type;

        $castcrew->delete();

        $message = __('messages.delete_form', ['form' => $type]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $castcrew = $this->castcrewService->getById($id);

        $type=$castcrew->type;

        $castcrew->restore();

        $message = __('messages.restore_form',  ['form' => $type]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $castcrew = $this->castcrewService->getById($id);

        $type=$castcrew->type;

        $castcrew->forcedelete();

        $message = __('messages.delete_form',  ['form' => $type]);

        return response()->json(['message' => $message, 'status' => true], 200);
    }

}
