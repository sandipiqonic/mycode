@extends('backend.layouts.app')

@section('content')

        {{ html()->form('PUT' ,route('backend.entertainments.update', $data->id))
        ->attribute('enctype', 'multipart/form-data')
        ->attribute('data-toggle', 'validator')
        ->open()
    }}


        @csrf
        <div class="row">
            {{ html()->hidden('type', $data->type)->id('type') }}
            <div class="col-6 position-relative">
                {{ html()->label(__('movie.lbl_thumbnail'), 'thumbnail')->class('form-control-label') }}
                <div class="text-center mb-3">
                    <div class="mb-3" id="selectedImageContainer1">
                        @if ($data->thumbnail_url)
                            <img src="{{ $data->thumbnail_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        {{ html()->button(__('messages.select'))
                            ->class('input-group-text')
                            ->type('button')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer1')
                            ->attribute('data-hidden-input', 'file_url1')
                        }}

                        {{ html()->text('image_input1')
                            ->class('form-control')
                            ->placeholder(__('placeholder.lbl_image'))
                            ->attribute('aria-label', 'Image Input 1')
                            ->attribute('data-bs-toggle', 'modal')
                            ->attribute('data-bs-target', '#exampleModal')
                            ->attribute('data-image-container', 'selectedImageContainer1')
                            ->attribute('data-hidden-input', 'file_url1')
                            ->attribute('aria-describedby', 'basic-addon1')
                        }}
                    </div>

                    {{ html()->hidden('thumbnail_url')->id('file_url1')->value($data->thumbnail_url) }}
                </div>
            </div>

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
                <div class="mb-3" id="selectedImageContainer3">
                    @if ($data->trailer_url)
                        <img src="{{ $data->trailer_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>

                <div class="input-group mb-3">
                    {{ html()->button(__('messages.select'))
                        ->class('input-group-text')
                        ->type('button')
                        ->attribute('data-bs-toggle', 'modal')
                        ->attribute('data-bs-target', '#exampleModal')
                        ->attribute('data-image-container', 'selectedImageContainer3')
                        ->attribute('data-hidden-input', 'file_url3')
                    }}

                    {{ html()->text('image_input3')
                        ->class('form-control')
                        ->placeholder(__('placeholder.lbl_select_file'))
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
                    <label class="form-check-label" for="paid">{{__('movie.lbl_paid')}}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="movie_access" id="free" value="free"
                        onchange="showPlanSelection(this.value === 'paid')"
                        {{ $data->movie_access == 'free' ? 'checked' : '' }}>
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
                        html()->checkbox('status', $data->status)
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
                {{ html()->select('language', $movie_language->pluck('name', 'value')->prepend(__('placeholder.lbl_select_language'), ''), $data->language)->class('form-control select2')->id('language') }}
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
                {{ html()->select('IMDb_rating', $numberOptions->prepend(__('placeholder.lbl_select_imdb_rating'), ''),$data->IMDb_rating)->class('form-control select2')->id('IMDb_rating') }}
                @error('IMDb_rating')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>



            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_content_rating') . '<span class="text-danger">*</span>', 'content_rating')->class('form-label') }}

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

        <a href="{{ route('backend.movies.index') }}" class="btn btn-secondary">{{__('messages.close')}}</a>
        <button type="submit" class="btn btn-primary">{{__('messages.save')}}</button>
    </form>
    @include('components.media-modal')
@endsection
@push('after-scripts')
    <script>
        // $(document).ready(function() {
        //     var selectedImage = document.getElementById('selectedImage');
        //     var inputFile = document.getElementById('thumbnail_url');
        //     var removeBtn = document.getElementById('removeBtn');
        //     var fileUrlRemoved = document.getElementById('thumbnail_url_removed');

        //     inputFile.addEventListener('change', function(event) {
        //         var file = event.target.files[0];
        //         if (file) {
        //             var reader = new FileReader();
        //             reader.onload = function(e) {
        //                 selectedImage.src = e.target.result;
        //                 selectedImage.style.display = 'block'; // Show image when a file is selected
        //                 fileUrlRemoved.value =
        //                     '0'; // Reset the hidden field when a new file is selected
        //             };
        //             reader.readAsDataURL(file);
        //             removeBtn.classList.remove('d-none');
        //         } else {
        //             selectedImage.src = '';
        //             selectedImage.style.display = 'none'; // Hide image when no file is selected
        //             inputFile.value = '';
        //             fileUrlRemoved.value = '1';

        //             removeBtn.classList.add('d-none');
        //         }
        //     });

        //     removeBtn.addEventListener('click', function(event) {
        //         event.preventDefault();
        //         selectedImage.src =
        //             'https://dummyimage.com/600x300/cfcfcf/000000.png'; // Reset image to default
        //         selectedImage.style.display = 'none'; // Hide image when removed
        //         inputFile.value = ''; // Clear file input
        //         fileUrlRemoved.value = '1'; // Indicate that the image has been removed
        //         removeBtn.classList.add('d-none');
        //     });
        // });
        // $(document).ready(function() {
        //     var selectedImage = document.getElementById('selectedPosterImage');
        //     var inputFile = document.getElementById('poster_url');
        //     var removeBtn = document.getElementById('removePostBtn');
        //     var fileUrlRemoved = document.getElementById('poster_url_removed');

        //     inputFile.addEventListener('change', function(event) {
        //         var file = event.target.files[0];
        //         if (file) {
        //             var reader = new FileReader();
        //             reader.onload = function(e) {
        //                 selectedImage.src = e.target.result;
        //                 selectedImage.style.display = 'block'; // Show image when a file is selected
        //                 fileUrlRemoved.value =
        //                     '0'; // Reset the hidden field when a new file is selected
        //             };
        //             reader.readAsDataURL(file);
        //             removeBtn.classList.remove('d-none');
        //         } else {
        //             selectedImage.src = '';
        //             selectedImage.style.display = 'none'; // Hide image when no file is selected
        //             inputFile.value = '';
        //             fileUrlRemoved.value = '1';

        //             removeBtn.classList.add('d-none');
        //         }
        //     });

        //     removeBtn.addEventListener('click', function(event) {
        //         event.preventDefault();
        //         selectedImage.src = 'https://dummyimage.com/600x300/cfcfcf/000000.png';
        //         selectedImage.style.display = 'none';
        //         inputFile.value = '';
        //         fileUrlRemoved.value = '1';
        //         removeBtn.classList.add('d-none');
        //     });
        // });


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
