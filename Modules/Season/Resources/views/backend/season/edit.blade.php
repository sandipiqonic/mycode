@extends('backend.layouts.app')

@section('content')


{{ html()->form('PUT' ,route('backend.seasons.update', $data->id))
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
                            ->placeholder((__('placeholder.lbl_image')))
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
                {{ html()->text('name')->attribute('value', $data->name)->placeholder(__('placeholder.lbl_season_name'))->class('form-control') }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


    <div class="col-sm-6 mb-3">
        {{ html()->label(__('season.lbl_tv_shows') . ' <span class="text-danger">*</span>', 'type')->class('form-label') }}
        {{ html()->select(
                'entertainment_id',
                $tvshows->pluck('name', 'id')->prepend(__('placeholder.lbl_select_tvshow'),''), $data->entertainment_id

            )->class('form-control select2')->id('entertainment_id') }}
        @error('entertainment_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6 mb-3">
        {{ html()->label(__('movie.lbl_short_desc'), 'short_desc')->class('form-label') }}
        {{ html()->textarea('short_desc', $data->short_desc)->class('form-control')->id('short_desc')->placeholder(__('placeholder.lbl_season_short_desc')) }}
        @error('short_desc')
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

                {{ html()->hidden('trailer_url')->id('file_url3')->value($data->trailer_url) }}

                {{-- {{ html()->file('trailer_video')->class('form-control-file')->accept('video/*')->class('form-control') }} --}}

                @error('trailer_video')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_movie_access') , 'movie_access')->class('form-control-label') }}
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
                @error('access')
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

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                {{ html()->textarea('description',$data->description)->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description')) }}
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <a href="{{ route('backend.seasons.index') }}" class="btn btn-secondary">{{__('messages.close')}}</a>

        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}

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

