@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            {{ html()->form('POST', route('backend.banners.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
            @csrf
            <div class="row">
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('banner.lbl_title') . '<span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                    {{ html()->text('title')->attribute('value', old('title'))->placeholder(__('placeholder.lbl_banner_title'))->class('form-control') }}
                    {{ html()->text('title')->attribute('value', old('title'))->placeholder(__('placeholder.lbl_banner_title'))->class('form-control') }}
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-6 position-relative">
                    {{ html()->label(__('banner.lbl_image'), 'file_url')->class('form-control-label') }}
                    <div class="text-center mb-3">
                        <div class="mb-3" id="selectedImageContainerThumbnail">
                            @if(old('file_url', isset($data) ? $data->file_url : ''))
                                <img src="{{ old('file_url', isset($data) ? $data->file_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                            @endif
                        </div>

                        <div class="input-group col-sm-6 mb-3">
                            {{ html()->button('Select')
                                ->class('input-group-text')
                                ->type('button')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                                ->attribute('data-hidden-input', 'file_url_image')
                            }}

                            {{ html()->text('thumbnail_input')
                                ->class('form-control')
                                ->placeholder(__('placeholder.lbl_image'))
                                ->attribute('aria-label', 'Thumbnail Image')
                                ->attribute('data-bs-toggle', 'modal')
                                ->attribute('data-bs-target', '#exampleModal')
                                ->attribute('data-image-container', 'selectedImageContainerThumbnail')
                            }}
                        </div>

                        {{ html()->hidden('file_url')->id('file_url_image')->value(old('file_url', isset($data) ? $data->file_url : '')) }}
                        {{-- <img id="selectedImage" src="{{ old('file_url', isset($data) ? $data->file_url : '') }}"
                            alt="banner-image" class="img-fluid mb-2 avatar-80"
                            style="{{ old('file_url', isset($data) ? $data->file_url : '') ? '' : 'display:none;' }}" />
                        <button
                            class="btn btn-danger btn-sm position-absolute close-icon {{ old('file_url', isset($data) ? $data->file_url : '') ? '' : 'd-none' }}"
                            id="removeImageBtn">&times;</button>
                        <div class="d-flex align-items-center justify-content-center gap-2 mt-3">
                            {{ html()->file('file_url')->id('file_url')->class('form-control')->attribute('accept', '.jpeg, .jpg, .png, .gif') }}
                            {{ html()->hidden('file_url_removed', 0)->id('file_url_removed') }}
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('banner.lbl_type') . '<span class="text-danger">*</span>', 'type')->class('form-control-label') }}
                    {{ html()->select('type', ['' => 'Select type'] + $types, old('type'))->class('form-control')->id('type') }}
                    @error('type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('banner.lbl_name') . '<span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                    {{ html()->select('name_id', ['' => 'Select name'] + [], old('name_id'))->class('form-control select2')->id('name_id') }}
                    @error('name_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{ html()->hidden('type_id')->id('type_id') }}
            {{ html()->hidden('type_name')->id('type_name') }}


            <div class="row">
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('banner.lbl_status'), 'status')->class('form-label') }}
                    <div class="form-check form-switch">
                        {{ html()->hidden('status', 0) }}
                        {{ html()->checkbox('status', old('status', 1))->class('form-check-input')->id('status')->value(1) }}
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <a href="{{ route('backend.banners.index') }}" class="btn btn-secondary">Close</a>
            {{ html()->submit('Save')->class('btn btn-md btn-primary float-right') }}
            {{ html()->form()->close() }}
        </div>
    </div>

    @include('components.media-modal')
@endsection

@push('after-scripts')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            function readURL(input, imgElement) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        imgElement.attr('src', e.target.result).show();
                        $('#removeImageBtn').removeClass('d-none');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $('#file_url').change(function() {
                readURL(this, $('#selectedImage'));
            });

            $('#removeImageBtn').click(function() {
                $('#selectedImage').attr('src', '').hide();
                $('#file_url').val('');
                $(this).addClass('d-none');
            });
        });

        function getNames(type, selectedNameId = "") {
            var get_names_list = "{{ route('backend.banners.index_list', ['type' => ':type']) }}".replace(':type', type);

            $.ajax({
                url: get_names_list,
                success: function(result) {
                    var formattedResult = Object.entries(result).map(function([id, name]) {
                        return {
                            id: id,
                            text: name
                        };
                    });

                    $('#name_id').select2({
                        width: '100%',
                        data: formattedResult
                    });

                    if (selectedNameId != "") {
                        $('#name_id').val(selectedNameId).trigger('change');
                    }
                }
            });
        }

        $(document).ready(function() {
            $('#type').select2({
                placeholder: 'Select type',
                width: '100%'
            });

            $('#type').change(function() {
                var type = $(this).val();
                if (type) {
                    $('#name_id').empty();
                    getNames(type);
                } else {
                    $('#name_id').empty();
                }
            });

            $('#name_id').change(function() {
                var nameId = $(this).val();
                var nameText = $('#name_id option:selected').text();

                $('#type_id').val(nameId);
                $('#type_name').val(nameText);
            });

            $('form').on('submit', function() {
                $('#type_id').val($('#name_id').val());
                $('#type_name').val($('#name_id option:selected').text());
            });
        });
    </script> --}}
@endpush

