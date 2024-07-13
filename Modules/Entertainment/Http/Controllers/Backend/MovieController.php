<?php

namespace Modules\Entertainment\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\ModuleTrait;
use Yajra\DataTables\DataTables;
use Modules\Constant\Models\Constant;
use Modules\Subscriptions\Models\Plan;
use Modules\Genres\Models\Genres;
use Modules\CastCrew\Models\CastCrew;
use Modules\Entertainment\Trait\ImportMovieTrait;
use Modules\Entertainment\Services\EntertainmentService;
use Modules\Entertainment\Services\MovieService;
class MovieController extends Controller
{
     use ImportMovieTrait;
    protected string $exportClass = '\App\Exports\MoiveExport';

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }


        protected $entertainmentService;
        protected $movieService;

        public function __construct(EntertainmentService $entertainmentService,  MovieService $movieService)
        {
            $this->entertainmentService = $entertainmentService;
            $this->movieService= $movieService;

            $this->traitInitializeModuleTrait(
                'movie.title', 
                'movies', 
                'fa-solid fa-clipboard-list'
            );
        }
    

    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];

        $module_action = 'List';

        $export_import = true;
        $export_columns = [
            [
                'value' => 'name',
                'text' => ' Name',
            ],
            [
                'value' => 'movie_access',
                'text' => ' Moive Access',
            ],

            [
                'value' => 'language',
                'text' => 'Language',
            ],

            [
                'value' => 'IMDb_rating',
                'text' => 'IMDb Rating',
            ],

            [
                'value' => 'content_rating',
                'text' => 'Content Rating',
            ],

            [
                'value' => 'duration',
                'text' => 'Duration',
            ],

            [
                'value' => 'release_date',
                'text' => 'Release Date',
            ],


            [
                'value' => 'is_restricted',
                'text' => 'Restricted',
            ],

            [
                'value' => 'status',
                'text' => 'Status',
            ]

        ];
        $export_url = route('backend.movies.export');

        $geners=Genres::where('status',1)->get();
        $plan=Plan::where('status',1)->get();

        $movie_language=Constant::where('type','movie_language')->get();

        return view('entertainment::backend.movie.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url','geners','movie_language','plan'));

    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $filter = $request->filter;
        $type='movie';
        return $this->entertainmentService->getDataTable($datatable, $filter, $type);
    }

    /**
     * Show the form for creating a new resource.
     */
   
        public function create()
        {
            $constants = Constant::whereIn('type', ['upload_type', 'movie_language', 'video_quality'])->get()->groupBy('type');
            
            $upload_url_type = $constants->get('upload_type', collect());
            $movie_language = $constants->get('movie_language', collect());
            $video_quality = $constants->get('video_quality', collect());
        
            $plan = Plan::where('status', 1)->get();
            $genres = Genres::where('status', 1)->get();
        
            $numberOptions = collect(range(1, 10))->mapWithKeys(function ($number) {
                return [$number => $number];
            });
        
            $cast_crew = CastCrew::whereIn('type', ['actor', 'director'])->get()->groupBy('type');
            
            $actors = $cast_crew->get('actor', collect());
            $directors = $cast_crew->get('director', collect());
        
            $type = 'movie';
            $module_title = __('movie.add_title');
            $mediaUrls = getMediaUrls();
        
            return view('entertainment::backend.entertainment.create', compact(
                'upload_url_type', 'plan', 'movie_language', 'genres', 'numberOptions', 'actors', 'directors', 'video_quality', 'type', 'module_title', 'mediaUrls'
            ));
         }


         public function ImportMovie($id)
         {
             $result = $this->movieService->importMovie($id);

        
             if (isset($result['success']) && $result['success'] === false){
                 return response()->json([
                     'success' => false,
                     'message' => $result['status_message']
                 ], 400);
             }
     
             return response()->json([
                 'success' => true,
                 'data' => $result
             ], 200);
         }

         
    // public function ImportMovie($id)
    //  {
    // $movie_id = $id;
    // $movie_details = null;

    // $configuration =$this->getConfiguration();
    // $configurationData = json_decode($configuration, true);

    // while($configurationData === null) {

    //     $configuration =$this->getConfiguration();
    //     $configurationData = json_decode($configuration, true);
    // }

    // if(isset($configurationData['success']) && $configurationData['success'] === false) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => $configurationData['status_message']
    //     ], 400);
    // }


    // $movie_details = $this->getMovieDetails($movie_id);
    // $movieDetail = json_decode($movie_details, true);

    // while($movieDetail === null) {
    //     $movie_details = $this->getMovieDetails($movie_id);
    //     $movieDetail = json_decode($movie_details, true);
    // }

    // if (isset($movieDetail['success']) && $movieDetail['success'] === false) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => $movieDetail['status_message']
    //     ], 400);
    // }

    // $movie_video = $this->getMovieVideo($movie_id);
    // $movieVideoDetail = json_decode($movie_video, true);

    // while($movieVideoDetail === null) {

    //     $movie_video = $this->getMovieVideo($movie_id);
    //     $movieVideoDetail = json_decode($movie_video, true);
    // }

    // if (isset($movieVideoDetail['success']) && $movieVideoDetail['success'] === false) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => $movieVideoDetail['status_message']
    //     ], 400);
    // }

    // $castcrew = $this->getCastCrew($movie_id);
    // $castcrewDetail = json_decode($castcrew, true);


    // while($castcrewDetail === null) {

    //     $castcrew = $this->getCastCrew($movie_id);
    //     $castcrewDetail = json_decode($castcrew, true);
    // }

    // $actors = [];
    // $directors = [];
    // $actorCount = 0;
    // $directorCount = 0;
    // $maxCount = 5;
    // $all_actors=[];
    // $all_directors=[];

    // if (isset($castcrewDetail['cast']) && is_array($castcrewDetail['cast'])) {
    //     foreach ($castcrewDetail['cast'] as $cast) {
    //         if (($actorCount >= $maxCount)) {
    //             break;
    //         }

    //         if ($cast['known_for_department'] == 'Acting') {
    //             $cast_details = $this->getCrewDetials($cast['id']);
    //             $castDetails = json_decode($cast_details, true);


    //             if (!empty($castDetails)) {

    //                 $cast_data = [
    //                     'name' => $castDetails['name'],
    //                     'type' => 'actor',
    //                     'file_url' => $configurationData['images']['secure_base_url'] . 'original' . $castDetails['profile_path'],
    //                     'bio' => $castDetails['biography'],
    //                     'place_of_birth' => $castDetails['place_of_birth'],
    //                     'dob' => $castDetails['birthday'],
    //                     'designation' => null,
    //                 ];

    //                     $castRecord = CastCrew::updateOrCreate(
    //                         ['name' => $cast_data['name'], 'dob' => $cast_data['dob'] ],
    //                         $cast_data
    //                     );
    //                     if ($actorCount < $maxCount) {
    //                         $actors[] = $castRecord->id;
    //                         $actorCount++;


    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     $all_actors=CastCrew::where('type', 'actor')->get();


    // if (isset($castcrewDetail['crew']) && is_array($castcrewDetail['crew'])) {
    //     foreach ($castcrewDetail['crew'] as $crew) {
    //         if (($directorCount >= $maxCount)) {
    //             break;
    //         }

    //         if ($crew['known_for_department'] == 'Directing') {
    //             $crew_details = $this->getCrewDetials($crew['id']);
    //             $crewDetails = json_decode($crew_details, true);

    //             if (!empty($crewDetails)) {

    //                 $crew_data = [
    //                     'name' => $crewDetails['name'],
    //                     'type' => 'director',
    //                     'file_url' => $configurationData['images']['secure_base_url'] . 'original' . $crewDetails['profile_path'],
    //                     'bio' => $crewDetails['biography'],
    //                     'place_of_birth' => $crewDetails['place_of_birth'],
    //                     'dob' => $crewDetails['birthday'],
    //                     'designation' => null,
    //                 ];


    //                     $crewRecord = CastCrew::updateOrCreate(
    //                         ['name' => $crew_data['name'], 'dob' => $crew_data['dob']],
    //                         $crew_data
    //                     );

    //                     if ($directorCount < $maxCount) {
    //                         $directors[] = $crewRecord->id;
    //                         $directorCount++;

    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     $all_directors=CastCrew::where('type', 'director')->get();

    // $language = null;

    // if (isset($movieDetail['spoken_languages']) && is_array($movieDetail['spoken_languages'])) {
    //     $spoken_languages = $movieDetail['spoken_languages'];

    //     if (!empty($spoken_languages)) {
    //         $first_language = $spoken_languages[0];
    //         $language = $first_language['name'];

    //         $language_data = [
    //             'name' => $language,
    //             'value' => strtolower($language),
    //             'type' => 'movie_language',
    //             'status' => 1,
    //         ];

    //         Constant::updateOrCreate(
    //             ['name' => $language_data['name'], 'type' => $language_data['type']],
    //             $language_data
    //         );
    //     }
    // }

    // $all_language=Constant::where('type', 'movie_language')->get();

    // $genersIds = [];

    // if(isset($movieDetail['genres']) && is_array($movieDetail['genres'])) {
    //     foreach ($movieDetail['genres'] as $genre) {
    //         $genre_data = [
    //             'name' => $genre['name'],
    //             'status' => 1,
    //         ];

    //         $genreRecord = Genres::updateOrCreate(
    //             ['name' => $genre_data['name']],
    //             $genre_data
    //         );

    //         $genersIds[] = $genreRecord->id;
    //     }
    // }

    // $all_genres= Genres::where('status',1)->get();

    // $trailer_url_type=null;
    // $trailer_url=null;
    // $moive_list=[];

    // if(isset($movieVideoDetail['results']) && is_array($movieVideoDetail['results'])) {

    //     foreach ($movieVideoDetail['results'] as $video) {

    //         if($video['type'] == 'Trailer'){

    //             $trailer_url_type= $video['site'];
    //             $trailer_url='https://www.youtube.com/watch?v='.$video['key'];

    //         }else{

    //             $moive_list[]=[

    //                'video_quality_type'=>$video['site'],
    //                'video_quality'=>$video['size'],
    //                'quality_video'=>'https://www.youtube.com/watch?v='.$video['key'],
    //             ];

    //         }

    //     }
    // }

    // function formatDuration($minutes) {
    //     $hours = floor($minutes / 60);
    //     $minutes = $minutes % 60;
    //     return sprintf('%02d:%02d', $hours, $minutes);
    // }


    // $data = [
    //     'poster_url' => $configurationData['images']['secure_base_url'] . 'original' . $movieDetail['poster_path'],
    //     'thumbnail_url' => $configurationData['images']['secure_base_url'] . 'original' . $movieDetail['backdrop_path'],
    //     'trailer_url_type'=>$trailer_url_type,
    //     'trailer_url'=>$trailer_url,
    //     'name' => $movieDetail['original_title'],
    //     'description' => $movieDetail['overview'],
    //     'duration' => formatDuration($movieDetail['runtime']),
    //     'language' => $language,
    //     'genres' => $genersIds,
    //     'is_restricted' => $movieDetail['adult'],
    //     'release_date' => $movieDetail['release_date'],
    //     'actors' => $actors,
    //     'directors' => $directors,
    //     'movie_access'=>'free',
    //     'enable_quality'=>true,
    //     'entertainmentStreamContentMappings'=>$moive_list,
    //     'all_actors'=>$all_actors,
    //     'all_directors'=>$all_directors,
    //     'all_language'=>$all_language,
    //     'all_genres'=>$all_genres,
    // ];

    //      return response()->json([
    //         'success' => true,
    //         'data' => $data,
    //     ], 200);
    // }


    // public function importMovie($id)
    // {
    //     $result = $this->movieService->importMovie($id);

    //     if ($result['success'] === false) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $result['message']
    //         ], 400);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $result['data']
    //     ], 200);
    // }



    public function ImportTVshow($id)
    {
        $movie_id = $id;
        $movie_details = null;
        $configuration =$this->getConfiguration();

        $configurationData = json_decode($configuration, true);

        if($configurationData === null){
            return response()->json([
                'success' => false,
                'message' => 'Failed to decode configuration JSON.'
            ], 400);
        }

        if(isset($configurationData['success']) && $configurationData['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $configurationData['status_message']
            ], 400);
        }

        $movie_details = $this->getMovieDetails($movie_id);
        $movieDetail = json_decode($movie_details, true);

        if($movieDetail === null) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to decode movie details JSON.'
            ], 400);
        }

        if (isset($movieDetail['success']) && $movieDetail['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $movieDetail['status_message']
            ], 400);
        }

        $movie_video = $this->getMovieVideo($movie_id);
        $movieVideoDetail = json_decode($movie_video, true);

        if ($movieVideoDetail === null) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to decode movie video JSON.'
            ], 400);
        }

        if (isset($movieVideoDetail['success']) && $movieVideoDetail['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $movieVideoDetail['status_message']
            ], 400);
        }

        $castcrew = $this->getCastCrew($movie_id);
        $castcrewDetail = json_decode($castcrew, true);


        if ($castcrewDetail === null) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to decode cast/crew JSON.'
            ], 400);
        }

        $actors = [];
        $directors = [];
        $actorCount = 0;
        $directorCount = 0;
        $maxCount = 5;

        if (isset($castcrewDetail['cast']) && is_array($castcrewDetail['cast'])) {
            foreach ($castcrewDetail['cast'] as $cast) {
                if (($actorCount >= $maxCount)) {
                    break;
                }

                if ($cast['known_for_department'] == 'Acting') {
                    $cast_details = $this->getCrewDetials($cast['id']);
                    $castDetails = json_decode($cast_details, true);


                    if (!empty($castDetails)) {

                        $cast_data = [
                            'name' => $castDetails['name'],
                            'type' => 'actor',
                            'file_url' => $configurationData['images']['secure_base_url'] . 'original' . $castDetails['profile_path'],
                            'bio' => $castDetails['biography'],
                            'place_of_birth' => $castDetails['place_of_birth'],
                            'dob' => $castDetails['birthday'],
                            'designation' => null,
                        ];

                            $castRecord = CastCrew::updateOrCreate(
                                ['name' => $cast_data['name'], 'dob' => $cast_data['dob'] ],
                                $cast_data
                            );
                            if ($actorCount < $maxCount) {
                                $actors[] = $castRecord->id;
                                $actorCount++;


                            }
                        }
                    }
                }
            }


        if (isset($castcrewDetail['crew']) && is_array($castcrewDetail['crew'])) {
            foreach ($castcrewDetail['crew'] as $crew) {
                if (($directorCount >= $maxCount)) {
                    break;
                }

                if ($crew['known_for_department'] == 'Directing') {
                    $crew_details = $this->getCrewDetials($crew['id']);
                    $crewDetails = json_decode($crew_details, true);

                    if (!empty($crewDetails)) {

                        $crew_data = [
                            'name' => $crewDetails['name'],
                            'type' => 'director',
                            'file_url' => $configurationData['images']['secure_base_url'] . 'original' . $crewDetails['profile_path'],
                            'bio' => $crewDetails['biography'],
                            'place_of_birth' => $crewDetails['place_of_birth'],
                            'dob' => $crewDetails['birthday'],
                            'designation' => null,
                        ];


                            $crewRecord = CastCrew::updateOrCreate(
                                ['name' => $crew_data['name'], 'dob' => $crew_data['dob']],
                                $crew_data
                            );

                            if ($directorCount < $maxCount) {
                                $directors[] = $crewRecord->id;
                                $directorCount++;

                            }
                        }
                    }
                }
            }

        $language = null;

        if (isset($movieDetail['spoken_languages']) && is_array($movieDetail['spoken_languages'])) {
            $spoken_languages = $movieDetail['spoken_languages'];

            if (!empty($spoken_languages)) {
                $first_language = $spoken_languages[0];
                $language = $first_language['name'];

                $language_data = [
                    'name' => $language,
                    'value' => $language,
                    'type' => 'movie_language',
                    'status' => 1,
                ];

                Constant::updateOrCreate(
                    ['name' => $language_data['name'], 'type' => $language_data['type']],
                    $language_data
                );
            }
        }

        $genersIds = [];

        if(isset($movieDetail['genres']) && is_array($movieDetail['genres'])) {
            foreach ($movieDetail['genres'] as $genre) {
                $genre_data = [
                    'name' => $genre['name'],
                    'status' => 1,
                ];

                $genreRecord = Genres::updateOrCreate(
                    ['name' => $genre_data['name']],
                    $genre_data
                );

                $genersIds[] = $genreRecord->id;
            }
        }

        $trailer_url_type=null;
        $trailer_url=null;
        $moive_list=[];

        if(isset($movieVideoDetail['results']) && is_array($movieVideoDetail['results'])) {

            foreach ($movieVideoDetail['results'] as $video) {

                if($video['type'] == 'Trailer'){

                    $trailer_url_type= $video['site'];
                    $trailer_url='https://www.youtube.com/watch?v='.$video['key'];

                }else{

                    $moive_list[]=[

                       'video_quality_type'=>$video['site'],
                       'video_quality'=>$video['size'],
                       'quality_video'=>'https://www.youtube.com/watch?v='.$video['key'],
                    ];

                }

            }
        }

        function formatDuration($minutes) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        $data = [
            'poster_url' => $configurationData['images']['secure_base_url'] . 'original' . $movieDetail['poster_path'],
            'thumbnail_url' => $configurationData['images']['secure_base_url'] . 'original' . $movieDetail['backdrop_path'],
            'trailer_url_type'=>$trailer_url_type,
            'trailer_url'=>$trailer_url,
            'name' => $movieDetail['original_title'],
            'description' => $movieDetail['overview'],
            'duration' => formatDuration($movieDetail['runtime']),
            'language' => $language,
            'genres' => $genersIds,
            'is_restricted' => $movieDetail['adult'],
            'release_date' => $movieDetail['release_date'],
            'actors' => $actors,
            'directors' => $directors,
            'movie_access'=>'free',
            'enable_quality'=>true,
            'entertainmentStreamContentMappings'=>$moive_list
        ];

             return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        }





}


