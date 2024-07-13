@extends('backend.layouts.app')

@section('content')
    <form method="POST" id="form" action="{{ route('backend.tv-channel.update', $data->id) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('messages.logo'), 'poster')->class('form-control-label') }}
                <div class="text-center mb-3">
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
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                {{ html()->text('name', $data->name)->placeholder(__('placeholder.lbl_movie_name'))->class('form-control') }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('movie.lbl_description'), 'description')->class('form-label') }}
                {{ html()->textarea('description', $data->description)->class('form-control')->id('description')->placeholder(__('placeholder.lbl_movie_description')) }}
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>




    <div class="row">
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('movie.lbl_movie_access') , 'access')->class('form-control-label') }}
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="access" id="paid" value="paid"
                    onchange="showPlanSelection(this.value === 'paid')"
                    {{ $data->access == 'paid' ? 'checked' : '' }} checked>
                <label class="form-check-label" for="paid">Paid</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="access" id="free" value="free"
                    onchange="showPlanSelection(this.value === 'paid')"
                    {{ $data->access == 'free' ? 'checked' : '' }}>
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
    </div>




        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('livetv.title') . '<span class="text-danger">*</span>', 'category_id')->class('form-label') }}
                {{ html()->select('category_id', $tvcategory->pluck('name', 'id'), old('category_id', $data->category_id))->class('form-control select2')->id('category_id') }}
                @error('category_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- <div class="col-sm-6 mb-3" id="thumb_url">
                {{ html()->label(__('movie.thumb_url'), 'thumb_url')->class('form-control-label') }}
                {{ html()->text('thumb_url')->attribute('value', old('thumb_url'))->placeholder(__('movie.thumb_url'))->class('form-control') }}
                @error('thumb_url')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div> --}}
        </div>
        <div id="enable_quality_section" class="enable_quality_section">
            <div id="video-inputs-container-parent">
                <div class="row video-inputs-container">
                    <div class="col-sm-6 mb-3">
                        {{ html()->label(__('movie.type'), 'type')->class('form-label') }}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="t_url" value="t_url"
                                   onchange="showStreamtypeSelection('t_url')"
                                   {{ $data->TvChannelStreamContentMappings->type == 't_url' ? 'checked' : '' }}>
                            <label class="form-check-label" for="t_url">URL</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="t_embedded" value="t_embedded"
                                   onchange="showStreamtypeSelection('t_embedded')"
                                   {{ $data->TvChannelStreamContentMappings->type == 't_embedded' ? 'checked' : '' }}>
                            <label class="form-check-label" for="t_embedded">Embedded</label>
                        </div>
                        @error('type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6 mb-3 {{ $data->TvChannelStreamContentMappings->type == 't_url' ? '' : 'd-none' }}" id="type_url">
                        {{ html()->label(__('movie.lbl_stream_type') . '<span class="text-danger">*</span>', 'stream_type')->class('form-control-label') }}
                        {{ html()->select(
                            'stream_type',
                            $url->pluck('name', 'value')->prepend('Select Video Type', ''),
                            $data->TvChannelStreamContentMappings->stream_type
                        )->class('form-control select2')->id('stream_type')->disabled(false) }}
                        @error('stream_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6 mb-3 {{ $data->TvChannelStreamContentMappings->type == 't_embedded' ? '' : 'd-none' }}" id="type_embedded">
                        {{ html()->label(__('movie.lbl_stream_type') . '<span class="text-danger">*</span>', 'stream_type')->class('form-control-label') }}
                        {{ html()->select(
                            'stream_type',
                            $embedded->pluck('name', 'value')->prepend('Select Video Type', ''),
                            $data->TvChannelStreamContentMappings->stream_type
                        )->class('form-control select2')->id('embedded_stream_type')->disabled(true) }}
                        @error('stream_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6 mb-3 {{ $data->TvChannelStreamContentMappings->type == 't_url' ? '' : 'd-none' }}" id="server_url">
                        {{ html()->label(__('movie.server_url') . '<span class="text-danger">*</span>', 'server_url')->class('form-control-label') }}
                        {{ html()->text('server_url', $data->TvChannelStreamContentMappings->server_url)->placeholder(__('movie.server_url'))->class('form-control') }}
                    </div>
                    <div class="col-sm-6 mb-3 {{ $data->TvChannelStreamContentMappings->type == 't_url' ? '' : 'd-none' }}" id="server_url1">
                        {{ html()->label(__('movie.server_url1'), 'server_url1')->class('form-control-label') }}
                        {{ html()->text('server_url1', $data->TvChannelStreamContentMappings->server_url1)->placeholder(__('movie.server_url1'))->class('form-control') }}
                    </div>

                    <div class="col-sm-6 mb-3 {{ $data->TvChannelStreamContentMappings->type == 't_embedded' ? '' : 'd-none' }}" id="embedded_textarea">
                        {{ html()->label(__('movie.embedded') . '<span class="text-danger">*</span>', 'embedded')->class('form-control-label') }}
                        {{ html()->textarea('embedded', $data->TvChannelStreamContentMappings->embedded)->class('form-control')->id('embedded')->placeholder(__('movie.embedded')) }}
                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-6 mb-3">
            {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
            <div class="form-check form-switch">
                {{ html()->hidden('status', 0) }}
                {{ html()->checkbox('status', old('status', $data->status))->class('form-check-input')->id('status')->value(1) }}
            </div>
            @error('status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <a href="{{ route('backend.movies.index') }}" class="btn btn-secondary">Close</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
    </form>

    @include('components.media-modal')
@endsection


@push('after-scripts')
    <script>

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
        //                 selectedImage.style.display = 'block';
        //                 fileUrlRemoved.value =
        //                     '0';
        //             };
        //             reader.readAsDataURL(file);
        //             removeBtn.classList.remove('d-none');
        //         } else {
        //             selectedImage.src = '';
        //             selectedImage.style.display = 'none';
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

        function showStreamtypeSelection(selectedType) {
    var type_url = document.getElementById('type_url');
    var type_embedded = document.getElementById('type_embedded');
    var server_url = document.getElementById('server_url');
    var server_url1 = document.getElementById('server_url1');
    var embedded_textarea = document.getElementById('embedded_textarea');
    if (selectedType === 't_url') {
        type_url.classList.remove('d-none');
        type_embedded.classList.add('d-none');
        document.getElementById('stream_type').disabled = false;
        document.getElementById('embedded_stream_type').disabled = true;

        server_url.classList.remove('d-none');
        server_url1.classList.remove('d-none');
        embedded_textarea.classList.add('d-none');
    } else {
        type_url.classList.add('d-none');
        type_embedded.classList.remove('d-none');
        document.getElementById('stream_type').disabled = true;
        document.getElementById('embedded_stream_type').disabled = false;

        server_url.classList.add('d-none');
        server_url1.classList.add('d-none');
        embedded_textarea.classList.remove('d-none');
    }
}

function handleVideoUrlTypeChange(selectedTypeValue) {
    var server_url = document.getElementById('server_url');
    var embedded_textarea = document.getElementById('embedded_textarea');
    var server_url1 = document.getElementById('server_url1');

    if (selectedTypeValue === 'URL' || selectedTypeValue === 'YouTube' || selectedTypeValue === 'HLS' || selectedTypeValue === 'Vimeo') {
        server_url.classList.remove('d-none');
        embedded_textarea.classList.add('d-none');
        server_url1.classList.remove('d-none');
    } else if (selectedTypeValue === 'Embedded') {
        server_url.classList.add('d-none');
        embedded_textarea.classList.remove('d-none');
        server_url1.classList.add('d-none');
    } else {
        server_url.classList.add('d-none');
        embedded_textarea.classList.add('d-none');
        server_url1.classList.add('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var type = document.querySelector('input[name="type"]:checked').value;
    console.log(type)
    showStreamtypeSelection(type);

    document.querySelectorAll('input[name="type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            console.log(this.value)
            showStreamtypeSelection(this.value);
        });
    });

    document.getElementById('stream_type').addEventListener('change', function() {
        handleVideoUrlTypeChange(this.value);
    });

    document.getElementById('embedded_stream_type').addEventListener('change', function() {
        handleVideoUrlTypeChange(this.value);
    });
    document.querySelector('form').addEventListener('submit', function() {
        var type = document.querySelector('input[name="type"]:checked').value;
        if (type === 't_url') {
            document.getElementById('stream_type').disabled = false;
            document.getElementById('embedded_stream_type').disabled = true;
        } else {
            document.getElementById('stream_type').disabled = true;
            document.getElementById('embedded_stream_type').disabled = false;
        }
    });
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

            $('#stream_type').select2({
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

