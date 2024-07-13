@extends('backend.layouts.app')

@section('content')
    <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
        <div class="flex-grow-1">
            {{ html()->label(__('movie.lbl_movie_id') . '<span class="text-danger">*</span>', 'movie_id')->class('form-label') }}
            {{ html()->text('movie_id')->attribute('value', old('movie_id'))->placeholder(__('placeholder.lbl_movie_id'))->class('form-control') }}
            <span class="text-danger" id="movie_id_error"></span>
        </div>

        <div>
            <div id="loader" style="display: none;">
                <button class="btn btn-md btn-primary float-right">Loading...</button>
            </div>
            <button class="btn btn-md btn-primary float-right" id="import_movie">Import</button>
        </div>
    </div>

    <form method="POST" id="form" class="mb-5" action="{{ route('backend.entertainments.store') }}"
        enctype="multipart/form-data">
        @csrf

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>About Movie</h6>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        {{ html()->hidden('type', $type)->id('type') }}
                        {{ html()->hidden('is_import', 0)->id('is_import') }}
                        {{ html()->label(__('movie.lbl_thumbnail'), 'thumbnail')->class('form-label form-control-label') }}
                        <div class="input-group btn-file-upload">
                            {{ html()->button('Select')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerThumbnail')->attribute('data-hidden-input', 'thumbnail_url')->id('iq-image-url') }}
                            {{ html()->text('thumbnail_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Thumbnail Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerThumbnail')->attribute('data-hidden-input', 'thumbnail_url') }}
                        </div>
                        <div class="mt-3" id="selectedImageContainerThumbnail">
                            @if (old('thumbnail_url', isset($data) ? $data->thumbnail_url : ''))
                                <img id="selectedImage"
                                    src="{{ old('thumbnail_url', isset($data) ? $data->thumbnail_url : '') }}"
                                    alt="feature-image" class="img-fluid mb-2 avatar-80" />
                            @endif
                        </div>
                        {{ html()->hidden('thumbnail_url')->id('thumbnail_url')->value(old('thumbnail_url', isset($data) ? $data->thumbnail_url : ''))->attribute('data-validation', 'iq_') }}
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-label form-control-label') }}

                        <div class="input-group btn-file-upload">
                            {{ html()->button('Select')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerPoster')->attribute('data-hidden-input', 'poster_url') }}

                            {{ html()->text('poster_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Poster Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerPoster')->attribute('data-hidden-input', 'poster_url') }}

                            {{ html()->hidden('poster_url')->id('poster_url')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                        </div>
                        <div class="mt-3" id="selectedImageContainerPoster">
                            <img id="selectedPosterImage"
                                src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" alt="feature-image"
                                class="img-fluid mb-2 avatar-80 "
                                style="{{ old('poster_url', isset($data) ? $data->poster_url : '') ? '' : 'display:none;' }}" />
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-label form-control-label') }}
                        {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_movie_name'))->class('form-control') }}
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="col-lg-12 mb-3">
                        {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                        {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description'))->rows(5) }}
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="col-lg-4 mb-3">
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
                    <div class="col-lg-4 mb-3">
                        <div id="url_input">
                            {{ html()->label(__('movie.lbl_trailer_url'), 'trailer_url')->class('form-label form-control-label') }}
                            {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                            @error('trailer_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div id="url_file_input">
                            {{ html()->label(__('movie.lbl_trailer_video'), 'trailer_video')->class('form-label form-control-label') }}

                            <div class="input-group btn-file-upload">
                                {{ html()->button('Select')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainertailerurl')->attribute('data-hidden-input', 'file_url_trailer') }}

                                {{ html()->text('trailer_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Trailer Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainertailerurl')->attribute('data-hidden-input', 'file_url_trailer') }}
                            </div>

                            <div class="mt-3" id="selectedImageContainertailerurl">
                                @if (old('trailer_url', isset($data) ? $data->trailer_url : ''))
                                    <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}"
                                        class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                @endif
                            </div>

                            {{ html()->hidden('trailer_video')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->poster_url : '')) }}

                            @error('trailer_video')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_movie_access'), 'movie_access')->class('form-label form-control-label') }}
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check form-check-inline form-control px-5">
                                <input class="form-check-input" type="radio" name="movie_access" id="paid"
                                    value="paid" onchange="showPlanSelection(this.value === 'paid')"
                                    {{ old('movie_access') == 'paid' ? 'checked' : '' }} checked>
                                <label class="form-check-label" for="paid">Paid</label>
                            </div>
                            <div class="form-check form-check-inline form-control px-5">
                                <input class="form-check-input" type="radio" name="movie_access" id="free"
                                    value="free" onchange="showPlanSelection(this.value === 'paid')"
                                    {{ old('movie_access') == 'free' ? 'checked' : '' }}>
                                <label class="form-check-label" for="free">Free</label>
                            </div>
                        </div>
                        @error('movie_access')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4 mb-3" id="planSelection">
                        {{ html()->label(__('movie.lbl_select_plan'), 'type')->class('form-label') }}
                        {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend('Select Plan', ''), old('plan_id'))->class('form-control select2')->id('plan_id') }}
                        @error('plan_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                        <div class="d-flex justify-content-between align-items-center form-control">
                            {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label mb-0 text-body') }}
                            <div class="form-check form-switch">
                                {{ html()->hidden('status', 0) }}
                                {{ html()->checkbox('status', old('status', 1))->class('form-check-input')->id('status')->value(1) }}
                            </div>
                        </div>
                        @error('status')
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
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_movie_language') . '<span class="text-danger">*</span>', 'language')->class('form-label') }}
                        {{ html()->select('language', $movie_language->pluck('name', 'value')->prepend('Select Language', ''), old('language'))->class('form-control select2')->id('language') }}
                        @error('language')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_genres') . '<span class="text-danger">*</span>', 'genres')->class('form-label') }}
                        {{ html()->select('genres[]', $genres->pluck('name', 'id'), old('genres'))->class('form-control select2')->id('genres')->multiple() }}
                        @error('genres')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_imdb_rating'), 'IMDb_rating')->class('form-label') }}
                        {{ html()->select('IMDb_rating', $numberOptions->prepend('Select IMDb Rating', ''), old('IMDb_rating'))->class('form-control select2')->id('IMDb_rating') }}
                        @error('IMDb_rating')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_content_rating'), 'content_rating')->class('form-label') }}

                        {{ html()->text('content_rating')->attribute('value', old('content_rating'))->placeholder(__('placeholder.lbl_content_rating'))->class('form-control') }}

                        @error('content_rating')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_duration') . ' <span class="text-danger">*</span>', 'duration')->class('form-control-label') }}
                        {{ html()->time('duration')->attribute('value', old('duration'))->placeholder(__('placeholder.lbl_duration'))->class('form-control') }}
                        @error('time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_release_date'), 'release_date')->class('form-label form-control-label') }}
                        {{ html()->date('release_date')->attribute('value', old('release_date'))->placeholder(__('placeholder.lbl_release_date'))->class('form-control') }}
                        @error('release_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-4 mb-3">
                        {{ html()->label(__('movie.lbl_age_restricted'), 'is_restricted')->class('form-label') }}
                        <div class="d-flex justify-content-between align-items-center form-control">
                            {{ html()->label(__('movie.lbl_age_restricted'), 'is_restricted')->class('form-label mb-0 text-body') }}
                            <div class="form-check form-switch">
                                {{ html()->hidden('is_restricted', 0) }}
                                {{ html()->checkbox('is_restricted', old('is_restricted', false))->class('form-check-input')->id('is_restricted') }}
                            </div>
                        </div>
                        @error('is_restricted')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>Actor and director</h6>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        {{ html()->label(__('movie.lbl_actors') . '<span class="text-danger">*</span>', 'actors')->class('form-label') }}
                        {{ html()->select('actors[]', $actors->pluck('name', 'id'), old('actors'))->class('form-control select2')->id('actors')->multiple() }}
                        @error('actors')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-6 mb-3">
                        {{ html()->label(__('movie.lbl_directors') . '<span class="text-danger">*</span>', 'directors')->class('form-label') }}
                        {{ html()->select('directors[]', $directors->pluck('name', 'id'), old('directors'))->class('form-control select2')->id('directors')->multiple() }}
                        @error('directors')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>Video Info</h6>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        {{ html()->label(__('movie.lbl_video_upload_type'). '<span class="text-danger">*</span>', 'video_upload_type')->class('form-label') }}
                        {{ html()->select(
                                'video_upload_type',
                                $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                                old('video_upload_type', 'Local'),
                            )->class('form-control select2')->id('video_upload_type') }}
                        @error('video_upload_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="mb-3 d-none" id="video_url_input_section">
                            {{ html()->label(__('movie.video_url_input') . '<span class="text-danger">*</span>', 'video_url_input')->class('form-control-label') }}
                            {{ html()->text('video_url_input')->attribute('value', old('video_url_input'))->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                            @error('video_url_input')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 d-none" id="video_file_input">
                            {{ html()->label(__('movie.video_file_input') . '<span class="text-danger">*</span>', 'video_file')->class('form-label form-control-label') }}

                            <div class="input-group btn-file-upload mb-3">
                                {{ html()->button('Select')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideourl')->attribute('data-hidden-input', 'file_url_video') }}

                                {{ html()->text('video_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Video Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideourl')->attribute('data-hidden-input', 'file_url_video') }}
                            </div>

                            <div class="mt-3" id="selectedImageContainerVideourl">
                                @if (old('video_url_input', isset($data) ? $data->video_url_input : ''))
                                    <img src="{{ old('video_url_input', isset($data) ? $data->video_url_input : '') }}"
                                        class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                @endif
                            </div>

                        

                            {{ html()->hidden('video_url_input')->id('file_url_video')->value(old('video_url_input', isset($data) ? $data->poster_url : '')) }}

                            {{-- {{ html()->file('video_file')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}

                            @error('video_url_input')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5 pt-4 mb-3">
            <h6>{{ __('movie.lbl_quality_info') }}</h6>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-ld-12">
                        <div class="d-flex align-items-center justify-content-between form-control">
                            <label for="enable_quality" class="form-label">{{ __('movie.lbl_enable_quality') }}</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="enable_quality" value="0">
                                <input type="checkbox" name="enable_quality" id="enable_quality"
                                    class="form-check-input" value="1"
                                    {{ old('enable_quality', false) ? 'checked' : '' }} onchange="toggleQualitySection()">
                            </div>
                        </div>
                        @error('enable_quality')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div id="enable_quality_section" class="enable_quality_section d-none">
                            <div id="video-inputs-container-parent">
                                <div class="row video-inputs-container">
                                    <div class="col-md-4 mb-3">
                                        {{ html()->label(__('movie.lbl_video_upload_type'), 'video_quality_type')->class('form-label') }}
                                        {{ html()->select(
                                                'video_quality_type[]',
                                                $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                                                old('video_quality_type', 'Local'),
                                            )->class('form-control select2 video_quality_type') }}
                                        @error('video_quality_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3 video-input">
                                        {{ html()->label(__('movie.lbl_video_quality'), 'video_quality')->class('form-label') }}
                                        {{ html()->select('video_quality[]', $video_quality->pluck('name', 'value')->prepend('Select Quality', ''))->class('form-control select2 video_quality') }}
                                    </div>
                                    <div class="col-md-4 mb-3 d-none video-url-input quality_video_input">
                                        {{ html()->label(__('movie.video_url_input'), 'quality_video_url_input')->class('form-label form-control-label') }}
                                        {{ html()->text('quality_video_url_input[]')->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                                    </div>
                                    <div class="col-md-4 mb-3 d-none video-file-input quality_video_file_input">
                                        {{ html()->label(__('movie.video_file_input'), 'quality_video')->class('form-label form-control-label') }}
                                        <div class="input-group btn-file-upload mb-3">
                                            {{ html()->button('Select')->class('input-group-text form-control')->type('button')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')->attribute('data-hidden-input', 'file_url_videoquality') }}
                                            {{ html()->text('videoquality_input')->class('form-control')->placeholder('Select Image')->attribute('aria-label', 'Video Quality Image')->attribute('data-bs-toggle', 'modal')->attribute('data-bs-target', '#exampleModal')->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')->attribute('data-hidden-input', 'file_url_videoquality') }}
                                        </div>
                                        <div class="mb-3" id="selectedImageContainerVideoqualityurl">
                                            @if (old('video_quality_url', isset($data) ? $data->video_quality_url : ''))
                                                <img src="{{ old('video_quality_url', isset($data) ? $data->video_quality_url : '') }}"
                                                    class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                                            @endif
                                        </div>
                                        {{ html()->hidden('quality_video[]')->id('file_url_videoquality')->value(old('video_quality_url', isset($data) ? $data->poster_url : ''))->attribute('data-validation', 'iq_video_quality') }}
                                        @error('quality_video')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <div class="text-end">
                                            <button type="button"
                                                class="btn btn-link text-danger p-0 remove-video-input d-none"><u>Remove</u></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <a id="add_more_video" class="btn btn-link p-0"><u>Add More</u></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-grid d-sm-flex justify-content-sm-end gap-3">
            <a href="{{ route('backend.movies.index') }}" class="btn btn-secondary">Close</a>
            {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        </div>

        {{ html()->form()->close() }}

        {{--
        <div class="row">
                {{ html()->hidden('type', $type)->id('type') }}

                {{ html()->hidden('is_import', 0)->id('is_import') }}

                {{ html()->label(__('movie.lbl_thumbnail'), 'thumbnail')->class('form-control-label') }}

                <div class="mb-3" id="selectedImageContainerThumbnail">
                    <img id="selectedImage" src="{{ old('thumbnail_url', isset($data) ? $data->thumbnail_url : '') }}"
                    alt="feature-image" class="img-fluid mb-2 avatar-80 "
                    style="{{ old('thumbnail_url', isset($data) ? $data->thumbnail_url : '') ? '' : 'display:none;' }}" />
                </div>

                <div class="input-group col-sm-6 mb-3">
                    {{ html()->button('Select')
                        ->class('input-group-text')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                        ->attribute('data-hidden-input', 'thumbnail_url')
                    }}

                    {{ html()->text('thumbnail_input')
                        ->class('form-control')
                        ->placeholder('Select Image')
                        ->attribute('aria-label', 'Thumbnail Image')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                        ->attribute('data-hidden-input', 'thumbnail_url')
                    }}
                </div>

                {{ html()->hidden('thumbnail_url')->id('thumbnail_url')->value(old('thumbnail_url', isset($data) ? $data->thumbnail_url : '')) }}



                <div class="col-6 position-relative">
                    {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-control-label') }}
                    <div class="mb-3" id="selectedImageContainerPoster">
                        <img id="selectedPosterImage" src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}"
                            alt="feature-image" class="img-fluid mb-2 avatar-80 "
                            style="{{ old('poster_url', isset($data) ? $data->poster_url : '') ? '' : 'display:none;' }}" />
                    </div>

                    <div class="input-group col-sm-6 mb-3">
                        {{ html()->button('Select')
                            ->class('input-group-text')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerPoster')
                            ->attribute('data-hidden-input', 'poster_url')
                        }}

                        {{ html()->text('poster_input')
                            ->class('form-control')
                            ->placeholder('Select Image')
                            ->attribute('aria-label', 'Poster Image')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainerPoster')
                            ->attribute('data-hidden-input', 'poster_url')
                        }}
                    </div>

                    {{ html()->hidden('poster_url')->id('poster_url')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                </div>
            </div>
        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_movie_name'))->class('form-control') }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description')) }}
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
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

            <div class="col-sm-6 mb-3 d-none" id="url_input">
                {{ html()->label(__('movie.lbl_trailer_url'), 'trailer_url')->class('form-control-label') }}
                {{ html()->text('trailer_url')->attribute('value', old('trailer_url'))->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
                @error('trailer_url')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="url_file_input">
                {{ html()->label(__('movie.lbl_trailer_video'), 'trailer_video')->class('form-control-label') }}

                <div class="mb-3" id="selectedImageContainertailerurl">
                    {{-- @if (old('trailer_url', isset($data) ? $data->trailer_url : ''))
                        <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif --}}
        {{-- </div>

                <div class="input-group col-sm-6 mb-3">
                    {{ html()->button('Select')
                        ->class('input-group-text')
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

                {{ html()->hidden('trailer_video')->id('file_url_trailer')->value(old('trailer_url', isset($data) ? $data->poster_url : '')) }}

                @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_access') , 'movie_access')->class('form-control-label') }}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="movie_access" id="paid" value="paid"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('movie_access') == 'paid' ? 'checked' : '' }} checked>
                    <label class="form-check-label" for="paid">Paid</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="movie_access" id="free" value="free"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('movie_access') == 'free' ? 'checked' : '' }}>
                    <label class="form-check-label" for="free">Free</label>
                </div>
                @error('movie_access')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="planSelection">
                {{ html()->label(__('movie.lbl_select_plan'), 'type')->class('form-label') }}
                {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend('Select Plan', ''), old('plan_id'))->class('form-control select2')->id('plan_id') }}
                @error('plan_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                <div class="form-check form-switch">
                    {{ html()->hidden('status', 0) }}
                    {{
                        html()->checkbox('status', old('status', 1))
                            ->class('form-check-input')
                            ->id('status')
                            ->value(1)
                    }}
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
                {{ html()->select('language', $movie_language->pluck('name', 'value')->prepend('Select Language', ''), old('language'))->class('form-control select2')->id('language') }}
                @error('language')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_genres') . '<span class="text-danger">*</span>', 'genres')->class('form-label') }}
                {{ html()->select('genres[]', $genres->pluck('name', 'id'), old('genres'))->class('form-control select2')->id('genres')->multiple() }}
                @error('genres')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_imdb_rating'), 'IMDb_rating')->class('form-label') }}
                {{ html()->select('IMDb_rating', $numberOptions->prepend('Select IMDb Rating', ''), old('IMDb_rating'))->class('form-control select2')->id('IMDb_rating') }}
                @error('IMDb_rating')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>



            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_content_rating') , 'content_rating')->class('form-label') }}

                {{ html()->text('content_rating')->attribute('value', old('content_rating'))->placeholder(__('placeholder.lbl_content_rating'))->class('form-control') }}

                @error('content_rating')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_duration') . ' <span class="text-danger">*</span>', 'duration')->class('form-control-label') }}
                {{ html()->time('duration')->attribute('value', old('duration'))->placeholder(__('placeholder.lbl_duration'))->class('form-control') }}
                @error('time')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_release_date') , 'release_date')->class('form-control-label') }}
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
                {{ html()->select('actors[]', $actors->pluck('name', 'id'), old('actors'))->class('form-control select2')->id('actors')->multiple() }}
                @error('actors')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_directors') . '<span class="text-danger">*</span>', 'directors')->class('form-label') }}
                {{ html()->select('directors[]', $directors->pluck('name', 'id'), old('directors'))->class('form-control select2')->id('directors')->multiple() }}
                @error('directors')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row">

            <div class="col-sm-12 mb-3">
                <h5>{{ __('movie.lbl_video_info') }}</h5>
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_video_upload_type'), 'video_upload_type')->class('form-label') }}
                {{ html()->select(
                        'video_upload_type',
                        $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                        old('video_upload_type', 'Local'),
                    )->class('form-control select2')->id('video_upload_type') }}
                @error('video_upload_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="video_url_input">
                {{ html()->label(__('movie.video_url_input'), 'video_url_input')->class('form-control-label') }}
                {{ html()->text('video_url_input')->attribute('value', old('video_url_input'))->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                @error('video_url_input')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="video_file_input">
                {{ html()->label(__('movie.video_file_input'), 'video_file')->class('form-control-label') }}

                <div class="mb-3" id="selectedImageContainerVideourl">
                    @if (old('video_url_input', isset($data) ? $data->video_url_input : ''))
                        <img src="{{ old('video_url_input', isset($data) ? $data->video_url_input : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>

                <div class="input-group col-sm-6 mb-3">
                    {{ html()->button('Select')
                        ->class('input-group-text')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainerVideourl')
                        ->attribute('data-hidden-input', 'file_url_video')
                    }}

                    {{ html()->text('video_input')
                        ->class('form-control')
                        ->placeholder('Select Image')
                        ->attribute('aria-label', 'Video Image')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainerVideourl')
                        ->attribute('data-hidden-input', 'file_url_video')
                    }}
                </div>

                {{ html()->hidden('video_url_input')->id('file_url_video')->value(old('video_url_input', isset($data) ? $data->poster_url : '')) }}



                @error('video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="col-sm-12 mb-3 mt-5">
            <h5>{{ __('movie.lbl_quality_info') }}</h5>
        </div>
        <div class="col-sm-12 mb-3">
            <label for="enable_quality" class="form-label">{{ __('movie.lbl_enable_quality') }}</label>
            <div class="form-check form-switch">
                <input type="hidden" name="enable_quality" value="0">
                <input type="checkbox" name="enable_quality" id="enable_quality" class="form-check-input" value="1" {{ old('enable_quality', false) ? 'checked' : '' }} onchange="toggleQualitySection()">
            </div>
            @error('enable_quality')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div id="enable_quality_section" class="enable_quality_section d-none">
            <div id="video-inputs-container-parent">
                <div class="row video-inputs-container">

                    <div class="col-sm-3 mb-3">
                        {{ html()->label(__('movie.lbl_video_upload_type'), 'video_quality_type')->class('form-label') }}
                        {{ html()->select(
                                'video_quality_type[]',
                                $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                                old('video_quality_type', 'Local'),
                            )->class('form-control select2 video_quality_type') }}
                        @error('video_quality_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-3 mb-3 video-input">
                        {{ html()->label(__('movie.lbl_video_quality'), 'video_quality')->class('form-label') }}
                        {{ html()->select(
                                'video_quality[]',
                                $video_quality->pluck('name', 'value')->prepend('Select Quality', '')
                            )->class('form-control select2 video_quality') }}
                    </div>

                    <div class="col-sm-3 mb-3 d-none video-url-input quality_video_input">
                        {{ html()->label(__('movie.video_url_input'), 'quality_video_url_input')->class('form-control-label') }}
                        {{ html()->text('quality_video_url_input[]')->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                    </div>

                    <div class="col-sm-3 mb-3 d-none video-file-input quality_video_file_input">
                        {{ html()->label(__('movie.video_file_input'), 'quality_video')->class('form-control-label') }}

                        <div class="mb-3" id="selectedImageContainerVideoqualityurl">
                            @if (old('video_quality_url', isset($data) ? $data->video_quality_url : ''))
                                <img src="{{ old('video_quality_url', isset($data) ? $data->video_quality_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                            @endif
                        </div>

                        <div class="input-group col-sm-6 mb-3">
                            {{ html()->button('Select')
                                ->class('input-group-text')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')
                                ->attribute('data-hidden-input', 'file_url_videoquality')
                            }}

                            {{ html()->text('videoquality_input')
                                ->class('form-control')
                                ->placeholder('Select Image')
                                ->attribute('aria-label', 'Video Quality Image')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')
                                ->attribute('data-hidden-input', 'file_url_videoquality')
                            }}
                        </div>

                        {{ html()->hidden('quality_video[]')->id('file_url_videoquality')->value(old('video_quality_url', isset($data) ? $data->poster_url : '')) }}


                        @error('quality_video')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-12 mb-3">
                        <button type="button" class="btn btn-danger remove-video-input d-none">Remove</button>
                    </div>
                </div>
            </div>
            <a id="add_more_video" class="btn btn-secondary">Add More</a>
        </div>

        <a href="{{ route('backend.movies.index') }}" class="btn btn-secondary">Close</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }} --}}

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


            function toggleQualitySection() {

                var enableQualityCheckbox = document.getElementById('enable_quality');
                var enableQualitySection = document.getElementById('enable_quality_section');

                if (enableQualityCheckbox.checked) {

                    enableQualitySection.classList.remove('d-none');

                } else {

                    enableQualitySection.classList.add('d-none');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                toggleQualitySection();
            });


            document.addEventListener('DOMContentLoaded', function() {

                function handleVideoUrlTypeChange(selectedtypeValue) {
                    var VideoFileInput = document.getElementById('video_file_input');
                    var VideoURLInput = document.getElementById('video_url_input_section');

                    if (selectedtypeValue === 'Local') {
                        VideoFileInput.classList.remove('d-none');
                        VideoURLInput.classList.add('d-none');

                    } else if (selectedtypeValue === 'URL' || selectedtypeValue === 'YouTube' || selectedtypeValue ===
                        'HLS' || selectedtypeValue === 'Vimeo') {
                        VideoURLInput.classList.remove('d-none');
                        VideoFileInput.classList.add('d-none');

                    } else {
                        VideoFileInput.classList.add('d-none');
                        VideoURLInput.classList.add('d-none');

                    }
                }

                var initialSelectedValue = document.getElementById('video_upload_type').value;
                handleVideoUrlTypeChange(initialSelectedValue);
                $('#video_upload_type').change(function() {
                    var selectedtypeValue = $(this).val();
                    handleVideoUrlTypeChange(selectedtypeValue);
                });
            });


            $(document).ready(function() {
                function handleVideoQualityTypeChange(section) {
                    section.find('.video_quality_type').on('change', function() {
                        var selectedType = $(this).val();
                        var QualityVideoFileInput = section.find('.quality_video_file_input');
                        var QualityVideoURLInput = section.find('.quality_video_input');

                        if (selectedType === 'Local') {
                            QualityVideoFileInput.removeClass('d-none');
                            QualityVideoURLInput.addClass('d-none');
                        } else {
                            QualityVideoFileInput.addClass('d-none');
                            QualityVideoURLInput.removeClass('d-none');
                        }
                    }).trigger('change');
                }

                function initializeSelect2(section) {
                    section.find('select.select2').each(function() {
                        $(this).select2({
                            width: '100%'
                        });
                    });
                }

                function destroySelect2(section) {
                    section.find('select.select2').each(function() {
                        if ($(this).data('select2')) {
                            $(this).select2('destroy');
                        }
                    });
                }


                $(document).on('click', '.remove-video-input', function() {
                    $(this).closest('.video-inputs-container').remove();
                });

                // Initial call for the first section
                handleVideoQualityTypeChange($('.video-inputs-container').first());
                initializeSelect2($('.video-inputs-container').first());
            });



            /////////////////////////////////  Import Moive //////////////////////////////////////////////////////////////////////

            $(document).ready(function() {
                $('#import_movie').on('click', function(e) {
                    e.preventDefault();

                    var movieId = $('#movie_id').val();
                    $('#movie_id_error').text('');
                    $('#error_message').text('');

                    if (!movieId) {
                        $('#movie_id_error').text('Movie ID is required.');
                        return;
                    }

                    var baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
                    var url = baseUrl + '/app/movies/import-movie/' + movieId;

                    $('#loader').show();
                    $('#import_movie').hide();

                    $.ajax({
                        url: '{{ route('backend.movies.import-movie', ':id') }}'.replace(':id',
                            movieId),
                        type: 'GET',
                        success: function(response) {

                            $('#loader').hide();
                            $('#import_movie').show();

                            if (response.success) {

                                var data = response.data;


                                $('#is_import').val(1);
                                console.log($('#selectedImageTumbnail'))
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

                                $('#video_upload_type').val(data.video_url_type).trigger('change');
                                $('#video_url_input').val(data.video_url);
                                $('#file_url_video').val(data.video_url);


                                var all_genres = data.all_genres;
                                $('#genres').empty().append(
                                    '<option value="">Select Genre</option>');
                                $.each(all_genres, function(index, genre) {
                                    $('#genres').append('<option value="' + genre.id +
                                        '">' + genre.name + '</option>');
                                });
                                $('#genres').val(data.genres).trigger('change');


                                var all_languages = data.all_language;
                                $('#language').empty().append(
                                    '<option value="">Select Language</option>');
                                $.each(all_languages, function(index, language) {
                                    $('#language').append('<option value="' + language
                                        .value + '">' + language.name + '</option>');
                                });
                                $('#language').val(data.language.toLowerCase()).trigger('change');


                                var all_actors = data.all_actors;
                                $('#actors').empty().append(
                                    '<option value="">Select Actors</option>');
                                $.each(all_actors, function(index, actor) {
                                    $('#actors').append('<option value="' + actor.id +
                                        '">' + actor.name + '</option>');
                                });
                                $('#actors').val(data.actors).trigger('change');


                                var all_directors = data.all_directors;
                                $('#directors').empty().append(
                                    '<option value="">Select Directors</option>');
                                $.each(all_directors, function(index, director) {
                                    $('#directors').append('<option value="' + director.id +
                                        '">' + director.name + '</option>');
                                });
                                $('#directors').val(data.directors).trigger('change');


                                if (data.is_restricted) {
                                    $('#is_restricted').prop('checked', true).val(1);
                                } else {
                                    $('#is_restricted').prop('checked', false).val(0);
                                }

                                if (data.thumbnail_url) {

                                    $('#selectedImage').attr('src', data.thumbnail_url).show();
                                }

                                if (data.poster_url) {

                                    $('#selectedPosterImage').attr('src', data.poster_url).show();
                                }
                                if (data.movie_access === 'paid') {
                                    document.getElementById('paid').checked = true;
                                    showPlanSelection(true);
                                } else {

                                    document.getElementById('free').checked = true;
                                    showPlanSelection(false);
                                }

                                if (data.enable_quality === true) {

                                    $('#enable_quality').prop('checked', true).val(1);
                                } else {

                                    $('#enable_quality').prop('checked', false).val(0);
                                }

                                toggleQualitySection()

                                if (data.enable_quality === true) {


                                    const container = document.getElementById(
                                        'video-inputs-container-parent');
                                    container.innerHTML = ''; // Clear existing content

                                    data.entertainmentStreamContentMappings.forEach((video,
                                        index) => {
                                            const videoInputContainer = document.createElement(
                                                'div');
                                            videoInputContainer.className =
                                                'row video-inputs-container';

                                            videoInputContainer.innerHTML = `
          <div class="col-sm-3 mb-3">
            <label class="form-label" for="video_quality_type_${index}">Video Upload Type</label>
            <select name="video_quality_type[]" id="video_quality_type_${index}" class="form-control select2 video_quality_type">
              <option value="YouTube" ${video.video_quality_type === 'YouTube' ? 'selected' : ''}>YouTube</option>
              <option value="Local" ${video.video_quality_type === 'Local' ? 'selected' : ''}>Local</option>
            </select>
          </div>

          <div class="col-sm-3 mb-3 video-input">
            <label class="form-label" for="video_quality_${index}">Video Quality</label>
            <select name="video_quality[]" id="video_quality_${index}" class="form-control select2 video_quality">
              <option value="1080p" ${video.video_quality === 1080 ? 'selected' : ''}>1080p</option>
              <option value="720p" ${video.video_quality === 720 ? 'selected' : ''}>720p</option>
              <option value="480p" ${video.video_quality === 480 ? 'selected' : ''}>480p</option>
            </select>
          </div>

          <div class="col-sm-3 mb-3 video-url-input quality_video_input">
            <label class="form-control-label" for="quality_video_url_input_${index}">Video URL</label>
            <input type="text" name="quality_video_url_input[]" id="quality_video_url_input_${index}" placeholder="Enter video URL" class="form-control" value="${video.quality_video}">
          </div>

          <div class="col-sm-3 mb-3 d-none video-file-input quality_video_file_input">
            <label class="form-control-label" for="quality_video_${index}">Video File</label>
            <input type="file" name="quality_video[]" id="quality_video_${index}" class="form-control-file" accept="video/*">
          </div>

          <div class="col-sm-12 mb-3">
            <button type="button" class="btn btn-danger remove-video-input">Remove</button>
          </div>
        `;

                                            container.appendChild(videoInputContainer);
                                        });
                                } else {

                                    $('#enable_quality').prop('checked', false).val(0);
                                    $('#enable_quality_section').addClass('d-none');
                                }

                            } else {
                                $('#error_message').text(response.message ||
                                    'Failed to import movie details.');
                            }
                        },
                        error: function(xhr) {

                            console.log(xhr)
                            $('#loader').hide();
                            $('#import_movie').show();
                            if (xhr.responseJSON && xhr.responseJSON.message) {


                                $('#error_message').text(xhr.responseJSON.message);
                            }
                            if (xhr.responseJSON && xhr.responseJSON.status_message) {

                                $('#error_message').text(xhr.responseJSON.status_message ||
                                    'Failed to import movie details.');

                            } else {
                                $('#error_message').text(
                                    'An error occurred while fetching the movie details.');
                            }
                        }
                    });
                });
            });


            var thumbUrl = $("#thumbnail_url")
            thumbUrl.attr('accept', 'video/*');
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
