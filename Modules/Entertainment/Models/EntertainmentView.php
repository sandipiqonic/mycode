<?php

namespace Modules\Entertainment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Entertainment\Database\factories\EntertainmentViewFactory;

class EntertainmentView extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['entertainment_id', 'user_id'];
    
    protected static function newFactory(): EntertainmentViewFactory
    {
        //return EntertainmentViewFactory::new();
    }
    public function entertainment()
    {
        return $this->belongsTo(Entertainment::class, 'entertainment_id');
    }
   
}
