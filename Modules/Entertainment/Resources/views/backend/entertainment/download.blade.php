@extends('backend.layouts.app')

@section('content')
    {{ html()->form('POST', route('backend.entertainments.store-downloads', $data->id))->attribute('data-toggle', 'validator')->open() }}

    @csrf
    <div class="row">
        <div class="tab-pane fade show active" id="nav-download_url" role="tabpanel" aria-labelledby="nav-home-tab"
            tabindex="0">

            <div class="col-sm-12 mb-3">
                <label for="download_status" class="form-label">{{ __('movie.lbl_download_status') }}</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="download_status" value="0">
                    <input type="checkbox" name="download_status" id="download_status" class="form-check-input"
                        value="1" onchange="toggleDownloadSection()"
                        {{ !empty($data) && $data->download_status == 1 ? 'checked' : '' }}>
                </div>
                @error('download_status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div id="download_status_section" class="download_status_section d-none">
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <h5>{{ __('movie.lbl_download_info') }}</h5>
                    </div>

                    <div class="row">

                        <div class="col-sm-6 mb-3">
                            {{ html()->label(__('movie.lbl_video_download_type'), 'download_type')->class('form-control-label') }}
                            {{ html()->select(
                                    'download_type',
                                    $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                                    old('download_type', $data->download_type ?? 'Local'),
                                )->class('form-control select2')->id('download_type') }}
                            @error('download_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6 mb-3">
                            {{ html()->label(__('movie.download_url'), 'download_url')->class('form-control-label') }}
                            {{ html()->text('download_url')->attribute('value', $data->download_url)->placeholder(__('placeholder.download_url'))->class('form-control') }}
                            @error('download_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-12 mb-3">
                            <h5>{{ __('movie.lbl_download_quality_info') }}</h5>
                        </div>

                        <div class="col-sm-12 mb-3">
                            <label for="enable_download_quality"
                                class="form-label">{{ __('movie.lbl_qaulity_info_message') }}</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="enable_download_quality" value="0">
                                <input type="checkbox" name="enable_download_quality" id="enable_download_quality"
                                    class="form-check-input" value="1" onchange="toggleDownloadQualitySection()"
                                    {{ !empty($data) && $data->enable_download_quality == 1 ? 'checked' : '' }}>
                            </div>
                            @error('enable_download_quality')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="download_quality_status_section" class="download_quality_status_section d-none">

                            <div id="video-inputs-container-parent">

                                @if(!empty($data['entertainmentDownloadMappings']) && count($data['entertainmentDownloadMappings']) > 0)
                                @foreach($data['entertainmentDownloadMappings'] as $mapping)
                                <div class="row video-inputs-container">

                                    <div class="col-sm-4 mb-3">
                                        {{ html()->label(__('movie.lbl_quality_video_download_type'), 'quality_video_download_type')->class('form-control-label') }}
                                        {{ html()->select(
                                                'quality_video_download_type[]',
                                                $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                                                old('quality_video_download_type', $mapping->type ?? 'Local'),
                                            )->class('form-control select2')->id('quality_video_download_type') }}
                                        @error('quality_video_download_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                
                                    <div class="col-sm-4 mb-3 video-input">
                                        {{ html()->label(__('movie.lbl_video_download_quality'), 'video_download_quality')->class('form-label') }}
                                        {{ html()->select(
                                                'video_download_quality[]',
                                                $video_quality->pluck('name', 'value')->prepend('Select Quality', ''), $mapping->quality 
                                            )->class('form-control select2')->id('video_download_quality') }}
                                      
                                    </div>
                
                                    <div class="col-sm-4 mb-3 video-url-input" id="download_quality_video_url">
                                        {{ html()->label(__('movie.download_quality_video_url'), 'download_quality_video_url')->class('form-control-label') }}
                                        {{ html()->text('download_quality_video_url[]',$mapping->url)->placeholder(__('placeholder.download_quality_video_url'))->class('form-control') }}
                                       
                                    </div>
                
                                    <div class="col-sm-12 mb-3">
                                        <button type="button" class="btn btn-danger remove-video-input d-none">Remove</button>
                                    </div>
                                </div>
                                @endforeach

                                @else
                                <div class="row video-inputs-container">

                                    <div class="col-sm-4 mb-3">
                                        {{ html()->label(__('movie.lbl_quality_video_download_type'), 'quality_video_download_type')->class('form-control-label') }}
                                        {{ html()->select(
                                                'quality_video_download_type[]',
                                                $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                                                old('quality_video_download_type', 'Local'),
                                            )->class('form-control select2')->id('quality_video_download_type') }}
                                        @error('quality_video_download_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                
                                    <div class="col-sm-4 mb-3 video-input">
                                        {{ html()->label(__('movie.lbl_video_download_quality'), 'video_download_quality')->class('form-label') }}
                                        {{ html()->select(
                                                'video_download_quality[]',
                                                $video_quality->pluck('name', 'value')->prepend('Select Quality', '')
                                            )->class('form-control select2')->id('video_download_quality') }}
                                      
                                    </div>
                
                                    <div class="col-sm-4 mb-3 video-url-input" id="download_quality_video_url">
                                        {{ html()->label(__('movie.download_quality_video_url'), 'download_quality_video_url')->class('form-control-label') }}
                                        {{ html()->text('download_quality_video_url[]')->placeholder(__('placeholder.download_quality_video_url'))->class('form-control') }}
                                       
                                    </div>
                
                                    <div class="col-sm-12 mb-3">
                                        <button type="button" class="btn btn-danger remove-video-input d-none">Remove</button>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <a id="add_more_video" class="btn btn-secondary">Add More</a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>    

        <a href="{{ route('backend.movies.index') }}" class="btn btn-secondary">Close</a>
        <button type="submit" class="btn btn-primary">Save
        </button>
        </form>
    @endsection
    @push('after-scripts')
        <script>
            function toggleDownloadSection() {

                var enableDownloadCheckbox = document.getElementById('download_status');
                var enableDownloadSection = document.getElementById('download_status_section');

                if (enableDownloadCheckbox.checked) {

                    enableDownloadSection.classList.remove('d-none');

                } else {

                    enableDownloadSection.classList.add('d-none');
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                toggleDownloadSection();
            });

            function toggleDownloadQualitySection() {

                var enableQualityCheckbox = document.getElementById('enable_download_quality');
                var enableQualitySection = document.getElementById('download_quality_status_section');

                if (enableQualityCheckbox.checked) {

                    enableQualitySection.classList.remove('d-none');

                } else {

                    enableQualitySection.classList.add('d-none');
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                toggleDownloadQualitySection();
            });

            $(document).ready(function() {

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
    initializeSelect2(originalSection);
});

$(document).on('click', '.remove-video-input', function() {
    $(this).closest('.video-inputs-container').remove();
});
});


        </script>
    @endpush
