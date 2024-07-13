<!-- Modal -->
<div class="modal fade modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Select Image</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bd-example">
                    <nav>
                        <div class="mb-3 nav nav-tabs p-3" id="nav-tab" role="tablist">
                            <button class="nav-link d-flex align-items-center" id="nav-upload-files-tab" data-bs-toggle="tab" data-bs-target="#nav-upload" type="button" role="tab" aria-controls="nav-upload" aria-selected="true">Upload Files</button>
                            <button class="nav-link active" id="nav-media-library-tab" data-bs-toggle="tab" data-bs-target="#nav-media" type="button" role="tab" aria-controls="nav-media" aria-selected="false">Media Library</button>
                        </div>
                    </nav>
                    <div class="tab-content iq-tab-fade-up" id="nav-tab-content">
                        <div class="tab-pane fade" id="nav-upload" role="tabpanel" aria-labelledby="nav-upload-files-tab">
                        {{
                            html()->file('file_url[]')
                                ->id('file_url_media')
                                ->class('form-control mb-3')
                                ->attribute('accept', '.jpeg, .jpg, .png, .gif, .mov, .mp4, .avi')
                                ->attribute('multiple', true)
                        }}
                            <div id="uploadedImages" class="mb-3"></div>
                        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right')->id('submitButton') }}
                        </div>
                        <div class="tab-pane fade show active" id="nav-media" role="tabpanel" aria-labelledby="nav-media-library-tab">
                            <div class="row">
                                <div class="col-md-12 d-flex gap-5 flex-wrap" id="mediaLibraryContent">
                                    @foreach ($mediaUrls as $mediaUrl)
                                    <div class="iq-media-images position-relative">
                                        @if (Str::endsWith($mediaUrl, ['.jpeg', '.jpg', '.png', '.gif']))
                                        <img class="img-fluid" src="{{ $mediaUrl }}" style="width: 10rem; height: 10rem;">
                                    @else
                                    <video width="400" controls="controls" preload="metadata" >
                                        <source src="{{ $mediaUrl }}" type="video/mp4" >
                                      </video>
                                    @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            {{ html()->button('Submit')->class('btn btn-md btn-primary mt-2')->id('mediaSubmitButton') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
