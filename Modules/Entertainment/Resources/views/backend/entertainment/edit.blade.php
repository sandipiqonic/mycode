@extends('backend.layouts.app')

@section('content')

        {{ html()->form('PUT' ,route('backend.entertainments.update', $data->id))
        ->attribute('enctype', 'multipart/form-data')
        ->attribute('data-toggle', 'validator')
        ->open()
    }}

        @csrf
        <div class="row">
            <div class="mb-3" id="selectedImageContainer1">
                @if ($data->thumbnail_url)
                    <img src="{{ $data->thumbnail_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                @endif
            </div>

            <div class="input-group mb-3">
                {{ html()->button('Select')
                    ->class('input-group-text')
                    ->type('button')
                    ->attribute('data-bs-toggle', 'modal')
                    ->attribute('data-bs-target', '#exampleModal')
                    ->attribute('data-image-container', 'selectedImageContainer1')
                    ->attribute('data-hidden-input', 'file_url1')
                }}

                {{ html()->text('image_input1')
                    ->class('form-control')
                    ->placeholder('Select Image')
                    ->attribute('aria-label', 'Image Input 1')
                    ->attribute('data-bs-toggle', 'modal')
                    ->attribute('data-bs-target', '#exampleModal')
                    ->attribute('data-image-container', 'selectedImageContainer1')
                    ->attribute('data-hidden-input', 'file_url1')
                    ->attribute('aria-describedby', 'basic-addon1')
                }}
            </div>

            {{ html()->hidden('thumbnail_url')->id('file_url1')->value($data->thumbnail_url) }}


            <div class="col-6 position-relative">
                {{ html()->label(__('movie.lbl_poster'), 'poster')->class('form-control-label') }}
                <div class="mb-3" id="selectedImageContainer2">
                    @if ($data->poster_url)
                        <img src="{{ $data->poster_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>

                <div class="input-group mb-3">
                    {{ html()->button('Select')
                        ->class('input-group-text')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainer2')
                        ->attribute('data-hidden-input', 'file_url2')
                    }}

                    {{ html()->text('image_input2')
                        ->class('form-control')
                        ->placeholder('Select Image')
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
                        $upload_url_type->pluck('name', 'value')->prepend('Select Type', ''),
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

                <div class="mb-3" id="selectedImageContainer3">
                    @if ($data->trailer_url)
                        <img src="{{ $data->trailer_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>

                <div class="input-group mb-3">
                    {{ html()->button('Select')
                        ->class('input-group-text')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainer3')
                        ->attribute('data-hidden-input', 'file_url3')
                    }}

                    {{ html()->text('image_input3')
                        ->class('form-control')
                        ->placeholder('Select Image')
                        ->attribute('aria-label', 'Image Input 3')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainer3')
                        ->attribute('data-hidden-input', 'file_url3')
                    }}
                </div>

                {{ html()->hidden('trailer_video')->id('file_url3')->value($data->trailer_url) }}

                {{-- {{ html()->file('trailer_video')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}

                @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_access') , 'movie_access')->class('form-control-label') }}
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="movie_access" id="paid" value="paid"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ $data->movie_access == 'paid' ? 'checked' : '' }} checked>
                    <label class="form-check-label" for="paid">Paid</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="movie_access" id="free" value="free"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ $data->movie_access == 'free' ? 'checked' : '' }}>
                    <label class="form-check-label" for="free">Free</label>
                </div>
                @error('movie_access')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3 d-none" id="planSelection">
                {{ html()->label(__('movie.lbl_select_plan'), 'type')->class('form-label') }}
                {{ html()->select('plan_id', $plan->pluck('name', 'id')->prepend('Select Plan', ''), $data->plan_id)->class('form-control select2')->id('plan_id') }}
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
                {{ html()->label(__('movie.lbl_movie_language') . '<span class="text-danger">*</span>', 'language')->class('form-label') }}
                {{ html()->select('language', $movie_language->pluck('name', 'value')->prepend('Select Language', ''), $data->language)->class('form-control select2')->id('language') }}
                @error('language')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_genres') . '<span class="text-danger">*</span>', 'genres')->class('form-label') }}
                {{ html()->select('genres[]', $genres->pluck('name', 'id'),  $data->genres)->class('form-control select2')->id('genres')->multiple() }}
                @error('genres')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_imdb_rating'), 'IMDb_rating')->class('form-label') }}
                {{ html()->select('IMDb_rating', $numberOptions->prepend('Select IMDb Rating', ''),$data->IMDb_rating)->class('form-control select2')->id('IMDb_rating') }}
                @error('IMDb_rating')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>



            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_content_rating') , 'content_rating')->class('form-label') }}

                {{ html()->text('content_rating')->attribute('value', $data->content_rating)->placeholder(__('placeholder.lbl_content_rating'))->class('form-control') }}

                @error('content_rating')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
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
                <h5>{{ __('movie.lbl_actor_director') }}</h5>
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_actors') . '<span class="text-danger">*</span>', 'actors')->class('form-label') }}
                {{ html()->select('actors[]', $actors->pluck('name', 'id'), $data->actors )->class('form-control select2')->id('actors')->multiple() }}
                @error('actors')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_directors') . '<span class="text-danger">*</span>', 'directors')->class('form-label') }}
                {{ html()->select('directors[]', $directors->pluck('name', 'id'), $data->directors )->class('form-control select2')->id('directors')->multiple() }}
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

                <div class="mb-3" id="selectedImageContainer4">
                    @if (Str::endsWith($data->video_url_input, ['.jpeg', '.jpg', '.png', '.gif']))
                        <img class="img-fluid" src="{{ $data->video_url_input }}" style="width: 10rem; height: 10rem;">
                    @else
                    <video width="400" controls="controls" preload="metadata" >
                        <source src="{{ $data->video_url_input }}" type="video/mp4" >
                        </video>
                    @endif
                </div>

                <div class="input-group mb-3">
                    {{ html()->button('Select')
                        ->class('input-group-text')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainer4')
                        ->attribute('data-hidden-input', 'file_url4')
                    }}

                    {{ html()->text('image_input4')
                        ->class('form-control')
                        ->placeholder('Select Image')
                        ->attribute('aria-label', 'Image Input 3')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainer4')
                        ->attribute('data-hidden-input', 'file_url4')
                    }}
                </div>

                {{ html()->hidden('video_url_input')->id('file_url4')->value($data->video_url_input) }}
                {{-- {{ html()->file('video_file')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}


                @error('video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            {{--
    <div class="col-sm-6 mb-3">
        {{ html()->label(__('movie.lbl_download_status'), 'download_status')->class('form-label') }}
        <div class="form-check form-switch">
            {{ html()->hidden('download_status', 0) }}
            {{
                html()->checkbox('download_status',old('download_status', false))
                    ->class('form-check-input')
                    ->id('download_status')
            }}
        </div>
        @error('download_status')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6 mb-3">
        {{ html()->label(__('movie.downaload_url'), 'downaload_url')->class('form-control-label') }}
        {{ html()->text('downaload_url')
                 ->attribute('value', old('downaload_url'))  ->placeholder(__('placeholder.downaload_url'))
                 ->class('form-control')
             }}
        @error('downaload_url')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div> --}}

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
            @if(!empty($data['entertainmentStreamContentMappings']) && count($data['entertainmentStreamContentMappings']) > 0)
                @foreach($data['entertainmentStreamContentMappings'] as $mapping)
                <div class="row video-inputs-container">

                    <div class="col-sm-3 mb-3">
                        {{ html()->label(__('movie.lbl_video_upload_type'), 'video_quality_type')->class('form-label') }}
                        {{ html()->select(
                                'video_quality_type[]',
                                $upload_url_type->pluck('name', 'value')->prepend('Select Video Type', ''),
                                $mapping->type,
                            )->class('form-control select2 video_quality_type')->id('video_quality_type_' . $mapping->id) }}
                        @error('video_quality_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-4 mb-3 video-input">
                        {{ html()->label(__('movie.lbl_video_quality'), 'video_quality')->class('form-label') }}
                        {{ html()->select(
                                'video_quality[]',
                                $video_quality->pluck('name', 'value')->prepend('Select Quality', ''),
                                $mapping->quality // Populate the select with the existing quality
                            )->class('form-control select2')->id('video_quality_' . $mapping->id) }}
                        @error('video_quality')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-4 mb-3 video-url-input quality_video_input" id="quality_video_input">
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
                        <button type="button" class="btn btn-danger remove-video-input">Remove</button>
                    </div>
                </div>
                @endforeach
                @else
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

                <div class="col-sm-6 mb-3 video-input">
                    {{ html()->label(__('movie.lbl_video_quality'), 'video_quality')->class('form-label') }}
                    {{ html()->select(
                            'video_quality[]',
                            $video_quality->pluck('name', 'value')->prepend('Select Quality', ''),
                            null // No existing quality
                        )->class('form-control select2')->id('video_quality_new') }}
                    @error('video_quality')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-5 mb-3 video-url-input" id="quality_video_input">
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
                        {{ html()->button('Select')
                            ->class('input-group-text')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer5')
                            ->attribute('data-hidden-input', 'file_url5')
                        }}

                        {{ html()->text('image_input5')
                            ->class('form-control')
                            ->placeholder('Select Image')
                            ->attribute('aria-label', 'Image Input 5')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer5')
                            ->attribute('data-hidden-input', 'file_url5')
                        }}
                    </div>

                    {{ html()->hidden('quality_video[]')->id('file_url5')->value($data->video_quality_url) }}
                    {{-- {{ html()->file('quality_video[]')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}
                    @error('quality_video')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-sm-12 mb-3">
                    <button type="button" class="btn btn-danger remove-video-input d-none">Remove</button>
                </div>
            </div>
        @endif
            </div>
            <a id="add_more_video" class="btn btn-secondary">Add More</a>
        </div>

        <a href="{{ route('backend.movies.index') }}" class="btn btn-secondary">Close</a>
        <button type="submit" class="btn btn-primary">Save
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
