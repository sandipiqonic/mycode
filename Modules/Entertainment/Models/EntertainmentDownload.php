<?php

namespace Modules\Entertainment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Entertainment\Database\factories\EntertainmentDownloadFactory;

class EntertainmentDownload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['entertainment_id', 'user_id', 'entertainment_type', 'is_download', 'type', 'quality', 'url'];
    
    protected static function newFactory(): EntertainmentDownloadFactory
    {
        //return EntertainmentDownloadFactory::new();
    }
}
