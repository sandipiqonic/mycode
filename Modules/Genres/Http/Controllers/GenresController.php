<?php

namespace Modules\Genres\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Genres\Http\Requests\GenresRequest;
use Modules\Genres\Services\GenreService;
use Yajra\DataTables\DataTables;
use Modules\Genres\Models\Genres;
use App\Trait\ModuleTrait;

class GenresController extends Controller
{
    protected string $exportClass = '\App\Exports\GenresExport';
    protected $genreService;

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
    }

    public function __construct(GenreService $genreService)
    {
        $this->genreService = $genreService;
        $this->traitInitializeModuleTrait(
            'genres.title', 
            'genres', 
            'fa-solid fa-clipboard-list' 
        );
    }

    public function index()
    {
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
        $export_url = route('backend.genres.export');

        return view('genres::backend.genres.index', compact('module_action', 'export_import', 'export_columns', 'export_url'));
    }


    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Genres';
        return $this->performBulkAction(Genres::class, $ids, $actionType, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $filter = $request->filter;
        return $this->genreService->getDataTable($datatable, $filter);
    }


    public function update_status(Request $request, $id)
    {
        $this->genreService->updateGenre($id, ['status' => $request->status]);
        return response()->json(['status' => true, 'message' => __('service_providers.status_update')]);
    }

    public function create()
    {
        $module_title = __('genres.add_title');
        $mediaUrls = getMediaUrls();
        return view('genres::backend.genres.create', compact('mediaUrls','module_title'));
    }

    public function store(GenresRequest $request)
    {
        $data = $request->all();
    
        $this->genreService->createGenre($data);
        $message = __('messages.create_form', ['form' => 'Genres']);
        return redirect()->route('backend.genres.index')->with('success', $message);
    }

    public function show($id)
    {
        return view('genres::show');
    }

    public function edit($id)
    {
        $genre = $this->genreService->getGenreById($id);
        $mediaUrls = getMediaUrls();
        $module_title = __('genres.edit_title');
        return view('genres::backend.genres.edit', compact('genre', 'mediaUrls','module_title'));
    }

    public function update(GenresRequest $request, $id)
    {
        $data = $request->all();
        $genre = $this->genreService->getGenreById($id);

        if ($request->has('file_url') && $request->input('file_url') !== null) {
            $fileUrl = $request->input('file_url');
            $data['file_url'] = $fileUrl;
        } else {
            $data['file_url'] = $genre->file_url;
        }

        $this->genreService->updateGenre($id, $data);
        $message = __('messages.update_form', ['form' => 'Genres']);
        return redirect()->route('backend.genres.index')->with('success', $message);
    }

    public function destroy($id)
    {
        $this->genreService->deleteGenre($id);
        $message = __('messages.delete_form', ['form' => 'Genres']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $this->genreService->restoreGenre($id);
        $message = __('messages.restore_form', ['form' => 'Genres']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $this->genreService->forceDeleteGenre($id);
        $message = __('messages.delete_form', ['form' => 'Genres']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }
}
