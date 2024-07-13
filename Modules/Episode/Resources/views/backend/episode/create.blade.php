@extends('backend.layouts.app')

@section('content')
<div class="row mb-3">
    <div class="text-danger" id="error_message"></div>
    <div class="col-md-4">

        {{ html()->label(__('movie.tvshows'), 'tvshows')->class('form-label') }}
        {{ html()->select(
            'tv_show_id',
            $imported_tvshow->pluck('name', 'tmdb_id')->prepend('Select TvShow', null),

        )->class('form-control select2')->id('tv_show_id') }}

     <span class="text-danger" id="tvshow_id_error"></span>


    </div>
    <div class="col-md-4">
        {{ html()->label(__('movie.seasons'), 'seasons')->class('form-label') }}
        {{ html()->select(
            'season_index',
            null,

        )->class('form-control select2')->id('season_index') }}
       <span class="text-danger" id="season_index_error"></span>
    </div>

    <div class="col-md-4">
        {{ html()->label(__('episode.episode'), 'episode')->class('form-label') }}
        {{ html()->select(
            'episode_index',
            null,

        )->class('form-control select2')->id('episode_index') }}
       <span class="text-danger" id="episode_index_error"></span>
    </div>
    <div id="loader" style="display: none;">

     <button class="btn btn-md btn-primary float-right">Loading...</button>

    </div>
    <div class="col-md-4">
        <button class="btn btn-md btn-primary float-right" id="import_episode">Import</button>

    </div>

