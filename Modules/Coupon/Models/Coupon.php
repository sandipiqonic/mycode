<?php

namespace Modules\Coupon\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'coupons';

    protected $fillable=['coupon_code', 'type', 'value', 'coupon_usage' , 'status', 'expiry_date'];
    const CUSTOM_FIELD_MODEL = 'Modules\Coupon\Models\Coupon';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */


    protected $appends = ['feature_image'];

    protected function getFeatureImageAttribute()
    {
        $media = $this->getFirstMediaUrl('feature_image');
        return isset($media) && ! empty($media) ? $media : 'https://dummyimage.com/600x300/cfcfcf/000000.png';
    }
    protected static function newFactory()
    {
        return \Modules\Coupon\database\factories\CouponFactory::new();
    }
}
