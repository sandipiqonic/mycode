<?php

namespace Modules\Entertainment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Entertainment\Database\factories\UserReminderFactory;

class UserReminder extends Model
{
    use HasFactory;

    protected $table = 'user_reminder';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['entertainment_id','user_id','release_date','is_remind'];
    
    protected static function newFactory(): UserReminderFactory
    {
        //return UserReminderFactory::new();
    }

    public function entertainment()
    {
        return $this->hasOne(Entertainment::class, 'id', 'entertainment_id')->with('entertainmentGenerMappings','plan');
    }
}
