<?php

namespace Modules\Entertainment\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Episode\Models\Episode;
use Modules\Video\Models\Video;
class ContinueWatch extends Model
{


    protected $table = 'continue_watch';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['entertainment_id', 'user_id', 'entertainment_type', 'watched_time', 'total_watched_time'];


    public function entertainment()
    {
        return $this->hasOne(Entertainment::class, 'id', 'entertainment_id')->with('entertainmentGenerMappings','plan');
    }

    public function episode()
    {
        return $this->hasOne(Episode::class,'id','entertainment_id')->with('plan');
    }

    public function video()
    {
        return $this->hasOne(Video::class,'id','entertainment_id')->with('plan');
    }
}
