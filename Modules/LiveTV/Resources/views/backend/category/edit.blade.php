@extends('backend.layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
  {{ html()->form('POST' ,route('backend.tv-category.update', $data->id))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
        @csrf
        @method('PUT')
        <div class="row">
                <div class="col-12">
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
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('genres.lbl_name') . '<span class="text-danger">*</span>', 'name')->class('form-label')}}
                    {{
                    html()->text('name', $data->name)
                        ->class('form-control')
                        ->id('name')
                        ->placeholder(__('placeholder.lbl_genre_name'))
                    }}
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('plan.lbl_description') . ' <span class="text-danger">*</span>', 'description')->class('form-label') }}
                    {{
                    html()->textarea('description', $data->description)
                        ->class('form-control')
                        ->id('description')
                        ->placeholder(__('placeholder.lbl_genre_description'))
                }}
                    @error('description')
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
                    }}
                    </div>
                    @error('status')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
        </div>
        <a href="{{ route('backend.tv-category.index') }}" class="btn btn-secondary">Close</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}
  </div>
</div>

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
