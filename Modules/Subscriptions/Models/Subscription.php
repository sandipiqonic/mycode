<?php

namespace Modules\Subscriptions\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Subscriptions\Models\SubscriptionTransactions;

class Subscription extends BaseModel
{
    use HasFactory;

    protected $fillable = ['plan_id',
        'user_id',
        'device_id',
        'start_date',
        'end_date',
        'status',
        'amount',
        'name',
        'identifier',
        'type',
        'duration',
        'level',
        'plan_type',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subscription_transaction()
    {
        return $this->hasOne(SubscriptionTransactions::class, 'subscriptions_id', 'id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

    protected static function newFactory()
    {
        return \Modules\Subscriptions\Database\factories\SubscriptionFactory::new();
    }
}
