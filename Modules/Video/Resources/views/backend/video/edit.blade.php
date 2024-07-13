@extends('backend.layouts.app')

@section('content')

        {{ html()->form('PUT' ,route('backend.videos.update', $data->id))
        ->attribute('enctype', 'multipart/form-data')
        ->attribute('data-toggle', 'validator')
        ->open()
    }}


        @csrf
        <div class="row">

            <div class="col-6 position-relative">
                {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-control-label') }}
                <div class="text-center mb-3">
                    <div class="mb-3" id="selectedImageContainer2">
                        @if ($data->poster_url)
                            <img src="{{ $data->poster_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        {{ html()->button(__('messages.select'))
                            ->class('input-group-text')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer2')
                            ->attribute('data-hidden-input', 'file_url2')
                        }}

                        {{ html()->text('image_input2')
                            ->class('form-control')
                            ->placeholder(__('placeholder.lbl_image'))
                            ->attribute('aria-label', 'Image Input 2')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer2')
                            ->attribute('data-hidden-input', 'file_url2')
                            ->attribute('aria-describedby', 'basic-addon1')
                        }}
                    </div>

                    {{ html()->hidden('poster_url')->id('file_url2')->value($data->poster_url) }}
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                {{ html()->text('name')->attribute('value', $data->name)->placeholder(__('placeholder.lbl_movie_name'))->class('form-control') }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                {{ html()->textarea('description',$data->description)->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description')) }}
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_trailer_url_type'), 'type')->class('form-label') }}
                {{ html()->select(
                        'trailer_url_type',
                        $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_type'), ''),
                        old('trailer_url_type', $data->trailer_url_type ?? 'Local') // Set 'Local' as the default value
                    )->class('form-control select2')->id('trailer_url_type') }}
                @error('trailer_url_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="url_input">
                {{ html()->label(__('movie.lbl_trailer_url'), 'trailer_url')->class('form-control-label') }}
                {{ html()->text('trailer_url')->attribute('value', $data->trailer_url)->placeholder(__('placeholder.lbl_trailer_url'))->class('form-control') }}
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
                {{ html()->label(__('movie.lbl_movie_access') , 'access')->class('form-control-label') }}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="access" id="paid" value="paid"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ $data->access == 'paid' ? 'checked' : '' }} checked>
                    <label class="form-check-label" for="paid">{{__('movie.lbl_paid')}}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="access" id="free" value="free"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ $data->access == 'free' ? 'checked' : '' }}>
                    <label class="form-check-label" for="free">{{__('movie.lbl_free')}}</label>
                </div>
                @error('movie_access')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="planSelection">
                {{ html()->label(__('movie.lbl_select_plan'), 'type')->class('form-label') }}
                {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend(__('placeholder.lbl_select_plan'), ''), $data->plan_id)->class('form-control select2')->id('plan_id') }}
                @error('plan_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                <div class="form-check form-switch">
                    {{ html()->hidden('status', 0) }}
                    {{
                        html()->checkbox('status',$data->status)
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
                {{ html()->time('duration')->attribute('value',  $data->duration)->placeholder(__('placeholder.lbl_duration'))->class('form-control') }}
                @error('time')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_release_date') , 'release_date')->class('form-control-label') }}
                {{ html()->date('release_date')->attribute('value', $data->release_date)->placeholder(__('placeholder.lbl_release_date'))->class('form-control') }}
                @error('release_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_age_restricted'), 'is_restricted')->class('form-label') }}
                <div class="form-check form-switch">
                    {{ html()->hidden('is_restricted', 0) }}
                    {{ html()->checkbox('is_restricted', $data->is_restricted)->class('form-check-input')->id('is_restricted') }}
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
                        old('video_upload_type', $data->video_upload_type ?? 'Local'),
                    )->class('form-control select2')->id('video_upload_type') }}
                @error('video_upload_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="video_url_input">
                {{ html()->label(__('movie.video_url_input'), 'video_url_input')->class('form-control-label') }}
                {{ html()->text('video_url_input')->attribute('value', $data->video_url_input)->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
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
                <input type="checkbox" name="enable_quality" id="enable_quality" class="form-check-input" value="1" onchange="toggleQualitySection()" {{!empty($data) && $data->enable_quality == 1 ? 'checked' : ''}} >
            </div>
            @error('enable_quality')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>


        <div id="enable_quality_section" class="enable_quality_section d-none">
            <div id="video-inputs-container-parent">
            @if(!empty($data['VideoStreamContentMappings']) && count($data['VideoStreamContentMappings']) > 0)
                @foreach($data['VideoStreamContentMappings'] as $mapping)
                <div class="row video-inputs-container">

                    <div class="col-sm-3 mb-3">
                        {{ html()->label(__('movie.lbl_video_upload_type'), 'video_quality_type')->class('form-label') }}
                        {{ html()->select(
                                'video_quality_type[]',
                                $upload_url_type->pluck('name', 'value')->prepend(__('placeholder.lbl_select_video_type'), ''),
                                $mapping->type,
                            )->class('form-control select2 video_quality_type')->id('video_quality_type_' . $mapping->id) }}
                        @error('video_quality_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-3 mb-3 video-input">
                        {{ html()->label(__('movie.lbl_video_quality'), 'video_quality')->class('form-label') }}
                        {{ html()->select(
                                'video_quality[]',
                                $video_quality->pluck('name', 'value')->prepend(__('placeholder.lbl_select_quality'), ''),
                                $mapping->quality // Populate the select with the existing quality
                            )->class('form-control select2')->id('video_quality_' . $mapping->id) }}
                        @error('video_quality')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-3 mb-3 video-url-input quality_video_input" id="quality_video_input">
                        {{ html()->label(__('movie.video_url_input'), 'quality_video_url_input')->class('form-control-label') }}
                        {{ html()->text('quality_video_url_input[]', $mapping->url) // Populate the input with the existing URL
                            ->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                        @error('quality_video_url_input')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-sm-3 mb-3 d-none video-file-input quality_video_file_input">
                        {{ html()->label(__('movie.video_file_input'), 'quality_video')->class('form-control-label') }}
                        {{ html()->file('quality_video[]')->class('form-control-file')->accept('video/*')->class('form-control') }}
                        @error('quality_video')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-12 mb-3">
                        <button type="button" class="btn btn-danger remove-video-input">{{__('episode.lbl_remove')}}</button>
                    </div>
                </div>
                @endforeach
                @else
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
                            $video_quality->pluck('name', 'value')->prepend(__('placeholder.lbl_select_quality'), ''),
                            null // No existing quality
                        )->class('form-control select2')->id('video_quality_new') }}
                    @error('video_quality')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-3 mb-3 video-url-input quality_video_input" id="quality_video_input">
                    {{ html()->label(__('movie.video_url_input'), 'quality_video_url_input')->class('form-control-label') }}
                    {{ html()->text('quality_video_url_input[]', null) // No existing URL
                        ->placeholder(__('placeholder.video_url_input'))->class('form-control') }}
                    @error('quality_video_url_input')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-3 mb-3 d-none video-file-input quality_video_file_input">
                    {{ html()->label(__('movie.video_file_input'), 'quality_video')->class('form-control-label') }}
                    <div class="mb-3" id="selectedImageContainer5">
                        @if ($data->video_quality_url)
                            <img src="{{ $data->video_quality_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        {{ html()->button(__('messages.select'))
                            ->class('input-group-text')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer5')
                            ->attribute('data-hidden-input', 'file_url5')
                        }}

                        {{ html()->text('image_input5')
                            ->class('form-control')
                            ->placeholder((__('placeholder.lbl_select_file')))
                            ->attribute('aria-label', 'Image Input 5')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer5')
                            ->attribute('data-hidden-input', 'file_url5')
                        }}
                    </div>

                    {{ html()->hidden('video_quality_url')->id('file_url5')->value($data->video_quality_url) }}
                    {{-- {{ html()->file('quality_video[]')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}
                    @error('quality_video')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-3 mb-3">
                    <button type="button" class="btn btn-danger remove-video-input d-none">{{__('episode.lbl_remove')}}</button>
                </div>
            </div>
           @endif
            </div>
            <a id="add_more_video" class="btn btn-secondary">{{__('episode.lbl_add_more')}}</a>
        </div>

        <a href="{{ route('backend.movies.index') }}" class="btn btn-secondary">{{__('messages.close')}}</a>
        <button type="submit" class="btn btn-primary">{{__('messages.save')}}
        </button>
    </form>

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

    // var QualityVideoFileInput = document.getElementById('quality_video_file_input');
    // var QualityVideoURLInput = document.getElementById('quality_video_input');

    if (selectedtypeValue === 'Local') {
        VideoFileInput.classList.remove('d-none');
        VideoURLInput.classList.add('d-none');
        // QualityVideoFileInput.classList.remove('d-none');
        // QualityVideoURLInput.classList.add('d-none');
    } else if (selectedtypeValue === 'URL' || selectedtypeValue === 'YouTube' || selectedtypeValue ===
        'HLS' || selectedtypeValue === 'Vimeo') {
        VideoURLInput.classList.remove('d-none');
        VideoFileInput.classList.add('d-none');
        // QualityVideoFileInput.classList.add('d-none');
        // QualityVideoURLInput.classList.remove('d-none');
    } else {
        VideoFileInput.classList.add('d-none');
        VideoURLInput.classList.add('d-none');
        // QualityVideoFileInput.classList.add('d-none');
        // QualityVideoURLInput.classList.add('d-none');
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

$('#video_quality').select2({
    width: '100%'
});
function updateRemoveButtonVisibility() {
    var sections = $('.video-inputs-container');
    if (sections.length > 1) {
        sections.find('.remove-video-input').removeClass('d-none');
    } else {
        sections.find('.remove-video-input').addClass('d-none');
    }
}

// Initialize Select2 for existing elements
initializeSelect2($('#video-inputs-container-parent'));

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
