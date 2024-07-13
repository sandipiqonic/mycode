<?php

namespace Modules\Season\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Modules\Season\Models\Season;

class SeasonDatabaseSeeder extends Seeder
{
    
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $seasons = [
            [
                'name' => 'S1 The Awakening Shadows',
                'entertainment_id' => 1,
                'poster_url' => public_path('/dummy-images/season/the_smiling_shadows_thumb.png'),
                'short_desc' => 'The team battles an ancient evil that awakens from the shadows. 🌒',
                'description' => 'The team encounters a series of mysterious events that awaken an ancient evil. Their battle to understand and confront this malevolent force begins. 🏚️👻',
                'trailer_url' => 'https://youtu.be/mBYGUn6Q7tQ?si=2ijlo4497ab-ZMpU',
                'trailer_url_type' => 'YouTube',
                'access' => 'free',
                'status' => 1,
            ],
            [
                'name' => 'S2 The Rising Shadows',
                'entertainment_id' => 1,
                'poster_url' => public_path('/dummy-images/season/the_smiling_shadows_thumb.png'),
                'short_desc' => 'Darkness intensifies as the ancient evil returns, stronger than before.',
                'description' => 'As the ancient evil rises again, the team faces even darker and more powerful threats. They must confront their deepest fears to save humanity from eternal darkness. 🌑🛡️',
                'trailer_url' => 'https://youtu.be/_U7wKRtf8C4?si=nGKAxMOgs9YDMEPq',
                'trailer_url_type' => 'YouTube',
                'access' => 'free',
                'status' => 1,
            ],
        ];

        if (env('IS_DUMMY_DATA')) {
            foreach ($seasons as $seasonData) {
                $posterPath = $seasonData['poster_url'] ?? null;
                
                $season = Season::create(Arr::except($seasonData, ['poster_url']));

                if (isset($posterPath)) {
                    $posterUrl = $this->attachImage($season, $posterPath, 'poster_url');
                    if ($posterUrl) {
                        $season->poster_url = $posterUrl;
                    }
                }

                $season->save();
             
            }

            Schema::enableForeignKeyConstraints();
        }
    }

    private function attachImage($model, $publicPath)
    {
        if (!env('IS_DUMMY_DATA_IMAGE')) return false;

        $file = new \Illuminate\Http\File($publicPath);

        $media = $model->addMedia($file)->preservingOriginal()->toMediaCollection('poster_url');

        return $media->getUrl();
    }
}
