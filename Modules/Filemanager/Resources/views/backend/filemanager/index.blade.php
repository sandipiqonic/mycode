@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="bd-example">
                <nav>
                    <div class="mb-3 nav nav-tabs p-3" id="nav-tab" role="tablist">
                        <button class="nav-link d-flex align-items-center" id="nav-upload-files-tab" data-bs-toggle="tab" data-bs-target="#nav-upload" type="button" role="tab" aria-controls="nav-upload" aria-selected="true">Upload Files</button>
                        <button class="nav-link active" id="nav-media-library-tab" data-bs-toggle="tab" data-bs-target="#nav-media" type="button" role="tab" aria-controls="nav-media" aria-selected="false">Media Library</button>
                    </div>
                </nav>
                <div class="tab-content iq-tab-fade-up" id="nav-tab-content">
                    <div class="tab-pane fade" id="nav-upload" role="tabpanel" aria-labelledby="nav-upload-files-tab">
                        {{ html()->form('POST', route('backend.filemanagers.store'))->id('uploadForm')->attribute('enctype', 'multipart/form-data')->open() }}
                        @csrf
                        <div class="col-4">
                            <div class="text-center mb-3">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    {{
                                        html()->file('file_url[]')
                                            ->id('file_url_media')
                                            ->class('form-control')
                                            ->attribute('accept', '.jpeg, .jpg, .png, .gif, .mov, .mp4, .avi')
                                            ->attribute('multiple', true)
                                    }}
                                </div>
                            </div>
                        </div>
                            <div id="uploadedImages" class="mb-3"></div>
                        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right')->id('submitButton') }}
                        {{ html()->form()->close() }}
                    </div>

                    <div class="tab-pane fade show active" id="nav-media" role="tabpanel" aria-labelledby="nav-media-library-tab">
                        <div class="row">
                            <div class="col-md-12 d-flex gap-5 flex-wrap">
                                @foreach ($mediaUrls as $mediaUrl)
                                {{-- {{ dd($mediaUrl) }} --}}
                                <div class="iq-media-images position-relative">
                                    @if (Str::endsWith($mediaUrl, ['.jpeg', '.jpg', '.png', '.gif']))
                                    <img class="img-fluid" src="{{ $mediaUrl }}" style="width: 10rem; height: 10rem;">
                                @else
                                <video width="400" controls="controls" preload="metadata" >
                                    <source src="{{ $mediaUrl }}" type="video/mp4" >
                                  </video>
                                @endif
                                    <button class="btn btn-danger position-absolute top-0 start-0 m-2 iq-button-delete" onclick="deleteImage('{{ $mediaUrl }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="snackbar" id="snackbar">
            <div class="d-flex justify-content-around align-items-center">
                <p class="mb-0">{{ session('success') }}</p>
                <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
            </div>
        </div>
    @endif
@endsection
@push('after-scripts')
<script>
    let baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
    function deleteImage(url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    })
    .then((result) => {
        if (result.isConfirmed) {
            fetch(`${baseUrl}/app/filemanagers/destroy`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ url: url })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const imgElement = document.querySelector(`img[src="${url}"]`);
                    if (imgElement) {
                        imgElement.parentElement.remove();
                    }
                }
            })
            window.location.reload()
        }
    });
}

</script>
@endpush
