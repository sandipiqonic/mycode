<?php

namespace Modules\CastCrew\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
use Modules\CastCrew\Models\CastCrew;

class CastCrewDatabaseSeeder extends Seeder

{

      public function run()
        {
            Schema::disableForeignKeyConstraints();
    
    
            $avatarPath = config('app.avatar_base_path');
    
            $castAndCrew = [
                [
                    'name' => 'Michael Johnson',
                    'type' => 'actor', 
                    'file_url' => public_path('/dummy-images/castcrew/michael_johnson.png'),
                    'bio' => 'Versatile actor known for his dynamic roles in action and drama films. 🎬',
                    'place_of_birth' => 'New York, USA',
                    'dob' => '1985-04-13',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'James Williams',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/james_williams.png'),
                    'bio' => 'Acclaimed actor with a knack for bringing complex characters to life. 🎭',
                    'place_of_birth' => 'Los Angeles, USA',
                    'dob' => '1980-04-14',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Robert Brown',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/robert_brown.png'),
                    'bio' => 'Renowned actor famed for his powerful performances in thrillers. 🔪',
                    'place_of_birth' => 'Chicago, USA',
                    'dob' => '1990-02-07',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'David Jones',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/david_jones.png'),
                    'bio' => 'Award-winning actor known for his captivating roles in historical dramas. 📜',
                    'place_of_birth' => 'London, UK',
                    'dob' => '1985-08-04',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'John Miller',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/john_miller.png'),
                    'bio' => 'Charismatic actor celebrated for his comedic timing and charm. 😂',
                    'place_of_birth' => 'Toronto, Canada',
                    'dob' => '1982-12-09',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Daniel Anderson',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/daniel_anderson.png'),
                    'bio' => 'Talented actor known for his intense and compelling performances in horror films. 👻',
                    'place_of_birth' => 'Sydney, Australia',
                    'dob' => '1985-04-18',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Matthew Clark',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/matthew_clark.png'),
                    'bio' => 'Dynamic actor recognized for his roles in inspirational and motivational films. 🌟',
                    'place_of_birth' => 'Dublin, Ireland',
                    'dob' => '1985-04-19',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Andrew Martinez',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/andrew_martinez.png'),
                    'bio' => 'Acclaimed actor with a strong presence in romantic films. 💖',
                    'place_of_birth' => 'Madrid, Spain',
                    'dob' => '1985-04-20',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Joshua Rodriguez',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/joshua_rodriguez.png'),
                    'bio' => 'Renowned for his action-packed roles and high-energy performances. 💥',
                    'place_of_birth' => 'Mexico City, Mexico',
                    'dob' => '1985-04-21',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Christopher Lopez',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/christopher_lopez.png'),
                    'bio' => 'Versatile actor known for his roles in both comedy and drama. 🎭',
                    'place_of_birth' => 'Buenos Aires, Argentina',
                    'dob' => '1985-04-22',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Emily Johnson',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/emily_johnson.png'),
                    'bio' => 'Talented actress known for her captivating performances in dramas. 🎬',
                    'place_of_birth' => 'New York, USA',
                    'dob' => '1985-04-23',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Laura Turner',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/laura_turner.png'),
                    'bio' => 'Renowned actress with a flair for bringing historical characters to life. 📜',
                    'place_of_birth' => 'Los Angeles, USA',
                    'dob' => '1985-04-24',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Olivia Martinez',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/olivia_martinez.png'),
                    'bio' => 'Acclaimed actress known for her dynamic roles in romantic films. 💖',
                    'place_of_birth' => 'Madrid, Spain',
                    'dob' => '1985-04-25',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Isabella Brown',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/isabella_brown.png'),
                    'bio' => 'Versatile actress with a talent for both comedy and drama. 😂',
                    'place_of_birth' => 'London, UK',
                    'dob' => '1985-04-26',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Lily Clark',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/lily_clark.png'),
                    'bio' => 'Celebrated actress known for her intense performances in thrillers. 🔪',
                    'place_of_birth' => 'Toronto, Canada',
                    'dob' => '1985-04-27',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Charlotte Garcia',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/charlotte_garcia.png'),
                    'bio' => 'Acclaimed actress renowned for her roles in horror films. 👻',
                    'place_of_birth' => 'Sydney, Australia',
                    'dob' => '1985-04-28',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Amelia Martinez',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/amelia_martinez.png'),
                    'bio' => 'Dynamic actress recognized for her roles in inspirational movies. 🌟',
                    'place_of_birth' => 'Mexico City, Mexico',
                    'dob' => '1985-04-29',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Jessica Adams',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/jessica_adams.png'),
                    'bio' => 'Talented actress known for her compelling performances in action films. 💥',
                    'place_of_birth' => 'Dublin, Ireland',
                    'dob' => '1985-04-30',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Megan Collins',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/megan_collins.png'),
                    'bio' => 'Versatile actress known for her roles in both romantic and drama films. 💖',
                    'place_of_birth' => 'Seoul, South Korea',
                    'dob' => '1985-05-01',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Grace Taylor',
                    'type' => 'actor',
                    'file_url' => public_path('/dummy-images/castcrew/grace_taylor.png'),
                    'bio' => 'Acclaimed actress celebrated for her performances in historical dramas. 📜',
                    'place_of_birth' => 'Cape Town, South Africa',
                    'dob' => '1985-05-02',
                    'designation' => 'Actor',
                ],
                [
                    'name' => 'Thomas Smith',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/thomas_smith.png'),
                    'bio' => 'Visionary director known for his innovative storytelling and cinematic techniques. 🎬',
                    'place_of_birth' => 'New York, USA',
                    'dob' => '1985-04-13',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'William Johnson',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/william_johnson.png'),
                    'bio' => 'Acclaimed director with a flair for intense action sequences. 💥',
                    'place_of_birth' => 'Los Angeles, USA',
                    'dob' => '1980-04-14',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Henry Taylor',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/henry_taylor.png'),
                    'bio' => 'Renowned director known for his compelling historical dramas. 📜',
                    'place_of_birth' => 'Chicago, USA',
                    'dob' => '1990-02-07',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Charles Wilson',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/charles_wilson.png'),
                    'bio' => 'Award-winning director famous for his work in horror films. 👻',
                    'place_of_birth' => 'London, UK',
                    'dob' => '1985-08-04',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'George Harris',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/george_harris.png'),
                    'bio' => 'Innovative director known for his unique approach to comedy. 😂',
                    'place_of_birth' => 'Toronto, Canada',
                    'dob' => '1982-12-09',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Anthony Clark',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/anthony_clark.png'),
                    'bio' => 'Talented director celebrated for his inspirational and motivational films. 🌟',
                    'place_of_birth' => 'Sydney, Australia',
                    'dob' => '1985-04-18',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Edward Lewis',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/edward_lewis.png'),
                    'bio' => 'Dynamic director recognized for his work in romantic films. 💖',
                    'place_of_birth' => 'Dublin, Ireland',
                    'dob' => '1985-04-19',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Daniel Walker',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/daniel_walker.png'),
                    'bio' => 'Acclaimed director known for his thrilling and suspenseful films. 🔪',
                    'place_of_birth' => 'Madrid, Spain',
                    'dob' => '1985-04-20',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Matthew Collins',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/matthew_collins.png'),
                    'bio' => 'Renowned for his action-packed films and high-energy direction. 🎥',
                    'place_of_birth' => 'Mexico City, Mexico',
                    'dob' => '1985-04-21',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Richard King',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/richard_king.png'),
                    'bio' => 'Celebrated director known for his masterful storytelling in drama. 🎭',
                    'place_of_birth' => 'Buenos Aires, Argentina',
                    'dob' => '1985-04-22',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Sophia Williams',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/sophia_williams.png'),
                    'bio' => 'Acclaimed director known for her profound and emotional storytelling. 🎬',
                    'place_of_birth' => 'New York, USA',
                    'dob' => '1985-04-23',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Emma Thompson',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/emma_thompson.png'),
                    'bio' => 'Visionary director celebrated for her work in romantic films. 💖',
                    'place_of_birth' => 'Los Angeles, USA',
                    'dob' => '1985-04-24',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Abigail Thompson',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/abigail_thompson.png'),
                    'bio' => 'Renowned director known for her historical dramas and biopics. 📜',
                    'place_of_birth' => 'Madrid, Spain',
                    'dob' => '1985-04-25',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Natalie Parker',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/natalie_parker.png'),
                    'bio' => 'Award-winning director famous for her suspenseful thrillers. 🔪',
                    'place_of_birth' => 'London, UK',
                    'dob' => '1985-04-26',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Mili Davis',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/mili_davis.png'),
                    'bio' => 'Talented director known for her innovative approach to comedy. 😂',
                    'place_of_birth' => 'Toronto, Canada',
                    'dob' => '1985-04-27',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Chloe Mitchell',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/chloe_mitchell.png'),
                    'bio' => 'Dynamic director recognized for her powerful horror films. 👻',
                    'place_of_birth' => 'Sydney, Australia',
                    'dob' => '1985-04-28',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Sarah Foster',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/sarah_foster.png'),
                    'bio' => 'Acclaimed director known for her inspirational and motivational films. 🌟',
                    'place_of_birth' => 'Mexico City, Mexico',
                    'dob' => '1985-04-29',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Victoria Evans',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/victoria_evans.png'),
                    'bio' => 'Visionary director celebrated for her thrilling and suspenseful films. 🔪',
                    'place_of_birth' => 'London, UK',
                    'dob' => '1985-04-30',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Ava Brown',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/ava_brown.png'),
                    'bio' => 'Renowned for her action-packed and high-energy films. 💥',
                    'place_of_birth' => 'Toronto, Canada',
                    'dob' => '1985-05-01',
                    'designation' => 'Director',
                ],
                [
                    'name' => 'Sophia Lee',
                    'type' => 'director',
                    'file_url' => public_path('/dummy-images/castcrew/sophia_lee.png'),
                    'bio' => 'Celebrated director known for her compelling drama films. 🎭',
                    'place_of_birth' => 'Sydney, Australia',
                    'dob' => '1985-05-02',
                    'designation' => 'Director',
                ],
                
            ];
            if (env('IS_DUMMY_DATA')) {
                foreach ($castAndCrew as $key => $castcrew_data) {
                    $featureImage = $gener_data['file_url'] ?? null;
                    $castcrewData = Arr::except($castcrew_data, ['file_url']);
                    $castcrew = CastCrew::create($castcrewData);
                    if (isset($featureImage)) {
                        $originalUrl = $this->attachFeatureImage($castcrew, $featureImage);
                        if ($originalUrl) {
                            $castcrew->file_url = $originalUrl;
                            $castcrew->save();
                        }
                    }
                }
            
                Schema::enableForeignKeyConstraints();
            }

        }
    
     
    private function attachFeatureImage($model, $publicPath)
    {
        if (!env('IS_DUMMY_DATA_IMAGE')) return false;
    
        $file = new \Illuminate\Http\File($publicPath);
    
        $media = $model->addMedia($file)->preservingOriginal()->toMediaCollection('file_url');
    
        return $media->getUrl(); 
    }
    
}
