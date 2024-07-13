<?php

namespace Modules\Entertainment\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Models\EntertainmentTalentMapping;
use Modules\Entertainment\Models\EntertainmentGenerMapping;
use Illuminate\Support\Facades\Schema;
use Modules\Entertainment\Models\EntertainmentStreamContentMapping;

class EntertainmentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $entertainments = [
            [
                'name' => 'The Smiling Shadows',
                'thumbnail_url' => public_path('/dummy-images/entertainment/tvshow/the_smiling_shadows_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/tvshow/the_smiling_shadows_poster.png'),
                'description' => 'A chilling tale where sinister smiles hide dark secrets and haunting mysteries. 😱👻',
                'trailer_url_type' => 'YouTube',
                'type' => 'tvshow',
                'trailer_url' => 'https://www.youtube.com/watch?v=mBYGUn6Q7tQ&t=17s',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'IMDb_rating' => 7.5,
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '07:20',
                'release_date' => '2024-04-23',
                'is_restricted' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'genre_id' => [5],
                'actors' => [11, 13],
                'directors' => [31],
            ],

            [
                'name' => 'Operation Viper',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/operation_viper_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/operation_viper_poster.png'),
                'description' => 'A covert team embarks on a high-stakes mission to thwart a dangerous terrorist organization. With explosive action and intense suspense, the team must navigate deadly traps and uncover hidden secrets to save the world from imminent catastrophe. 💥🔍',
                'trailer_url_type' => 'YouTube',
                'type' => 'movie',
                'trailer_url' => 'https://youtu.be/5zSPGLoN9lQ?si=vIAtP5OnEQ4gnMV8',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '03:00',
                'release_date' => '2010-04-23',
                'is_restricted' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'genre_id' => [1],
                'actors' => [1, 2],
                'directors' => [21, 31],
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/5zSPGLoN9lQ?si=dXOMNNUjwOW8Krc9',
                'enable_quality' => 1,
                'stream_content' => [
                    [
                        'quality' => '480p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/5zSPGLoN9lQ?si=-BRLpMNIEJrnKm6f',
                    ],
                    [
                        'quality' => '720p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/5zSPGLoN9lQ?si=sygr-NcCZcS00O0p',
                    ],
                    [
                        'quality' => '1080p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/5zSPGLoN9lQ?si=eckyQwNdCsW6Pao6',
                    ],
                    [
                        'quality' => '2K',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/5zSPGLoN9lQ?si=eckyQwNdCsW6Pao6',
                    ],
                ],
            ],
            [
                'name' => 'The Cure: Final Redemption',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/the_cure_final_redemption_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/the_cure_final_redemption_poster.png'),
                'description' => 'A team of elite agents races against time to uncover a deadly conspiracy, where the cure for a global pandemic holds the key to humanity\'s survival. As they navigate through treacherous terrain and face formidable foes, they must decide who to trust and how far they are willing to go to secure The Cure. 🌍💉',
                'trailer_url_type'=> 'YouTube',
                'type'=>'movie',
                'trailer_url' => 'https://youtu.be/eg8HPCKz6Rk?si=xDPYMcHMHIuLaJJn',
                'movie_access' => 'paid',
                'status' => 1,
                'language' => 'english',
                'content_rating' => 'PG-13',
                'duration' => '02:45',
                'release_date' => '2011-01-12',
                'is_restricted' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'genre_id' => [1],  
                'actors' => [3, 18, 19], 
                'directors' => [22, 32],
                'video_upload_type'=> 'YouTube',
                'video_url_input'=>'https://youtu.be/PdxPlbKFkaM?si=H_nwgJKc4Ioqj1tR',
                'enable_quality' => 1,
                'stream_content' => [
                    [
                        'quality' => '480p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/PdxPlbKFkaM?si=NydEmXECOvT1blJL',
                    ],
                    [
                        'quality' => '720p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/PdxPlbKFkaM?si=zaa1bCmFWRbSxZEB',
                    ],
                    [
                        'quality' => '1080p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/PdxPlbKFkaM?si=zlHHbalMgDJWz9Tp',
                    ],
                    [
                        'quality' => '2K',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/PdxPlbKFkaM?si=qaZ1H82OVU3sVx0V',
                    ],
                ],  
            ],
            [
                'name' => 'The Gunfighter\'s Redemption',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/the_gunfighters_redemption_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/the_gunfighters_redemption_poster.png'),
                'description' => 'A retired gunslinger is forced back into action when his peaceful life is shattered by ruthless outlaws. He must confront his past and fight for justice one last time. 🤠🔫',
                'trailer_url_type'=> 'YouTube',
                'type'=>'movie',
                'trailer_url' => 'https://youtu.be/ob8iKn-gLFI?si=sUpU_bMpIDocTFZ6',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:30',
                'release_date' => '2012-03-08',
                'is_restricted' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'genre_id' => [1], 
                'actors' => [13, 14],  
                'directors' => [33, 34],
                'video_upload_type'=> 'YouTube',
                'video_url_input'=>'https://youtu.be/TXfltmzRG-g?si=l0bYn4q-22XCmsJy'    
            ],
            [
                'name' => 'Daizy\'s Enchanted Journey',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/daizys_enchanted_journey_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/daizys_enchanted_journey_poster.png'),
                'description' => 'Join Daizy on an enchanting adventure as she discovers the world beyond her tower. With her magical hair and unwavering spirit, she teams up with a charming rogue and a cast of quirky characters to uncover the secrets of her past and embrace her destiny. This heartwarming tale of bravery, friendship, and self-discovery will captivate audiences of all ages. 🌟🧚‍♀️',
                'trailer_url_type'=> 'YouTube',
                'type'=>'movie',
                'trailer_url' => 'https://youtu.be/1c3O3-sVFC0?si=p7TI-JkU7E5LB5HJ',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'content_rating' => 'PG (Parental Guidance Suggested)',
                'duration' => '03:06',
                'release_date' => '2014-07-06',
                'is_restricted' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'genre_id' => [2],  
                'actors' => [15, 6],  
                'directors' => [30, 31],
                'video_upload_type'=> 'YouTube',
                'video_url_input'=>'https://youtu.be/aGjRYObE5Vw?si=7Q99VNk-J4Q5yWAC'    
            ],
            [
                'name' => 'Secrets of Zambezia',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/secrets_of_zambezia_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/secrets_of_zambezia_poster.png'),
                'description' => 'In Secrets of Zambezia, a spirited young falcon named Kai leaves his remote outpost to explore Zambezia, a majestic city hidden in the heart of Africa. Here, he discovers a vibrant community of birds from all over the world, each with their own unique talents and traditions. As Kai learns about friendship, courage, and the importance of diversity, he uncovers ancient secrets that could change Zambezia forever. Filled with stunning animation and heartwarming moments, this adventure will enchant audiences of all ages. 🌍🦅',
                'trailer_url_type'=> 'YouTube',
                'type'=>'movie',
                'trailer_url' => 'https://youtu.be/gxslnpqFwOs?si=fRi0Wnbd4qUCw43i',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'content_rating' => 'PG (Parental Guidance Suggested)',
                'duration' => '02:15',
                'release_date' => '2016-06-04',
                'is_restricted' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'genre_id' => [2], 
                'actors' => [6, 7, 16],  
                'directors' => [31],
                'video_upload_type'=> 'YouTube',
                'video_url_input'=>'https://youtu.be/qWCKqFWPjAY?si=a2c6hq4yTIKlYFJ4'    
            ],
            [
                'name' => 'The New Empire',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/the_new_empire_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/the_new_empire_poster.png'),
                'description' => 'The New Empire brings an exhilarating clash of the titans, where Godzilla and Kong must join forces to defend their realm against an unprecedented threat. In this animated adventure, the balance of power shifts as a new, formidable adversary emerges, threatening to plunge their world into chaos. With stunning visuals and heart-pounding action, "The New Empire" explores themes of unity, courage, and the primal struggle for survival. Join Godzilla and Kong on a journey filled with breathtaking battles, unexpected alliances, and the ultimate test of strength and resilience. 🌍🦖🦍',
                'trailer_url_type'=> 'YouTube',
                'type'=>'movie',
                'trailer_url' => 'https://youtu.be/jSGlwLzjHpI?si=le4MKsQdLAyXjTpm',
                'movie_access' => 'paid',
                'status' => 1,
                'language' => 'english',
                'content_rating' => 'PG (Parental Guidance Suggested)',
                'duration' => '03:56',
                'release_date' => '2017-08-14',
                'is_restricted' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'genre_id' => [2],  
                'actors' => [15, 7, 16],  
                'directors' => [35,29,30],  
                'video_upload_type'=> 'YouTube',
                'video_url_input'=>'https://youtu.be/hlKFxyxOWIQ?si=Avo5stG4_o4UiUOe',
                'enable_quality' => 1,
                'stream_content' => [
                    [
                        'quality' => '480p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/hlKFxyxOWIQ?si=d5nuCs6BYaIZJhSn',
                    ],
                    [
                        'quality' => '720p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/hlKFxyxOWIQ?si=0NmD4yAoShQigs07',
                    ],
                    [
                        'quality' => '1080p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/hlKFxyxOWIQ?si=_KagBhO3OxIJxdyx',
                    ],
                    [
                        'quality' => '1440p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/hlKFxyxOWIQ?si=H096nrbHzq3_2hWF',
                    ],
                ],  
            ],
            [
                'name' => 'The Daring Player',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/the_daring_player_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/the_daring_player_poster.png'),
                'description' => 'The Daring Playe is a comedic tale of an intrepid athlete with a knack for finding himself in the most absurd and laugh-inducing predicaments. With a heart full of courage and a head full of unconventional ideas, our hero tackles everything from high-stakes competitions to everyday mishaps. Along the way, he encounters a quirky cast of characters who add to the chaos and fun. Packed with laughter, excitement, and heartwarming moments, this movie is a delightful journey through the world of a true daredevil. 🏅🤣',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/OPQogQKVeTk?si=YOmW1cKIiWOZqq34',
                'movie_access' => 'paid',
                'status' => 1,
                'language' => 'hindi',
                'genre_id' => [1],
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:50',
                'release_date' => '2019-03-07',
                'is_restricted' => true,
                'actors' => [9, 10, 11,12], 
                'directors' => [35,23,24],  
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/U-KfnCpEEl4?si=fQOoRWLbiIVBaL1k',
                'enable_quality' => 1,
                'stream_content' => [
                    [
                        'quality' => '480p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/U-KfnCpEEl4?si=Vc70N3_zFcBD0yR4',
                    ],
                    [
                        'quality' => '720p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/U-KfnCpEEl4?si=HUmROBp9MupZ_mAa',
                    ],
                    [
                        'quality' => '1080p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/U-KfnCpEEl4?si=Wd3qSh7kodL-LvxC',
                    ],
                    [
                        'quality' => '1440p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/U-KfnCpEEl4?si=QcjXOGpAHgsq1IJl',
                    ],
                ],
            ],
            [
                'name' => 'The Monkey King: Kingdom of Shadows',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/the_monkey_king_kingdom_of_shadows_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/the_monkey_king_kingdom_of_shadows_poster.png'),
                'description' => 'The Monkey King: Kingdom of Shadows" follows the legendary Monkey King as he faces his greatest challenge yet. When an ancient evil emerges, casting a dark shadow over his kingdom, the Monkey King must summon all his strength and cunning to protect his realm. Alongside a band of loyal companions, he ventures into treacherous territories, confronts formidable enemies, and uncovers hidden secrets. This gripping thriller blends epic battles with supernatural intrigue, delivering a heart-pounding adventure that will keep audiences on the edge of their seats. 🐒⚔️🌑',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/Ssu5XsVngDI?si=le5P47hemzqTawW4',
                'movie_access' => 'paid',
                'status' => 1,
                'language' => 'hindi',
                'genre_id' => [8],
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:55',
                'release_date' => '2020-01-10',
                'is_restricted' => true,
                'actors' => [17, 4, 5], 
                'directors' => [36, 25, 26], 
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/7QbM6edqrr8?si=cxJkQJPoga1k3GBH'
            ],
            [
                'name' => 'Deep Sea Mysteries',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/deep_sea_mysteries_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/deep_sea_mysteries_poster.png'),
                'description' => 'In Deep Sea Mysteries, an elite team of scientists and explorers embarks on a high-stakes underwater expedition to uncover the ocean\'s most guarded secrets. As they venture deeper into uncharted territories, they encounter breathtaking wonders and formidable creatures. However, the thrill of discovery quickly turns into a fight for survival as they stumble upon a long-lost civilization\'s dark secrets. With danger lurking at every turn, the team must navigate treacherous waters, solve ancient puzzles, and confront their deepest fears to unravel the mysteries of the deep sea. This thrilling adventure is a gripping tale of courage, discovery, and the relentless pursuit of truth. 🌊🦈⚓',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/WwSsikrAN-E?si=0G6UPDm-H7LW3Not',
                'movie_access' => 'paid',
                'status' => 1,
                'language' => 'hindi',
                'genre_id' => [8],
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '01:50',
                'release_date' => '2020-03-15',
                'is_restricted' => true,
                'actors' => [18, 8], 
                'directors' => [37, 27, 28], 
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/so2XtxcSLHQ?si=3AkAW4QC9aZOpGKA',
                'enable_quality' => 1,
                'stream_content' => [
                    [
                        'quality' => '480p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/so2XtxcSLHQ?si=ac0V29WoRwQyTNc7',
                    ],
                    [
                        'quality' => '720p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/so2XtxcSLHQ?si=N97AW29RFILE1nZ0',
                    ],
                    [
                        'quality' => '1080p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/so2XtxcSLHQ?si=yk7Cvs-MlKkT8MQy',
                    ],
                   
                ],
            ],
            [
                'name' => 'The Wacky Adventures of Tim and Tom',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/the_wacky_adventures_of_tim_and_tom_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/the_wacky_adventures_of_tim_and_tom_poster.png'),
                'description' => 'Get ready for non-stop laughter with "The Wacky Adventures of Tim and Tom." This comedy movie follows two lifelong friends, Tim and Tom, whose penchant for wild schemes and hilarious mishaps constantly lands them in the most outrageous situations. Whether they\'re attempting to start a dubious business, navigating the pitfalls of modern dating, or embarking on a spontaneous road trip, their friendship is the one constant that keeps them grounded amidst the chaos. With a mix of slapstick humor, witty dialogue, and heartwarming moments, "The Wacky Adventures of Tim and Tom" is a feel-good film that celebrates the enduring power of friendship and the joy of embracing life\'s unpredictability. Join Tim and Tom on their laugh-out-loud journey where every day is a new adventure filled with endless comedy. 😂🚗🍿',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/Mmq_NVwLN_g?si=JfALgqlfHlZgaoZP',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'genre_id' => [3], 
                'content_rating' => 'PG (Parental Guidance Suggested)',
                'duration' => '02:40',
                'release_date' => '2020-03-01',
                'is_restricted' => true,
                'actors' => [3,2],
                'directors' => [38,22],
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/dXiLaNVaRyw?si=an3a6I7K8ocDdA-A'
            ],
            [
                'name' => 'Laugh Out Loud',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/laugh_out_loud_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/laugh_out_loud_poster.png'),
                'description' => 'Follow the uproarious escapades of a group of mismatched friends as they navigate life\'s absurdities. From wild parties to chaotic road trips, their misfortunes and misunderstandings lead to a series of laugh-out-loud moments. With each friend bringing their own quirky personality to the mix, they find themselves in hilariously unpredictable situations. Through it all, their camaraderie and the power of laughter keep them together, reminding us that sometimes the best way to handle life\'s challenges is to just laugh out loud. 😂🚗🌟',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/fEzWvEoD9ew?si=YNoDNhX0EOQ4oXsH',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'genre_id' => [3], 
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:35',
                'release_date' => '2020-04-04',
                'is_restricted' => true,
                'actors' => [20],
                'directors' => [39,30],
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/zW3f0TYKJqw?si=X2K5WpJXtcS_6Pav'
            ],
            [
                'name' => 'Frank and Fearless Adventures',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/frank_and_fearless_adventures_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/frank_and_fearless_adventures_poster.png'),
                'description' => 'Frank and Fearless Adventures is a heartwarming and hilarious family comedy that follows the unlikely duo of Frank, a bumbling but lovable inventor, and Fearless, a daring young adventurer with a knack for getting into trouble. Together, they embark on a series of misadventures that take them from their quiet hometown to the wilds of the jungle, the depths of the ocean, and even outer space. Along the way, they encounter quirky characters, outlandish obstacles, and plenty of laughs. With its blend of humor, excitement, and touching moments, "Frank and Fearless Adventures" is a feel-good film that promises to entertain audiences of all ages. 🌟😂🛠️',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/_8slPqV-16w?si=oCMjQ4pRqCEBc-fF',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'genre_id' => [3], 
                'content_rating' => 'PG (Parental Guidance Suggested)',
                'duration' => '02:58',
                'release_date' => '2020-06-11',
                'is_restricted' => true,
                'actors' => [1,5,6],
                'directors' => [40,29],
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/RnqIF9ZHxKk?si=CcqAkQHgZBnpZ9nC'
            ],
            [
                'name' => 'Haunting Shadows',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/haunting_shadows_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/haunting_shadows_poster.png'),
                'description' => 'A bone-chilling horror movie, a group of investigators explores the dark and twisted history of an abandoned orphanage shrouded in mystery. As they uncover the sinister events that transpired within its walls, they encounter restless spirits and malevolent forces determined to keep their secrets hidden. Battling their own fears and the supernatural, they must find a way to escape before they become the next victims. "Haunting Shadows" delivers a harrowing and suspenseful experience that will leave viewers on the edge of their seats. 👻🏚️',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/_XV764KWBrA?si=dX3daijNvpduGGKR',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'genre_id' => [5],
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:40',
                'release_date' => '2020-07-03',
                'is_restricted' => true,
                'actors' => [18, 8],
                'directors' => [36, 25, 26],
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/ATi3GdospAo?si=KVHj8EF_1AFOkci5'
            ],
            [
                'name' => 'Echoes of Terror',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/echoes_of_terror_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/echoes_of_terror_poster.png'),
                'description' => 'A spine-tingling horror movie, a once-peaceful town is haunted by a series of terrifying events linked to a dark and ancient curse. As a group of brave investigators unravels the sinister history behind the curse, they encounter vengeful spirits and malevolent forces. Battling their own fears and the supernatural, they must race against time to uncover the truth and put an end to the terror. "Echoes of Terror" is a harrowing journey into the unknown, filled with suspense and chilling moments that will leave viewers breathless. 😱👹',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/smTK_AeAPHs?si=UQAlhFHgP-j1YSjG',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'genre_id' => [5],
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:50',
                'release_date' => '2021-07-08',
                'is_restricted' => true,
                'actors' => [17, 5],
                'directors' => [26],
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/wTGqBmUTCMQ?si=jTuwff4Q6CDdEN5H'

                
            ],
            [
                'name' => 'Origins of Civilization: The Dawn of Empires',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/origins_civilization_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/origins_civilization_poster.png'),
                'description' => 'Origins of Civilization: The Dawn of Empires is a captivating historical documentary that explores the emergence of the world\'s first great civilizations. Journey through ancient Mesopotamia, Egypt, the Indus Valley, and China as the film uncovers the remarkable innovations, cultural achievements, and pivotal moments that shaped human history. With stunning visuals and expert insights, this documentary brings to life the stories of the pioneering societies that laid the foundation for modern civilization. Discover how these ancient empires rose to greatness, overcame challenges, and left an enduring legacy that continues to influence our world today. 🌍🏛️',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/W0_55mECsa4?si=b_AlIpdvNC_wZ5Zr',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'genre_id' => [4],
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:56',
                'release_date' => '2021-07-17',
                'is_restricted' => true,
                'actors' => [9, 10, 11, 12],
                'directors' => [36, 25, 26],
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/7jbO8ToJTko?si=cHMWBfwPFObg3wma'
            ],
            [
                'name' => 'Legacy of Antiquity: Origins of Civilization',
                'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/legacy_of_antiquity_thumb.png'),
                'poster_url' => public_path('/dummy-images/entertainment/movie/poster/legacy_of_antiquity_poster.png'),
                'description' => 'Legacy of Antiquity: Origins of Civilization is a comprehensive historical documentary that journeys back to the dawn of human civilization. From the ancient cities of Sumer and the monumental architecture of Egypt to the philosophical enlightenment of Greece and the engineering marvels of Rome, this film unravels the complex tapestry of early societies and their enduring legacies. Through expert analysis, immersive storytelling, and breathtaking visuals, uncover the cultural, political, and technological advancements that defined these pivotal periods in history. Legacy of Antiquity invites viewers to explore the origins of human achievement and the profound impact of ancient civilizations on our modern world. 🏛️🌍',
                'trailer_url_type' => 'YouTube',
                'trailer_url' => 'https://youtu.be/JZAkVbZVPTg?si=6ruDm6DJxmgWCnyJ',
                'movie_access' => 'free',
                'status' => 1,
                'language' => 'english',
                'genre_id' => [4],
                'content_rating' => 'TV-MA (Mature Audiences)',
                'duration' => '02:45',
                'release_date' => '2022-07-24',
                'is_restricted' => true,
                'actors' => [9, 3, 2, 11,12],
                'directors' => [35, 23, 24],
                'type' => 'movie',
                'video_upload_type' => 'YouTube',
                'video_url_input' => 'https://youtu.be/WltJPKFo_J4?si=L4BpLkhhHu3Yk5SS',   
                'enable_quality' => 1,
                'stream_content' => [
                    [
                        'quality' => '480p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/WltJPKFo_J4?si=zz4-zHhey7CK-d3N',
                    ],
                    [
                        'quality' => '720p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/WltJPKFo_J4?si=0wIlovLv2RVlfjxt6',
                    ],
                    [
                        'quality' => '1080p',
                        'type' => 'YouTube',
                        'url' => 'https://youtu.be/WltJPKFo_J4?si=BvEAyAoOkOdLnFr4',
                    ],
                  
                ]
            ],
                [
                    'name' => 'Rise Above',
                    'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/rise_above_thumb.png'),
                    'poster_url' => public_path('/dummy-images/entertainment/movie/poster/rise_above_poster.png'),
                    'description' => 'In Rise Above, an inspirational short film, we follow the journey of a young individual who faces immense challenges and setbacks. Through determination, resilience, and the support of loved ones, they rise above adversity to achieve their dreams. This heartwarming and motivational story reminds us that no matter how difficult the path, success is within reach if we believe in ourselves and never give up. 🌟💪',
                    'trailer_url_type' => 'YouTube',
                    'trailer_url' => 'https://youtu.be/4v7bSmTqx-Y?si=Oa05Nt5KpiD59vYN',
                    'movie_access' => 'free',
                    'status' => 1,
                    'language' => 'english',
                    'genre_id' => [6],  
                    'content_rating' => 'TV-MA (Mature Audiences)',
                    'duration' => '02:35',
                    'release_date' => '2023-08-04',
                    'is_restricted' => true,
                    'actors' => [1, 5, 6, 17, 20], 
                    'directors' => [33, 34, 25,24],  
                    'type' => 'movie',
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/4v7bSmTqx-Y?si=hVcCSbCyThkDg503'
                ],
                
                [
                    'name' => 'A Journey of Triumph',
                    'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/a_journey_of_triumph_thumb.png'),
                    'poster_url' => public_path('/dummy-images/entertainment/movie/poster/a_journey_of_triumph_poster.png'),
                    'description' => 'A Journey of Triumph is an uplifting and motivational film that chronicles the extraordinary stories of individuals who have faced insurmountable odds and emerged victorious. Through powerful narratives and moving testimonials, the film highlights the unwavering spirit, determination, and courage required to rise above adversity. With breathtaking visuals and heartfelt moments, A Journey of Triumph inspires viewers to believe in their own potential and strive for greatness, no matter the obstacles. 🌟💪',
                    'trailer_url_type' => 'YouTube',
                    'trailer_url' => 'https://youtu.be/gJxRD-092rI?si=Yi-PuPoYEKRicQHv',
                    'movie_access' => 'free',
                    'status' => 1,
                    'language' => 'english',
                    'genre_id' => [6], 
                    'content_rating' => 'TV-MA (Mature Audiences)',
                    'duration' => '01:25',
                    'release_date' => '2023-08-10',
                    'is_restricted' => true,
                    'actors' => [18, 8, 20], 
                    'directors' => [26, 40],  
                    'type' => 'movie',
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/6WxscBX09Xs?si=rDk_v2tbG_G0Yup7'
                ],
                
                [
                    'name' => 'Forever and a Day',
                    'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/forever_and_a_day_thumb.png'),
                    'poster_url' => public_path('/dummy-images/entertainment/movie/poster/forever_and_a_day_poster.png'),
                    'description' => 'Forever and a Day is a captivating romantic film that tells the enchanting story of two souls destined to be together across lifetimes. When a modern-day writer discovers the love letters of a long-lost couple, she becomes entwined in their timeless romance. As their story unfolds, she embarks on a journey to find her own true love, realizing that some connections are meant to last forever. Filled with heartfelt moments, breathtaking scenery, and an unforgettable love story, "Forever and a Day" is a beautiful ode to enduring love. 💖⏳',
                    'trailer_url_type' => 'YouTube',
                    'trailer_url' => 'https://youtu.be/NTAzn4W48gc?si=WCToq6upXzjdetux',
                    'movie_access' => 'free',
                    'status' => 1,
                    'language' => 'english',
                    'genre_id' => [7], 
                    'content_rating' => 'TV-MA (Mature Audiences)',
                    'duration' => '01:30',
                    'release_date' => '2024-04-04',
                    'is_restricted' => true,
                    'actors' => [3, 2, 1, 5, 6], 
                    'directors' => [29, 36, 25],  
                    'type' => 'movie',
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/KitwmpvZ2GU?si=0PZuT_sk6eTIDDkB'
                ],
                
                [
                    'name' => 'Forever in My Heart',
                    'thumbnail_url' => public_path('/dummy-images/entertainment/movie/thumb/forever_in_my_heart_thumb.png'),
                    'poster_url' => public_path('/dummy-images/entertainment/movie/poster/forever_in_my_heart_poster.png'),
                    'description' => 'Forever in My Heart tells the enchanting story of two soulmates whose love endures the test of time and distance. When Sarah and Michael meet, it\'s love at first sight, but life circumstances soon separate them. Despite the miles and years that come between them, their connection remains unbroken. Through letters, dreams, and unexpected reunions, they navigate life\'s challenges, always finding their way back to each other. This romantic tale is a celebration of enduring love, hope, and the belief that true love is forever. ❤️💑',
                    'trailer_url_type' => 'YouTube',
                    'trailer_url' => 'https://youtu.be/p3PboMzVt8c?si=_tM07Z7p7k1LqjDH',
                    'movie_access' => 'free',
                    'status' => 1,
                    'language' => 'english',
                    'genre_id' => [7], 
                    'content_rating' => 'TV-MA (Mature Audiences)',
                    'duration' => '02:30',
                    'release_date' => '2024-05-11',
                    'is_restricted' => true,
                    'actors' => [20, 9, 3, 2, 11, 12],  
                    'directors' => [37, 27, 28, 36, 25,40],  
                    'type' => 'movie',
                    'video_upload_type' => 'YouTube',
                    'video_url_input' => 'https://youtu.be/22l6w8n9iCc?si=u2fUQaTyOLUyTvhh',
                    'enable_quality' => 1,
                    'stream_content' => [
                        [
                            'quality' => '480p',
                            'type' => 'YouTube',
                            'url' => 'https://youtu.be/22l6w8n9iCc?si=ojEDxNeMZ9DEFg8J',
                        ],
                        [
                            'quality' => '720p',
                            'type' => 'YouTube',
                            'url' => 'https://youtu.be/22l6w8n9iCc?si=4gAqMfc4DUSUyg3G',
                        ],
                        [
                            'quality' => '1080p',
                            'type' => 'YouTube',
                            'url' => 'https://youtu.be/22l6w8n9iCc?si=gVdCokIa76dm3gJy',
                        ],
                    
                    ],
                ]
                
    ];

        if (env('IS_DUMMY_DATA')) {
            foreach ($entertainments as $entertainmentData) {
                $thumbnailPath = $entertainmentData['thumbnail_url'] ?? null;
                $posterPath = $entertainmentData['poster_url'] ?? null;

                $entertainment = Entertainment::create(Arr::except($entertainmentData, ['thumbnail_url', 'poster_url', 'genre_id', 'actors', 'directors', 'stream_content']));

                if (isset($thumbnailPath)) {
                    $thumbnailUrl = $this->attachImage($entertainment, $thumbnailPath, 'thumbnail_url');
                    if ($thumbnailUrl) {
                        $entertainment->thumbnail_url = $thumbnailUrl;
                    }
                }

                if (isset($posterPath)) {
                    $posterUrl = $this->attachImage($entertainment, $posterPath, 'poster_url');
                    if ($posterUrl) {
                        $entertainment->poster_url = $posterUrl;
                    }
                }

                $entertainment->save();

                foreach ($entertainmentData['genre_id'] as $genreId) {
                    EntertainmentGenerMapping::create([
                        'entertainment_id' => $entertainment->id,
                        'genre_id' => $genreId,
                    ]);
                }

                foreach ($entertainmentData['actors'] as $actorId) {
                    EntertainmentTalentMapping::create([
                        'entertainment_id' => $entertainment->id,
                        'talent_id' => $actorId,
                    ]);
                }

                foreach ($entertainmentData['directors'] as $directorId) {
                    EntertainmentTalentMapping::create([
                        'entertainment_id' => $entertainment->id,
                        'talent_id' => $directorId,
                    ]);
                }

                if (isset($entertainmentData['stream_content']) && is_array($entertainmentData['stream_content'])) {
                    foreach ($entertainmentData['stream_content'] as $streamContent) {
                        EntertainmentStreamContentMapping::create([
                            'entertainment_id' => $entertainment->id,
                            'quality' => $streamContent['quality'],
                            'type' => $streamContent['type'],
                            'url' => $streamContent['url'],
                        ]);
                    }
                }
                
            }

            Schema::enableForeignKeyConstraints();
        }
    }

    private function attachImage($model, $publicPath, $collectionName)
    {
        if (!env('IS_DUMMY_DATA_IMAGE')) return false;

        $file = new \Illuminate\Http\File($publicPath);

        $media = $model->addMedia($file)->preservingOriginal()->toMediaCollection($collectionName);

        return $media->getUrl();
    }
}
