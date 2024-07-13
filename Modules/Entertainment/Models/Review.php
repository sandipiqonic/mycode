<?php

namespace Modules\Entertainment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Entertainment\Database\factories\ReviewFactory;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['entertainment_id', 'user_id', 'rating', 'review'];
    
    protected static function newFactory(): ReviewFactory
    {
        //return ReviewFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function entertainment()
    {
        return $this->belongsTo(Entertainment::class, 'entertainment_id');
    }
}
