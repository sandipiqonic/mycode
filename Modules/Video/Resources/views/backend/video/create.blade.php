@extends('backend.layouts.app')

@section('content')
    <form method="POST" id="form" action="{{ route('backend.videos.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-6 position-relative">
                {{ html()->label(__('video.lbl_logo'), 'poster')->class('form-control-label') }}
                <div class="text-center mb-3">

                    <div class="mb-3" id="selectedImageContainerPoster">
                        @if(old('poster_url', isset($data) ? $data->poster_url : ''))
                            <img src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}" id="selectedPosterImage" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                        <button class="btn btn-danger btn-sm position-absolute close-icon d-none"
                        id="removePostBtn">&times;</button>
                        {{ html()->hidden('poster_url_removed', 0)->id('poster_url_removed') }}
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

                    {{-- <img id="selectedPosterImage" src="{{ old('poster_url', isset($data) ? $data->poster_url : '') }}"
                        alt="feature-image" class="img-fluid mb-2 avatar-80 "
                        style="{{ old('poster_url', isset($data) ? $data->poster_url : '') ? '' : 'display:none;' }}" />
                    <button class="btn btn-danger btn-sm position-absolute close-icon d-none"
                        id="removePostBtn">&times;</button>
                    <div class="d-flex align-items-center justify-content-center gap-2 mt-3">
                        {{ html()->file('poster_url')->id('poster_url')->class('form-control')->attribute('accept', '.jpeg, .jpg, .png, .gif') }}
                        {{ html()->hidden('poster_url_removed', 0)->id('poster_url_removed') }}
                    </div> --}}
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('video.lbl_title') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                {{ html()->text('name')->attribute('value', old('name'))->placeholder(__('placeholder.lbl_video_title'))->class('form-control') }}
                @error('name')
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
                {{ html()->textarea('description', old('description'))->class('form-control')->id('description')->placeholder(__('placeholder.lbl_video_description')) }}
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_trailer_url_type'), 'type')->class('form-label') }}
                {{ html()->select(
                        'trailer_url_type',
                        $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_type'), ''),
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
                {{ html()->file('trailer_video')->class('form-control-file')->accept('video/*')->class('form-control') }}

                @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_access') , 'movie_access')->class('form-control-label') }}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="access" id="paid" value="paid"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('access') == 'paid' ? 'checked' : '' }} checked>
                    <label class="form-check-label" for="paid">{{__('movie.lbl_paid')}}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="access" id="free" value="free"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ old('access') == 'free' ? 'checked' : '' }}>
                    <label class="form-check-label" for="free">{{__('movie.lbl_free')}}</label>
                </div>
                @error('access')
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
                <h5>{{ __('movie.lbl_video_info') }}</h5>
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_video_upload_type'), 'video_upload_type')->class('form-label') }}
                {{ html()->select(
                        'video_upload_type',
                        $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_video_type'), ''),
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
                {{ html()->file('video_file')->class('form-control-file')->accept('video/*')->class('form-control') }}


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
                                $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_video_type'), ''),
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
                                $video_quality->pluck('name', 'value')->prepend(__('placeholder.lbl_select_quality'), '')
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
                            {{ html()->button(__('messages.select'))
                                ->class('input-group-text')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')
                                ->attribute('data-hidden-input', 'file_url_videoquality')
                            }}

                            {{ html()->text('videoquality_input')
                                ->class('form-control')
                                ->placeholder(__('placeholder.lbl_select_file'))
                                ->attribute('aria-label', 'Video Quality Image')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerVideoqualityurl')
                                ->attribute('data-hidden-input', 'file_url_videoquality')
                            }}
                        </div>

                        {{ html()->hidden('video_quality_url')->id('file_url_videoquality')->value(old('video_quality_url', isset($data) ? $data->poster_url : '')) }}
                        {{-- {{ html()->file('quality_video[]')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}
                        @error('quality_video')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-12 mb-3">
                        <button type="button" class="btn btn-danger remove-video-input d-none">{{__('episode.lbl_remove')}}</button>
                    </div>
                </div>
            </div>
            <a id="add_more_video" class="btn btn-secondary">{{__('episode.lbl_add_more')}}</a>
        </div>


        <a href="{{ route('backend.videos.index') }}" class="btn btn-secondary">{{__('messages.close')}}</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}

        @include('components.media-modal')
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function() {
            var selectedImage = document.getElementById('selectedPosterImage');
            var inputFile = document.getElementById('file_url_poster');
            var removeBtn = document.getElementById('removePostBtn');
            var fileUrlRemoved = document.getElementById('poster_url_removed');

            inputFile.addEventListener('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        selectedImage.src = e.target.result;
                        selectedImage.style.display = 'block'; // Show image when a file is selected
                        fileUrlRemoved.value =
                            '0'; // Reset the hidden field when a new file is selected
                    };
                    reader.readAsDataURL(file);
                    removeBtn.classList.remove('d-none');
                } else {
                    selectedImage.src = '';
                    selectedImage.style.display = 'none'; // Hide image when no file is selected
                    inputFile.value = '';
                    fileUrlRemoved.value = '1';

                    removeBtn.classList.add('d-none');
                }
            });

            removeBtn.addEventListener('click', function(event) {
                event.preventDefault();
                selectedImage.src = 'https://dummyimage.com/600x300/cfcfcf/000000.png';
                selectedImage.style.display = 'none';
                inputFile.value = '';
                fileUrlRemoved.value = '1';
                removeBtn.classList.add('d-none');
            });
        });


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

  //   var QualityVideoFileInput = document.getElementById('quality_video_file_input');
   //  var QualityVideoURLInput = document.getElementById('quality_video_input');

     if (selectedtypeValue === 'Local') {
         VideoFileInput.classList.remove('d-none');
         VideoURLInput.classList.add('d-none');
   //    QualityVideoFileInput.classList.remove('d-none');
   //     QualityVideoURLInput.classList.add('d-none');
     } else if (selectedtypeValue === 'URL' || selectedtypeValue === 'YouTube' || selectedtypeValue ===
         'HLS' || selectedtypeValue === 'Vimeo') {
         VideoURLInput.classList.remove('d-none');
         VideoFileInput.classList.add('d-none');
    //    QualityVideoFileInput.classList.add('d-none');
   //    QualityVideoURLInput.classList.remove('d-none');
     } else {
         VideoFileInput.classList.add('d-none');
         VideoURLInput.classList.add('d-none');
     //  QualityVideoFileInput.classList.add('d-none');
    //    QualityVideoURLInput.classList.add('d-none');
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
