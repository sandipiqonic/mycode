<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Filemanager\Models\Filemanager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Batchable;

class ProcessFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $filemanager;
    public $filePath;
    public $discType;

    /**
     * Create a new job instance.
     */
    public function __construct(Filemanager $filemanager, $filePath, $discType)
    {
        $this->filemanager = $filemanager;
        $this->filePath = $filePath;
        $this->discType = $discType;
    }

    /**
     * Execute the job.
     */
    public function handle()
{
    $file = Storage::get($this->filePath);

    if ($this->discType == 's3') {
        $folderPath = 'media/'. $this->filemanager->id;
        Storage::disk('s3')->put($folderPath, $file);
    } else {
        $tempFilePath = tempnam(sys_get_temp_dir(), 'filemanager');
        file_put_contents($tempFilePath, $file);
        $fileObject = new \Illuminate\Http\UploadedFile($tempFilePath,pathinfo($this->filePath, PATHINFO_BASENAME));
        $filePath = StoreMediaFile($this->filemanager, $fileObject, 'file_url');
        if (file_exists($tempFilePath)) {
            unlink($tempFilePath);
        }
    }

    // Save the changes to $filemanager model
    $this->filemanager->save();

    // Delete the temporary file from storage
    Storage::delete($this->filePath);
}
}
