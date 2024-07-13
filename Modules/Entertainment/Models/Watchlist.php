<?php

namespace Modules\Entertainment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Entertainment\Database\factories\WatchlistFactory;

class Watchlist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['entertainment_id','user_id'];
    
    protected static function newFactory(): WatchlistFactory
    {
        //return WatchlistFactory::new();
    }
 
    public function entertainment()
    {
        return $this->hasOne(Entertainment::class, 'id', 'entertainment_id')->with('entertainmentGenerMappings','plan');
    }
}
