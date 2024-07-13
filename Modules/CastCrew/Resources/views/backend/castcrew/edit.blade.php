@extends('backend.layouts.app')
@section('content')

<div class="card">
    <div class="card-body">
        {{ html()->form('PUT' ,route('backend.castcrew.update', $data->id))
                                    ->attribute('enctype', 'multipart/form-data')
                                    ->attribute('data-toggle', 'validator')
                                    ->open()
                                }}
            <div class="col-4">
                <div class="text-center mb-3">
                    <div class="mb-3" id="selectedImageContainer1">
                        @if ($data->file_url)
                            <img src="{{ $data->file_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
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

                    {{ html()->hidden('file_url')->id('file_url1')->value($data->file_url) }}

                </div>
            </div>

            <div class="row">

                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('castcrew.lbl_name') . '<span class="text-danger">*</span>', 'name')->class('form-label')}}
                    {{
                    html()->text('name', $data->name)
                        ->class('form-control')
                        ->id('name')
                        ->placeholder(__('placeholder.lbl_cast_name'))
                    }}
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('castcrew.lbl_bio') . ' <span class="text-danger">*</span>', 'bio')->class('form-label') }}
                    {{
                    html()->textarea('bio', $data->bio)
                        ->class('form-control')
                        ->id('bio')
                        ->placeholder(__('placeholder.lbl_cast_bio'))
                }}
                    @error('bio')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-6 mb-3 d-none">
                    {{ html()->label(__('castcrew.lbl_type') . '<span class="text-danger">*</span>', 'type')->class('form-label') }}
                    {{
                                html()->select('type', [
                                        '' => 'Select Type',
                                        'actor' => 'Actor',
                                        'director' => 'Director',
                                    ], $data->type)
                                    ->class('form-control select2')
                                    ->id('type')

                            }}
                    @error('type')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('castcrew.lbl_birth_place') . '<span class="text-danger">*</span>', 'name')->class('form-label')}}
                    {{
                    html()->text('place_of_birth', $data->place_of_birth)
                        ->class('form-control')
                        ->id('place_of_birth')
                        ->placeholder(__('placeholder.lbl_cast_place_of_birth'))
                    }}
                    @error('place_of_birth')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('castcrew.lbl_dob') . '<span class="text-danger">*</span>', 'dob')->class('form-label')}}
                    {{
                    html()->date('dob', $data->dob)
                        ->class('form-control')
                        ->id('dob')
                        ->placeholder(__('placeholder.lbl_cast_dob'))
                    }}
                    @error('dob')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('castcrew.lbl_designation') , 'designation')->class('form-label')}}
                    {{
                    html()->text('designation', $data->designation)
                        ->class('form-control')
                        ->id('designation')
                        ->placeholder(__('placeholder.lbl_cast_designation'))
                    }}
                    @error('designation')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <a href="{{ route('backend.castcrew.index', ['type' =>  $data->type]) }}" class="btn btn-secondary">Close</a>

        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}
    </div>
</div>

@include('components.media-modal')
@endsection
@push('after-scripts')
{{-- <script>
$(document).ready(function() {
    var selectedImage = document.getElementById('selectedImage');
    var inputFile = document.getElementById('file_url');
    var removeBtn = document.getElementById('removeBtn');
    var fileUrlRemoved = document.getElementById('file_url_removed');
    inputFile.addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                selectedImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            if (selectedImage.src) {
                selectedImage.src = '';
                inputFile.value = '';
            }
        }
    });
    removeBtn.addEventListener('click', function() {
        event.preventDefault();
        selectedImage.src =
            'https://dummyimage.com/600x300/cfcfcf/000000.png'; // Reset image to default
        inputFile.value = ''; // Clear file input
        fileUrlRemoved.value = '1';

    });
});
</script> --}}
@endpush
