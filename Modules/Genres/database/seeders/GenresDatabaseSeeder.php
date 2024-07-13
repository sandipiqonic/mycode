<?php

namespace Modules\Genres\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\Genres\Models\Genres;
use Illuminate\Support\Arr;

class GenresDatabaseSeeder extends Seeder
{

      public function run()
        {
            Schema::disableForeignKeyConstraints();
    
    
            $avatarPath = config('app.avatar_base_path');
    
            $geners = [
                [
                    'name' => 'Action',
                    'slug' => 'action',
                    'file_url' =>  public_path('/dummy-images/genre/action_genre.png'),
                    'description' => 'Action movies are packed with high-energy sequences, intense battles, and thrilling adventures. These films deliver non-stop excitement and adrenaline-pumping scenes that captivate audiences. 💥🏃‍♂️',
                    'status' => 1,
                  
                ],
                [
                    'name' => 'Animation',
                    'file_url' => public_path('/dummy-images/genre/animation_genre.png'),
                    'slug' => 'animation',
                    'description' => 'Captivating animated stories that bring imaginative worlds and characters to life. These films use creative visuals and storytelling to enchant audiences of all ages. 🎨✨',
                    'status' => 1,
                ],
    
                [
                    'name' => 'Comedy',
                    'file_url' => public_path('/dummy-images/genre/comedy_genre.png'),
                    'slug' => 'comedy',
                    'description' => 'Light-hearted films designed to entertain and amuse with humor and wit. These movies offer a delightful escape filled with laughter and joy. 😂🎬',
                    'status' => 1,
                ],
                [
                    'name' => 'History',
                    'file_url' => public_path('/dummy-images/genre/history_genre.png'),
                    'slug' => 'history',
                    'description' => 'Movies that delve into significant historical events, figures, and eras. They offer a glimpse into the past, bringing history to life with compelling narratives. 📜🏰',
                    'status' => 1,
                ],

                [
                    'name' => 'Horror',
                    'file_url' =>public_path('/dummy-images/genre/horror_genre.png'),
                    'slug' => 'horror',
                    'description' => 'Spine-chilling movies that evoke fear and suspense, often featuring supernatural elements. These films are designed to haunt and thrill viewers. 👻🕯️',
                    'status' => 1,
                ],

                [
                    'name' => 'Inspirational',
                    'file_url' => public_path('/dummy-images/genre/inspirational_genre.png'),
                    'slug' => 'inspirational',
                    'description' => 'Uplifting films that motivate and inspire with stories of courage, perseverance, and triumph. They often highlight the resilience of the human spirit. 🌟💪',
                    'status' => 1,
                ],
                [
                    'name' => 'Romantic',
                    'file_url' => public_path('/dummy-images/genre/romantic_genre.png'),
                    'slug' => 'romantic',
                    'description' => 'Heartwarming stories focusing on love, relationships, and the complexities of romance. These films explore the beauty and challenges of romantic connections. 💖🌹',
                    'status' => 1,
                ],

                [
                    'name' => 'Thriller',
                    'file_url' => public_path('/dummy-images/genre/thriller_genre.png'),
                    'slug' => 'thriller',
                    'description' => 'High-stakes scenarios and intense suspense that keep you on the edge of your seat. Expect unexpected twists and heart-pounding moments. 🔪🎬',
                    'status' => 1,
                ],
    
    
            ];
            if (env('IS_DUMMY_DATA')) {
                foreach ($geners as $key => $gener_data) {
                    $featureImage = $gener_data['file_url'] ?? null;
                    $generData = Arr::except($gener_data, ['file_url']);
                    $gener = Genres::create($generData);
                    if (isset($featureImage)) {
                        $originalUrl = $this->attachFeatureImage($gener, $featureImage);
                        if ($originalUrl) {
                            $gener->file_url = $originalUrl;
                            $gener->save();
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
