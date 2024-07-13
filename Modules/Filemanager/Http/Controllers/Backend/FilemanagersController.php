<?php

namespace Modules\Filemanager\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Filemanager\Models\Filemanager;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Filemanager\Http\Requests\FilemanagerRequest;
use App\Trait\ModuleTrait;
use App\Models\Setting;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessFileUpload;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Aws\S3\S3Client;

class FilemanagersController extends Controller
{
    protected string $exportClass = '\App\Exports\FilemanagerExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
    }

    public function __construct()
    {
        $this->traitInitializeModuleTrait(
            'filemanager.title', // module title
            'filemanagers', // module name
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
        $module_action = 'List';
        $mediaUrls =  getMediaUrls();

        return view('filemanager::backend.filemanager.index', compact('module_action', 'mediaUrls'));
    }

    public function getMediaStore(Request $request)
    {
        $mediaUrls =  getMediaUrls();

        return response()->json(['response' => $mediaUrls]);
    }

    public function store(FilemanagerRequest $request)
    {
        $jobs = [];
        if ($request->hasFile('file_url')) {
            $discType = Setting::where('name', 'disc_type')->value('val');
            foreach ($request->file('file_url') as $file) {
                $temporaryPath = $file->store('temp');
                $filemanager = Filemanager::create([
                    'file_url' => $temporaryPath,
                ]);

                $job = new ProcessFileUpload($filemanager, $temporaryPath, $discType);

                $jobs[] = $job;
            }
        }

        $batch = Bus::batch($jobs)->dispatch();

        return redirect()->route('backend.filemanagers.index')->with('success', 'Filemanager Added Successfully');
    }

    public function upload(Request $request)
    {
        $fileChunk = $request->file('file_chunk');
        $fileName = $request->input('file_name');
        $index = $request->input('index');
        $totalChunks = $request->input('total_chunks');

        $temporaryDirectory = storage_path('app/temp/uploads/');
        $filePath = $temporaryDirectory . $fileName;

        // Store the chunk
        $fileChunk->move($temporaryDirectory, $fileName);

        // If all chunks are uploaded, merge them
        if ($index + 1 == $totalChunks) {
            $outputFilePath = storage_path('app/temp/uploads/') . $fileName;
            // Create the final file from chunks
            $outputFile = fopen($outputFilePath, 'ab');

            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkFilePath = $temporaryDirectory . $fileName;
                $chunkFile = fopen($chunkFilePath, 'rb');
                stream_copy_to_stream($chunkFile, $outputFile);
                fclose($chunkFile);
                unlink($chunkFilePath);
            }

            fclose($outputFile);

        }


        return response()->json(['success' => true]);
    }

    public function destroy(Request $request){
        $url = $request->input('url');
        if(Setting::where('name', 'disc_type')->value('val') == 's3'){
            $parsedUrl = parse_url($url);
            $path = ltrim($parsedUrl['path'], '/');
           // Delete the file from S3 bucket
        if (Storage::disk('s3')->delete($path)) {
            return response()->json(['success' => true]);
        }
        }else{
            $filenameWithoutExtension = basename($url);

            // Delete the file from the storage
            // Make sure to adjust the path accordingly
            $media = Media::where('file_name', $filenameWithoutExtension)->first();
            if ($media) {
                $media->delete();
            }

            $filePath = str_replace(url('/storage'), 'public', $url);
            if (Storage::delete($filePath)) {
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false], 500);
    }

}
