@extends('backend.layouts.app')
@section('content')

<div class="d-flex align-items-end justify-content-between gap-3 mb-3">
    <div class="flex-grow-1">
        {{ html()->label(__('movie.lbl_tv_show') , 'tvshow_id')->class('form-label') }}
        {{ html()->text('tvshow_id')->attribute('value', old('tvshow_id'))->placeholder(__('placeholder.lbl_tvshow_id'))->class('form-control') }}
        <span class="text-danger" id="tvshow_id_error"></span>
    </div>

    <div id="loader" style="display: none;">

     <button class="btn btn-md btn-primary float-right">{{__('tvshow.lbl_loading')}}</button>

    </div>

    <button class="btn btn-md btn-primary float-right" id="import_tvshow_id">{{__('tvshow.lbl_import')}}</button>

</div>

    <form method="POST" id="form" action="{{ route('backend.entertainments.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>About TV shows</h6>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        {{ html()->hidden('type', $type)->id('type') }}
                        {{ html()->hidden('tmdb_id', null)->id('tmdb_id') }}
                        <div class="position-relative">
                            {{ html()->label(__('movie.lbl_thumbnail'), 'thumbnail')->class('form-label form-control-label') }}
                            <div class="input-group btn-file-upload">
                                {{ html()->button('Select')
                                    ->class('input-group-text form-control')
                                    ->type('button')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                                    ->attribute('data-hidden-input', 'file_url_thumbnail')
                                }}

                                {{ html()->text('thumbnail_input')
                                    ->class('form-control')
                                    ->placeholder('Select Image')
                                    ->attribute('aria-label', 'Thumbnail Image')
                                    ->attribute('data-bs-toggle', 'modal')
                                    ->attribute('data-bs-target', '#exampleModal')
                                    ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                                    ->attribute('data-hidden-input', 'file_url_thumbnail')
                                }}
                            </div>
                            <div class="mt-3" id="selectedImageContainerThumbnail">
                                @if(old('thumbnail_url', isset($data) ? $data->thumbnail_url : ''))
                                    <img src="{{ old('thumbnail_url', isset($data) ? $data->thumbnail_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                @endif
                            </div>
                            {{ html()->hidden('thumbnail_url')->id('file_url_thumbnail')->value(old('thumbnail_url', isset($data) ? $data->thumbnail_url : '')) }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label form-control-label') }}                            
                        <div class="input-group btn-file-upload mb-3">
                            {{ html()->button('Select')
                                ->class('input-group-text form-control')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerPoster')
                                ->attribute('data-hidden-input', 'file_url_poster')
                            }}

                            {{ html()->text('poster_input')
                                ->class('form-control')
                                ->placeholder('Select Image')
                                ->attribute('aria-label', 'Poster Image')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerPoster')
                                ->attribute('data-hidden-input', 'file_url_poster')
                            }}
                        </div>

                        <div class="mt-3" id="selectedImageContainerPoster">
                            @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                                <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                            @endif
                        </div>
                        {{ html()->hidden('poster_url')->id('file_url_poster')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-label form-control-label') }}
                        {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_movie_name'))->class('form-control') }}
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                        {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description'))->rows(5) }}
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        {{ html()->label(__('movie.lbl_trailer_url_type'), 'type')->class('form-label') }}
                        {{ html()->select(
                                'trailer_url_type',
                                $upload_url_type->pluck('name', 'value')->prepend('Select Type', ''),
                                old('trailer_url_type', 'Local'), // Set 'Local' as the default value
                            )->class('form-control select2')->id('trailer_url_type') }}
                        @error('trailer_url_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3 d-none" id="url_input">   
                        {{ html()->label(__('movie.lbl_trailer_url'), 'trailer_url')->class('form-label form-control-label') }}
                        {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                        @error('trailer_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3 d-none" id="url_file_input">
                        {{ html()->label(__('movie.lbl_trailer_video'), 'trailer_video')->class('form-label form-control-label') }}

                        <div class="input-group btn-file-upload">
                            {{ html()->button('Select')
                                ->class('input-group-text form-control')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainertailerurl')
                                ->attribute('data-hidden-input', 'file_url_trailer')
                            }}

                            {{ html()->text('trailer_input')
                                ->class('form-control')
                                ->placeholder('Select Image')
                                ->attribute('aria-label', 'Trailer Image')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainertailerurl')
                                ->attribute('data-hidden-input', 'file_url_trailer')
                            }}
                        </div>
                        <div class="mt-3" id="selectedImageContainertailerurl">
                            @if(old('trailer_url', isset($data) ? $data->trailer_url : ''))
                                <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                            @endif
                        </div>

                        {{ html()->hidden('trailer_video')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->trailer_url : '')) }}
                        {{-- {{ html()->file('trailer_video')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}

                        @error('trailer_video')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>Basic Info</h6>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>Actor and director</h6>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6"></div>
                </div>
            </div>
        </div>

        <div class="d-grid d-sm-flex justify-content-sm-end gap-3">
            <a href="{{ route('backend.tvshows.index') }}" class="btn btn-secondary">Close</a>
            {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        </div>



        <!-- <div class="row">
            {{ html()->hidden('type', $type)->id('type') }}
            {{ html()->hidden('tmdb_id', null)->id('tmdb_id') }}
            <div class="col-6 position-relative">
                {{ html()->label(__('movie.lbl_thumbnail'), 'thumbnail')->class('form-control-label') }}
                <div class="text-center mb-3">
                    <div class="mb-3" id="selectedImageContainerThumbnail">
                        @if(old('thumbnail_url', isset($data) ? $data->thumbnail_url : ''))
                            <img src="{{ old('thumbnail_url', isset($data) ? $data->thumbnail_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <div class="input-group col-sm-6 mb-3">
                        {{ html()->button(__('messages.select'))
                            ->class('input-group-text')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                            ->attribute('data-hidden-input', 'file_url_thumbnail')
                        }}

                        {{ html()->text('thumbnail_input')
                            ->class('form-control')
                            ->placeholder(__('placeholder.lbl_image'))
                            ->attribute('aria-label', 'Thumbnail Image')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                            ->attribute('data-hidden-input', 'file_url_thumbnail')
                        }}
                    </div>

                    {{ html()->hidden('thumbnail_url')->id('file_url_thumbnail')->value(old('thumbnail_url', isset($data) ? $data->thumbnail_url : '')) }}
                </div>
            </div>

            <div class="col-6 position-relative">
                {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-control-label') }}
                <div class="text-center mb-3">
                    <div class="mb-3" id="selectedImageContainerPoster">
                        @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                            <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <div class="input-group col-sm-6 mb-3">
                        {{ html()->button(__('messages.select'))
                            ->class('input-group-text')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerPoster')
                            ->attribute('data-hidden-input', 'file_url_poster')
                        }}

                        {{ html()->text('poster_input')
                            ->class('form-control')
                            ->placeholder(__('placeholder.lbl_image'))
                            ->attribute('aria-label', 'Poster Image')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerPoster')
                            ->attribute('data-hidden-input', 'file_url_poster')
                        }}
                    </div>

                    {{ html()->hidden('poster_url')->id('file_url_poster')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                </div>
            </div>
        </div> -->


        <div class="row">
            <!-- <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_movie_name'))->class('form-control') }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> -->

            <!-- <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description')) }}
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> -->

            <!-- <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_trailer_url_type'), 'type')->class('form-label') }}
                {{ html()->select(
                        'trailer_url_type',
                        $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_type'), ''),
                        old('trailer_url_type', 'Local'), // Set 'Local' as the default value
                    )->class('form-control select2')->id('trailer_url_type') }}
                @error('trailer_url_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> -->

            <!-- <div class="col-sm-6 mb-3 d-none" id="url_input">
                {{ html()->label(__('movie.lbl_trailer_url'), 'trailer_url')->class('form-control-label') }}
                {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                @error('trailer_url')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> -->

            <!-- <div class="col-sm-6 mb-3 d-none" id="url_file_input">
                {{ html()->label(__('movie.lbl_trailer_video'), 'trailer_video')->class('form-control-label') }}

                <div class="mb-3" id="selectedImageContainertailerurl">
                    @if(old('trailer_url', isset($data) ? $data->trailer_url : ''))
                        <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>

                <div class="input-group col-sm-6 mb-3">
                    {{ html()->button(__('messages.select'))
                        ->class('input-group-text')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainertailerurl')
                        ->attribute('data-hidden-input', 'file_url_trailer')
                    }}

                    {{ html()->text('trailer_input')
                        ->class('form-control')
                        ->placeholder(__('placeholder.lbl_select_file'))
                        ->attribute('aria-label', 'Trailer Image')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainertailerurl')
                        ->attribute('data-hidden-input', 'file_url_trailer')
                    }}
                </div>

                {{ html()->hidden('trailer_video')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->trailer_url : '')) }}
                {{-- {{ html()->file('trailer_video')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}

                @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> -->

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_access'), 'movie_access')->class('form-control-label') }}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="movie_access" id="paid" value="paid"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('movie_access') == 'paid' ? 'checked' : '' }} checked>
                    <label class="form-check-label" for="paid">{{__('movie.lbl_paid')}}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="movie_access" id="free" value="free"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('movie_access') == 'free' ? 'checked' : '' }}>
                    <label class="form-check-label" for="free">{{__('movie.lbl_free')}}</label>
                </div>
                @error('movie_access')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="planSelection">
                {{ html()->label(__('movie.lbl_select_plan'), 'type')->class('form-label') }}
                {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend(__('placeholder.lbl_select_plan'), ''), old('plan_id'))->class('form-control select2')->id('plan_id') }}
                @error('plan_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                <div class="form-check form-switch">
                    {{ html()->hidden('status', 0) }}
                    {{ html()->checkbox('status', old('status', 1))->class('form-check-input')->id('status')->value(1) }}
                </div>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="row">

            <div class="col-sm-12 mb-3">
                <h5>{{ __('movie.lbl_basic_info') }}</h5>
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_language') . '<span class="text-danger">*</span>', 'language')->class('form-label') }}
                {{ html()->select('language', $movie_language->pluck('name', 'value')->prepend(__('placeholder.lbl_select_language'), ''), old('language'))->class('form-control select2')->id('language') }}
                @error('language')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_genres') . '<span class="text-danger">*</span>', 'genres')->class('form-label') }}
                {{ html()->select('genres[]', $genres->pluck('name', 'id')->prepend(__('placeholder.lbl_select_genre'), ''), old('genres'))->class('form-control select2')->id('genres')->multiple() }}
                @error('genres')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_imdb_rating'), 'IMDb_rating')->class('form-label') }}
                {{ html()->select('IMDb_rating', $numberOptions->prepend(__('placeholder.lbl_select_imdb_rating'), ''), old('IMDb_rating'))->class('form-control select2')->id('IMDb_rating') }}
                @error('IMDb_rating')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>



            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_content_rating') . '<span class="text-danger">*</span>', 'content_rating')->class('form-label') }}

                {{ html()->text('content_rating')->attribute('value', old('content_rating'))->placeholder(__('placeholder.lbl_content_rating'))->class('form-control') }}

                @error('content_rating')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_duration') , 'duration')->class('form-control-label') }}
                {{ html()->time('duration')->attribute('value', old('duration'))->placeholder(__('placeholder.lbl_duration'))->class('form-control') }}
                @error('time')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_release_date'), 'release_date')->class('form-control-label') }}
                {{ html()->date('release_date')->attribute('value', old('release_date'))->placeholder(__('placeholder.lbl_release_date'))->class('form-control') }}
                @error('release_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_age_restricted'), 'is_restricted')->class('form-label') }}
                <div class="form-check form-switch">
                    {{ html()->hidden('is_restricted', 0) }}
                    {{ html()->checkbox('is_restricted', old('is_restricted', false))->class('form-check-input')->id('is_restricted') }}
                </div>
                @error('is_restricted')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row">

            <div class="col-sm-12 mb-3">
                <h5>{{ __('movie.lbl_actor_director') }}</h5>
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_actors') . '<span class="text-danger">*</span>', 'actors')->class('form-label') }}
                {{ html()->select('actors[]', $actors->pluck('name', 'id')->prepend(__('placeholder.lbl_select_actor'), ''), old('actors'))->class('form-control select2')->id('actors')->multiple() }}
                @error('actors')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_directors') . '<span class="text-danger">*</span>', 'directors')->class('form-label') }}
                {{ html()->select('directors[]', $directors->pluck('name', 'id')->prepend(__('placeholder.lbl_select_director'), ''), old('directors'))->class('form-control select2')->id('directors')->multiple() }}
                @error('directors')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>


        <a href="{{ route('backend.tvshows.index') }}" class="btn btn-secondary">{{__('messages.close')}}</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
    {{ html()->form()->close() }}

    @include('components.media-modal')
@endsection
    @push('after-scripts')
        <script>
           
            document.addEventListener('DOMContentLoaded', function() {

                function handleTrailerUrlTypeChange(selectedValue) {
                    var FileInput = document.getElementById('url_file_input');
                    var URLInput = document.getElementById('url_input');

                    if (selectedValue === 'Local') {
                        FileInput.classList.remove('d-none');
                        URLInput.classList.add('d-none');
                    } else if (selectedValue === 'URL' || selectedValue === 'YouTube' || selectedValue === 'HLS' ||
                        selectedValue === 'Vimeo') {
                        URLInput.classList.remove('d-none');
                        FileInput.classList.add('d-none');
                    } else {
                        FileInput.classList.add('d-none');
                        URLInput.classList.add('d-none');
                    }
                }

                var initialSelectedValue = document.getElementById('trailer_url_type').value;
                handleTrailerUrlTypeChange(initialSelectedValue);
                $('#trailer_url_type').change(function() {
                    var selectedValue = $(this).val();
                    handleTrailerUrlTypeChange(selectedValue);
                });
            });

            function showPlanSelection(show) {
                var planSelection = document.getElementById('planSelection');
                if (show) {
                    planSelection.classList.remove('d-none');
                } else {
                    planSelection.classList.add('d-none');
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                var movieAccessPaid = document.getElementById('paid');
                if (movieAccessPaid.checked) {
                    showPlanSelection(true);
                }
            });

        

            /////////////////////////////////  Import Moive //////////////////////////////////////////////////////////////////////

   $(document).ready(function() {

     $('#import_tvshow_id').on('click', function(e) {
        e.preventDefault();

        var tvshowID = $('#tvshow_id').val();
        $('#tvshow_id_error').text('');
        $('#error_message').text('');

        var baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
        var url = baseUrl + '/app/tvshows/import-tvshow/' + tvshowID;

        if (!tvshowID) {
            $('#tvshow_id_error').text('TV show ID is required.');
            return;
        }

        $('#loader').show();
        $('#import_tvshow_id').hide();

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {

                $('#loader').hide();
                $('#import_tvshow_id').show();

                if(response.success){

                 var data = response.data;

                 $('#tmdb_id').val(data.id);
                 $('#selectedImage').attr('src', data.thumbnail_url).show();
                 $('#selectedPosterImage').attr('src', data.poster_url).show();
                 $('#name').val(data.name);
                 $('#description').val(data.description);
                 $('#trailer_url_type').val(data.trailer_url_type).trigger('change');
                 $('#trailer_url').val(data.trailer_url);
                 $('#release_date').val(data.release_date);
                 $('#duration').val(data.duration);
                 $('#thumbnail_url').val(data.thumbnail_url);
                 $('#poster_url').val(data.poster_url);

                 var all_genres = data.all_genres;

                 $('#genres').empty().append('<option value="">Select Genre</option>');
                 $.each(all_genres, function(index, genre) {

                     $('#genres').append('<option value="' + genre.id + '">' + genre.name + '</option>');
                 });
                 $('#genres').val(data.genres).trigger('change');

                 var all_languages = data.all_language;

                 $('#language').empty().append('<option value="">Select Language</option>');
                 $.each(all_languages, function(index, language) {
                     $('#language').append('<option value="' + language.value + '">' + language.name + '</option>');
                 });
                 $('#language').val(data.language.toLowerCase()).trigger('change');


                 var all_actors = data.all_actors;
                 $('#actors').empty().append('<option value="">Select Actors</option>');
                 $.each(all_actors, function(index, actor) {
                     $('#actors').append('<option value="' + actor.id + '">' + actor.name + '</option>');
                 });
                 $('#actors').val(data.actors).trigger('change');


                 var all_directors = data.all_directors;
                 $('#directors').empty().append('<option value="">Select Directors</option>');
                 $.each(all_directors, function(index, director) {
                     $('#directors').append('<option value="' + director.id + '">' + director.name + '</option>');
                 });
                 $('#directors').val(data.directors).trigger('change');


                 if(data.is_restricted) {
                        $('#is_restricted').prop('checked', true).val(1);
                    } else {
                        $('#is_restricted').prop('checked', false).val(0);
                    }

                    if(data.thumbnail_url){

                        $('#selectedImage').attr('src', data.thumbnail_url).show();
                    }

                    if(data.poster_url) {

                        $('#selectedPosterImage').attr('src', data.poster_url).show();
                    }
                    if (data.movie_access === 'paid') {
                      document.getElementById('paid').checked = true;
                      showPlanSelection(true);
                    } else {

                      document.getElementById('free').checked = true;
                      showPlanSelection(false);
                    }

                } else {
                    $('#error_message').text(response.message || 'Failed to import movie details.');
                }
            },
            error: function(xhr) {
                console.log(xhr);
                $('#loader').hide();
                $('#import_movie').show();
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    $('#error_message').text(xhr.responseJSON.message);
                } else {
                    $('#error_message').text('An error occurred while fetching the movie details.');
                }
            }
          });
        });
     });
        </script>

        <style>
            .position-relative {
                position: relative;
            }

            .position-absolute {
                position: absolute;
            }

            .close-icon {
                top: -13px;
                left: 54px;
                background: rgba(255, 0, 0, 0.6);
                border: none;
                border-radius: 50%;
                color: white;
                width: 25px;
                height: 25px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 16px;
                line-height: 25px;
            }
        </style>
    @endpush