</div>
    <form method="POST" id="form" action="{{ route('backend.episodes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            {{ html()->hidden('episode_number', null)->id('episode_number') }}
            {{ html()->hidden('tmdb_season', null)->id('tmdb_season') }}
            {{ html()->hidden('tmdb_id', null)->id('tmdb_id') }}

            <div class="col-6 position-relative">
                {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-control-label') }}
                <div class="text-center mb-3">
                    <div class="mb-3" id="selectedImageContainerPoster">
                        @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                            <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <div class="input-group col-sm-6 mb-3">
                        {{ html()->button('Select')
                            ->class('input-group-text')
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

                    {{ html()->hidden('poster_url')->id('file_url_poster')->value(old('poster_url', isset($data) ? $data->poster_url : '')) }}
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_episode_name'))->class('form-control') }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="col-sm-6 mb-3">
                {{ html()->label(__('season.lbl_tv_shows') . ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
                {{ html()->select(
                        'entertainment_id',
                        $tvshows->pluck('name', 'id')->prepend('Select TvShow',''), old('entertainment_id'))->class('form-control select2')->id('entertainment_id') }}
                @error('entertainment_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('episode.lbl_season') . ' <span class="text-danger">*</span>', 'season_id')->class('form-label') }}
                {{ html()->select(
                        'season_id',
                        $seasons->pluck('name', 'id')->prepend('Select Season',''),old('season_id'))->class('form-control select2')->id('season_id') }}
                @error('season_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_short_desc'), 'short_desc')->class('form-label') }}
                {{ html()->textarea('short_desc', old('short_desc'))->class('form-control')->id('short_desc')->placeholder(__('placeholder.episode_short_desc')) }}
                @error('short_desc')
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
                    @if(old('trailer_url', isset($data) ? $data->trailer_url : ''))
                        <img src="{{ old('trailer_url', isset($data) ? $data->trailer_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>

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
                {{-- {{ html()->file('trailer_video')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}

                @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_access') , 'access')->class('form-control-label') }}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="access" id="paid" value="paid"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('access') == 'paid' ? 'checked' : '' }} checked>
                    <label class="form-check-label" for="paid">Paid</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="access" id="free" value="free"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('access') == 'free' ? 'checked' : '' }}>
                    <label class="form-check-label" for="free">Free</label>
                </div>
                @error('access')
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
                {{ html()->label(__('movie.lbl_imdb_rating'), 'IMDb_rating')->class('form-label') }}
                {{ html()->select('IMDb_rating', $numberOptions->prepend('Select IMDb Rating', ''), old('IMDb_rating'))->class('form-control select2')->id('IMDb_rating') }}
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
                {{ html()->label(__('movie.lbl_duration') . ' <span class="text-danger">*</span>', 'duration')->class('form-control-label') }}
                {{ html()->time('duration')->attribute('value', old('duration'))->placeholder(__('placeholder.lbl_duration'))->class('form-control') }}
                @error('time')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_release_date') . '<span class="text-danger">*</span>' , 'release_date')->class('form-control-label') }}
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
                    @if(old('video_url_input', isset($data) ? $data->video_url_input : ''))
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
                {{-- {{ html()->file('video_file')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}


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
                            @if(old('video_quality_url', isset($data) ? $data->video_quality_url : ''))
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
                        {{-- {{ html()->file('quality_video[]')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}
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


        <a href="{{ route('backend.episodes.index') }}" class="btn btn-secondary">Close</a>
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

        function toggleQualitySection() {

var enableQualityCheckbox = document.getElementById('enable_quality');
var enableQualitySection = document.getElementById('enable_quality_section');

if (enableQualityCheckbox.checked) {

 enableQualitySection.classList.remove('d-none');

  } else {

  enableQualitySection.classList.add('d-none');
}
}

document.addEventListener('DOMContentLoaded', function () {
toggleQualitySection();
});


document.addEventListener('DOMContentLoaded', function() {

 function handleVideoUrlTypeChange(selectedtypeValue) {
     var VideoFileInput = document.getElementById('video_file_input');
     var VideoURLInput = document.getElementById('video_url_input');

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

$('#add_more_video').click(function() {
var originalSection = $('.video-inputs-container').first();
destroySelect2(originalSection);

var newSection = originalSection.clone();
newSection.find('input').val('');
newSection.find('select').val('').trigger('change');

var timestamp = Date.now();
newSection.find('[id]').each(function() {
 var newId = $(this).attr('id') + timestamp;
 $(this).attr('id', newId);

 var ariaLabelledby = $(this).attr('aria-labelledby');
 if (ariaLabelledby) {
     $(this).attr('aria-labelledby', ariaLabelledby + timestamp);
 }
 var ariaControls = $(this).attr('aria-controls');
 if (ariaControls) {
     $(this).attr('aria-controls', ariaControls + timestamp);
 }
});

newSection.find('[name]').each(function() {
 var newName = $(this).attr('name') + timestamp;
 $(this).attr('name', newName);
});

newSection.find('.remove-video-input').removeClass('d-none');

$('#video-inputs-container-parent').append(newSection);

initializeSelect2(newSection);
handleVideoQualityTypeChange(newSection);
initializeSelect2(originalSection);
});

$(document).on('click', '.remove-video-input', function() {
$(this).closest('.video-inputs-container').remove();
});

// Initial call for the first section
handleVideoQualityTypeChange($('.video-inputs-container').first());
initializeSelect2($('.video-inputs-container').first());
});


   function getSeasons(entertainmentId, selectedSeasonId = "") {
    
       var get_seasons_list = "{{ route('backend.seasons.index_list', ['entertainment_id' => '']) }}" + entertainmentId;
       get_seasons_list = get_seasons_list.replace('amp;', '');

       $.ajax({
           url: get_seasons_list,
           success: function(result) {

               var formattedResult = result.map(function(season) {
                   return { id: season.id, text: season.name };
               });

               $('#season_id').select2({
                   width: '100%',
                   placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}",
                   data: formattedResult
               });

               if (selectedSeasonId != "") {
                   $('#season_id').val(selectedSeasonId).trigger('change');
               }
           }
       });
    }

    $(document).ready(function() {
    $('#entertainment_id').change(function() {
        var entertainmentId = $(this).val();
        if (entertainmentId) {
            $('#season_id').empty().select2({
                width: '100%',
                placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
            });
            getSeasons(entertainmentId);
        } else {
            $('#season_id').empty().select2({
                width: '100%',
                placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
            });
        }
    });
});


   
      // ///////////////////////////////////////////////   get season list ///////////////////////////////////////

      function getimportSeasons(tmdbId, selectedSeasonId = "") {
    var get_seasons_list = "{{ route('backend.episodes.import-season-list', ['tmdb_id' => '']) }}" + tmdbId;
    get_seasons_list = get_seasons_list.replace('amp;', '');

    $.ajax({
        url: get_seasons_list,
        success: function(result) {
            var formattedResult = result.map(function(season) {
                return { id: season.season_index, text: season.name };
            });

            formattedResult.unshift({ id: '', text: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}" });

            $('#season_index').select2({
                width: '100%',
                placeholder: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}",
                data: formattedResult
            });

            if (selectedSeasonId != "") {
                $('#season_index').val(selectedSeasonId).trigger('change');
            }
        }
    });
}

$(document).ready(function() {
    $('#tv_show_id').change(function() {
        var tvShowId = $(this).val();
        if (tvShowId) {
            $('#season_index').empty().select2({
                width: '100%',
                placeholder: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}"
            });
            getimportSeasons(tvShowId);
        } else {
            $('#season_index').empty().select2({
                width: '100%',
                placeholder: "{{ trans('episode.select_seson', ['select' => trans('messages.season')]) }}"
            });
        }
    });
});


         // ///////////////////////////////////////////////  Get Episode List ///////////////////////////////////////
         function getEpisode(tvShowId, season_index, selectedEpisodeId = "") {
    var get_episode_list = "{{ route('backend.episodes.import-episode-list') }}";
    get_episode_list = get_episode_list.replace('amp;', '');

    $.ajax({
        url: get_episode_list,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            tvshow_id: tvShowId,
            season_id: season_index,
        },
        success: function(result) {
            var formattedResult = result.map(function(episode) {
                return { id: episode.episode_number, text: episode.name };
            });

            formattedResult.unshift({ id: '', text: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}" });

            $('#episode_index').select2({
                width: '100%',
                placeholder: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}",
                data: formattedResult
            });

            if (selectedEpisodeId != "") {
                $('#episode_index').val(selectedEpisodeId).trigger('change');
            }
        }
    });
}

$(document).ready(function() {
    $('#season_index').change(function() {
        var season_index = $(this).val();
        var tvShowId = $('#tv_show_id').val();

        if (season_index) {
            $('#episode_index').empty().select2({
                width: '100%',
                placeholder: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}"
            });
            getEpisode(tvShowId, season_index);
        } else {
            $('#episode_index').empty().select2({
                width: '100%',
                placeholder: "{{ trans('episode.select_episode', ['select' => trans('messages.episode')]) }}"
            });
        }
    });
});

        //////////////////////////////////////   Import Episode Data ///////////////////////////////////////////

 $(document).ready(function() {
    $('#import_episode').on('click', function(e) {
        e.preventDefault();

        var tvshowID = $('#tv_show_id').val();
       $('#tvshow_id_error').text('');
       $('#error_message').text('');


       var seasonID = $('#season_index').val();
       $('#season_index_error').text('');
       $('#error_message').text('');


       var episodeID = $('#episode_index').val();
       $('#episode_index_error').text('');
       $('#error_message').text('');


       var import_episode = "{{ route('backend.episodes.import-episode') }}";
         import_episode = import_episode.replace('amp;', '');
    
       if (!tvshowID) {
           $('#tvshow_id_error').text('TV show ID is required.');
           return;
       }

       if (!seasonID) {
           $('#season_index_error').text('Season is required.');
           return;
       }

       if (!episodeID) {
           $('#episode_index_error').text('Season is required.');
           return;
       }

        $('#loader').show();
        $('#import_episode').hide();

        $.ajax({
            url: import_episode,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
           data: {
                   tvshow_id: tvshowID,
                   season_id: seasonID,
                   episode_id: episodeID,
                 },
            success: function(response) {

                $('#loader').hide();
                $('#import_episode').show();

                if (response.success){

                 var data = response.data;
                 $('#tmdb_season').val(data.tmdb_season);
                 $('#episode_number').val(data.episode_number);
                 $('#tmdb_id').val(data.tmdb_id);
                 $('#selectedPosterImage').attr('src', data.poster_url).show();
                 $('#name').val(data.name);
                 $('#description').val(data.description);
                 $('#trailer_url').val(data.trailer_url);
                 $('#trailer_url_type').val(data.trailer_url_type).trigger('change');
                 $('#entertainment_id').val(data.entertainment_id).trigger('change');
                 $('#season_id').val(data.season_id).trigger('change');  
                 $('#release_date').val(data.release_date);
                 $('#duration').val(data.duration);
                 $('#poster_url').val(data.poster_url);
                 $('#video_upload_type').val(data.video_url_type).trigger('change');
                 $('#video_url_input').val(data.video_url);


                 if(data.is_restricted) {
                        $('#is_restricted').prop('checked', true).val(1);
                    } else {
                        $('#is_restricted').prop('checked', false).val(0);
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

                    if (data.enable_quality === true) {

                      $('#enable_quality').prop('checked', true).val(1);
                    } else {

                      $('#enable_quality').prop('checked', false).val(0);
                    }

                    toggleQualitySection()

                    if (data.enable_quality === true) {


      const container = document.getElementById('video-inputs-container-parent');
      container.innerHTML = ''; // Clear existing content

      data.episodeStreamContentMappings.forEach((video, index) => {
        const videoInputContainer = document.createElement('div');
        videoInputContainer.className = 'row video-inputs-container';

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
