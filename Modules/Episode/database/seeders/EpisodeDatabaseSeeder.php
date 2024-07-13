<?php

namespace Modules\Episode\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Modules\Episode\Models\Episode;
use Modules\MenuBuilder\Models\MenuBuilder;

class EpisodeDatabaseSeeder extends Seeder
{
   
    public function run()
        {
            Schema::disableForeignKeyConstraints();

            $episodes = [
                [
                    'name' => 'S1 E1 The Awakening',
                    'poster_url' => public_path('/dummy-images/episode/s1_e1_the_awakening_thumb.png'),
                    'entertainment_id' => 1,
                    'season_id' => 1,
                    'short_desc' => 'A series of mysterious events awaken an ancient evil. 😱',
                    'description' => 'The team must uncover the truth behind these occurrences before it\'s too late. A series of mysterious events awaken an ancient evil. 😱',
                    'trailer_url' => 'https://www.youtube.com/watch?v=mBYGUn6Q7tQ&t=17s',
                    'trailer_url_type' => 'YouTube',
                    'access' => 'free',
                    'status' => 1,
                    'duration' => '02:56',
                    'is_restricted' => false,
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/mBYGUn6Q7tQ?si=2ijlo4497ab-ZMpU',
                ],
                [
                    'name' => 'S1 E2 The Haunted',
                    'poster_url' => public_path('/dummy-images/episode/s1_e2_the_haunted_thumb.png'),
                    'entertainment_id' => 1,
                    'season_id' => 1,
                    'short_desc' => 'The team discovers a haunted house with a dark secret. 🏚️',
                    'description' => 'As they explore, they encounter terrifying apparitions and uncover the house\'s grim history. The team discovers a haunted house with a dark secret. 🏚️',
                    'trailer_url' => 'https://www.youtube.com/watch?v=mBYGUn6Q7tQ&t=17s',
                    'trailer_url_type' => 'YouTube',
                    'access' => 'free',
                    'status' => 1,
                    'duration' => '05:13',
                    'is_restricted' => false,
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/oZDzZNm4k6M?si=Hv33WihW-44xFvkm',
                    
                ],
                [
                    'name' => 'S1 E3 The Possession',
                    'poster_url' => public_path('/dummy-images/episode/s1_e3_the_possession_thumb.png'),
                    'entertainment_id' => 1,
                    'season_id' => 1,
                    'short_desc' => 'One of the investigators becomes possessed by a malevolent spirit. 👻',
                    'description' => 'The team struggles to save their friend and find a way to expel the dark force. One of the investigators becomes possessed by a malevolent spirit. 👻',
                    'trailer_url' => 'https://www.youtube.com/watch?v=mBYGUn6Q7tQ&t=17s',
                    'trailer_url_type' => 'YouTube',
                    'access' => 'free',
                    'status' => 1,
                    'duration' => '02:09',
                    'is_restricted' => false,
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/ng9BW-vQ_2k?si=z-uDACI2iFie0-HY',
                ],
                [
                    'name' => 'S1 E4 The Ritual',
                    'poster_url' => public_path('/dummy-images/episode/s1_e4_the_ritual_thumb.png'),
                    'entertainment_id' => 1,
                    'season_id' => 1,
                    'short_desc' => 'The team uncovers a ritual that could banish the evil entity. 🕯️',
                    'description' => 'The team uncovers a ritual that could banish the evil entity. They must perform it correctly amidst mounting supernatural threats. 🕯️',
                    'trailer_url' => 'https://www.youtube.com/watch?v=mBYGUn6Q7tQ&t=17s',
                    'trailer_url_type' => 'YouTube',
                    'access' => 'free',
                    'status' => 1,
                    'duration' => '03:20',
                    'is_restricted' => false,
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/M6EMl7HPw6M?si=b5GV20LyRV6xd5Cn',
                ],
                [
                    'name' => 'S1 E5 The Final Confrontation',
                    'poster_url' => public_path('/dummy-images/episode/s1_e5_the_final_confrontation_thumb.png'),
                    'entertainment_id' => 1,
                    'season_id' => 1,
                    'short_desc' => 'A climactic battle to defeat the ancient evil once and for all. ⚔️',
                    'description' => 'A climactic battle to defeat the ancient evil once and for all. The team faces their greatest challenge yet, risking everything to save humanity. ⚔️',
                    'trailer_url' => 'https://www.youtube.com/watch?v=mBYGUn6Q7tQ&t=17s',
                    'trailer_url_type' => 'YouTube',
                    'access' => 'free',
                    'status' => 1,
                    'duration' => '03:28',
                    'is_restricted' => false,
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/WHBOBgDTLak?si=zrUg1McYVIr9POWc',
                ],
                // [
                //     'name' => 'S2 E1 The Return',
                //     'poster_url' => 's2_e1_the_return_thumb.png',
                //     'entertainment_id' => 1,
                //     'season_id' => 2,
                //     'short_desc' => 'The ancient evil returns, more powerful than ever. 🔥',
                //     'description' => 'The ancient evil returns, more powerful than ever. The team must regroup and devise a new plan to confront this formidable foe. 🔥',
                //     'trailer_url' => 'https://youtu.be/_U7wKRtf8C4?si=nGKAxMOgs9YDMEPq',
                //     'trailer_url_type' => 'YouTube',
                //     'access' => 'free',
                //     'plan_id' => null,
                //     'status' => 1,
                //     'IMDb_rating' => null,
                //     'content_rating' => null,
                //     'duration' => '02:43',
                //     'release_date' => null,
                //     'is_restricted' => false,
                //     'video_upload_type' => 'YouTube',
                //     'video_url_input' => 'https://youtu.be/_U7wKRtf8C4?si=nGKAxMOgs9YDMEPq',
                //     'download_status' => false,
                //     'download_type' => null,
                //     'download_url' => null,
                //     'enable_download_quality' => false,
                //     'enable_quality' => false,
                // ],
                // [
                //     'name' => 'S2 E2 The Darkness Within',
                //     'poster_url' => 's2_e2_the_darkness_within_thumb.png',
                //     'entertainment_id' => 1,
                //     'season_id' => 2,
                //     'short_desc' => 'The team faces their darkest fears and inner demons. 🌑',
                //     'description' => 'The team faces their darkest fears and inner demons. As they battle the rising shadows, personal struggles threaten to tear them apart. 🌑',
                //     'trailer_url' => 'https://youtu.be/_U7wKRtf8C4?si=nGKAxMOgs9YDMEPq',
                //     'trailer_url_type' => 'YouTube',
                //     'access' => 'free',
                //     'plan_id' => null,
                //     'status' => 1,
                //     'IMDb_rating' => null,
                //     'content_rating' => null,
                //     'duration' => '04:08',
                //     'release_date' => null,
                //     'is_restricted' => false,
                //     'video_upload_type' => 'YouTube',
                //     'video_url_input' => 'https://youtu.be/1sCBEzxF_K4?si=B-rZUby9EXaMWkKD',
                //     'download_status' => false,
                //     'download_type' => null,
                //     'download_url' => null,
                //     'enable_download_quality' => false,
                //     'enable_quality' => false,
                // ],
                // [
                //     'name' => 'S2 E3 The Last Stand',
                //     'poster_url' => 's2_e3_the_last_stand_thumb.png',
                //     'entertainment_id' => 1,
                //     'season_id' => 2,
                //     'short_desc' => 'A desperate struggle to save humanity from eternal darkness. 🛡️',
                //     'description' => 'A desperate struggle to save humanity from eternal darkness. The team unites for a final stand, determined to vanquish the evil once and for all. 🛡️',
                //     'trailer_url' => 'https://youtu.be/_U7wKRtf8C4?si=nGKAxMOgs9YDMEPq',
                //     'trailer_url_type' => 'YouTube',
                //     'access' => 'free',
                //     'plan_id' => null,
                //     'status' => 1,
                //     'IMDb_rating' => null,
                //     'content_rating' => null,
                //     'duration' => '03:53',
                //     'release_date' => null,
                //     'is_restricted' => false,
                //     'video_upload_type' => 'YouTube',
                //     'video_url_input' => 'https://youtu.be/7_MJp5AbSwA?si=Mtx9h0wlxtn4o_2Q',
                //     'download_status' => false,
                //     'download_type' => null,
                //     'download_url' => null,
                //     'enable_download_quality' => false,
                //     'enable_quality' => false,
                // ],
            ];
            
            if (env('IS_DUMMY_DATA')) {
                foreach ($episodes as $episodesData) {
                    $posterPath = $episodesData['poster_url'] ?? null;
                    
                    $episode = Episode::create(Arr::except($episodesData, ['poster_url']));
    
                    if (isset($posterPath)) {
                        $posterUrl = $this->attachImage($episode, $posterPath, 'poster_url');
                        if ($posterUrl) {
                            $episode->poster_url = $posterUrl;
                        }
                    }
    
                    $episode->save();
                 
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