<?php

namespace Modules\Constant\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Constant\Models\Constant;
use Illuminate\Support\Facades\DB;

class ConstantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * Constants Seed
         * ------------------
         */
        // Enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $arr1 = [
            [
                'type' => 'PAYMENT_METHODS',
                'name' => 'cash',
                'value' => 'Cash',
            ],
            [
                'type' => 'PAYMENT_METHODS',
                'name' => 'upi',
                'value' => 'UPI',
            ],
            [
                'type' => 'PAYMENT_METHODS',
                'name' => 'razorpay',
                'value' => 'Razorpay',
            ],
            [
                'type' => 'PAYMENT_METHODS',
                'name' => 'stripe',
                'value' => 'Stripe',
            ],
            [
                'type' => 'GENDER',
                'name' => 'unisex',
                'value' => 'Unisex',
            ],
            [
                'type' => 'GENDER',
                'name' => 'female',
                'value' => 'Female',
            ],
            [
                'type' => 'GENDER',
                'name' => 'male',
                'value' => 'Male',
            ],

            [
                'type' => 'status',
                'name' => 1,
                'value' => 'Active',
            ],
            [
                'type' => 'status',
                'name' => 0,
                'value' => 'Deactive',
            ],
            
            [
                'type' => 'language',
                'value' => 'en',
                'name' => 'English',
                'sequence' => 1,
            ],
            [
                'type' => 'language',
                'value' => 'br',
                'name' => 'বাংলা',
                'sequence' => 2,
            ],
            [
                'type' => 'language',
                'value' => 'ar',
                'name' => 'العربی',
                'sequence' => 3,
            ],
            [
                'type' => 'language',
                'value' => 'vi',
                'name' => 'Vietnamese',
                'sequence' => 4,
            ],
            [
                'type' => 'SLIDER_TYPES',
                'value' => 'category',
                'name' => 'Category',
                'sequence' => 1,
            ],
            [
                'type' => 'SLIDER_TYPES',
                'value' => 'service',
                'name' => 'Service',
                'sequence' => 2,
            ],

            [
                'type' => 'EARNING_PAYMENT_TYPE',
                'value' => 'cash',
                'name' => 'Cash',
                'sequence' => 1,
            ],

            [
                'type' => 'EARNING_PAYMENT_TYPE',
                'value' => 'bank',
                'name' => 'Bank',
                'sequence' => 2,
            ],

            [
                'type' => 'EARNING_PAYMENT_TYPE',
                'value' => 'wallet',
                'name' => 'Wallet',
                'sequence' => 3,
            ],
          
            [
                'type' => 'PAYMENT_STATUS',
                'name' => 'Paid',
                'value' => '1',
            ],

            [
                'type' => 'PAYMENT_STATUS',
                'name' => 'Pending',
                'value' => '0',
            ],

            [
                'type' => 'PAYMENT_STATUS',
                'name' => 'Pending',
                'value' => '0',
            ],


            [
                'type' => 'upload_type',
                'name' => 'Local',
                'value' => 'Local',
            ],
            [
                'type' => 'upload_type',
                'name' => 'URL',
                'value' => 'URL',
            ],
            [
                'type' => 'upload_type',
                'name' => 'YouTube',
                'value' => 'YouTube',
            ],
            [
                'type' => 'upload_type',
                'name' => 'HLS',
                'value' => 'HLS',
            ],
            [
                'type' => 'upload_type',
                'name' => 'Vimeo',
                'value' => 'Vimeo',
            ],

            [
                'type' => 'movie_language',
                'name' => 'English',
                'value' => 'english',
            ],

            [
                'type' => 'movie_language',
                'name' => 'Hindi',
                'value' => 'hindi',
            ],

            [
                'type' => 'movie_language',
                'name' => 'Tamil',
                'value' => 'tamil',
            ],


            [
                'type' => 'movie_language',
                'name' => 'Telgu',
                'value' => 'telgu',
            ],


            [
                'type' => 'video_quality',
                'name' => '480p',
                'value' => '480p',
            ],

            [
                'type' => 'video_quality',
                'name' => '720p',
                'value' => '720p',
            ],

            [
                'type' => 'video_quality',
                'name' => '1080p',
                'value' => '1080p',
            ],
            [
                'type' => 'video_quality',
                'name' => '1440p',
                'value' => '1440p',
            ],
            [
                'type' => 'video_quality',
                'name' => '2K',
                'value' => '2K',
            ],
            [
                'type' => 'video_quality',
                'name' => '4K',
                'value' => '4K',
            ],
            [
                'type' => 'video_quality',
                'name' => '8K',
                'value' => '8K',
            ],
            [
                'type' => 'STREAM_TYPE',
                'name' => 'URL',
                'value' => 'URL',
            ],
            [
                'type' => 'STREAM_TYPE',
                'name' => 'YouTube',
                'value' => 'YouTube',
            ],
            [
                'type' => 'STREAM_TYPE',
                'name' => 'HLS',
                'value' => 'HLS',
            ],
            [
                'type' => 'STREAM_TYPE',
                'name' => 'Vimeo',
                'value' => 'Vimeo',
            ],
            [
                'type' => 'STREAM_TYPE',
                'name' => 'Embedded',
                'value' => 'Embedded',
            ],
            
        ];

        foreach ($arr1 as $key => $val) {
            Constant::create($val);
        }
    }
}
