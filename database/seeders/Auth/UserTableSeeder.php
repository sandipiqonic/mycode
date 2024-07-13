<?php

namespace Database\Seeders\Auth;

use App\Events\Backend\UserCreated;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Address;
/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Add the master administrator, user id of 1
        $avatarPath = config('app.avatar_base_path');


        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@streamit.com',
                'password' => Hash::make('12345678'),
                'mobile' => '1-81859861',
                'date_of_birth' => fake()->date,
                'profile_image' => public_path('/dummy-images/profile/admin/super_admin.png'),
                'gender' => 'female',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'user_type' => 'admin',
            ],
            [
                'first_name' => 'Ivan',
                'last_name' => 'Norris',
                'email' => 'demo@streamit.com',
                'password' => Hash::make('12345678'),
                'mobile' => '1-74858414',
                'date_of_birth' => fake()->date,
                'profile_image' => public_path('/dummy-images/profile/admin/demo_admin.png'),
                'gender' => 'male',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'user_type' => 'demo_admin',
            ],


            //user

            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@streamit.com',
                'password' => Hash::make('12345678'),
                'mobile' => '1-4578952512',
                'date_of_birth' =>fake()->date, // Replacefake()->date with the actual date of birth
                'profile_image' => public_path('/dummy-images/profile/user/john.png'),
                'gender' => 'male',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'user_type' => 'user',
            ]


        ];

        if (env('IS_DUMMY_DATA')) {
            foreach ($users as $key => $user_data) {
                $featureImage = $user_data['profile_image'] ?? null;
                $userData = Arr::except($user_data, ['profile_image','address']);
                $user = User::create($userData);

                if (isset($user_data['address'])) {
                  $addresses = $user_data['address'];

                  foreach($addresses as $addressData){
                      $address = new Address($addressData);
                      $user->address()->save($address);
                  }
                }

                $user->assignRole($user_data['user_type']);


                event(new UserCreated($user));
                if (isset($featureImage)) {
                    $this->attachFeatureImage($user, $featureImage);
                }
            }
            // if (env('IS_FAKE_DATA')) {
            //   User::factory()->count(30)->create()->each(function ($user){
            //     $user->assignRole('user');
            //     $img = public_path('/dummy-images/user/customers/'.fake()->numberBetween(1,13).'.webp');
            //     $this->attachFeatureImage($user, $img);
            //   });
            // }
        }

          Schema::enableForeignKeyConstraints();
      }

      private function attachFeatureImage($model, $publicPath)
      {
          if(!env('IS_DUMMY_DATA_IMAGE')) return false;

          $file = new \Illuminate\Http\File($publicPath);

          $media = $model->addMedia($file)->preservingOriginal()->toMediaCollection('profile_image');

          return $media;
      }
}
