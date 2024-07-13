@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            {{ html()->form('PUT', route('backend.banners.update', $banner->id))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
            @csrf
            <div class="row">
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('banner.lbl_title') . '<span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                    {{ html()->text('title', $banner->title)->placeholder('Enter title')->class('form-control') }}
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-6 position-relative">
                    {{ html()->label(__('banner.lbl_image'), 'file_url')->class('form-control-label') }}
                    <div class="text-center mb-3">
                        <div class="mb-3" id="selectedImageContainer1">
                            @if ($banner->file_url)
                                <img src="{{ $banner->file_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
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

                        {{ html()->hidden('file_url')->id('file_url1')->value($banner->file_url) }}
                        {{-- <img id="selectedImage" src="{{ old('file_url', isset($banner) ? $banner->file_url : '') }}"
                            alt="banner-image" class="img-fluid mb-2 avatar-80"
                            style="{{ old('file_url', isset($banner) ? $banner->file_url : '') ? '' : 'display:none;' }}" />
                        <button type="button"
                            class="btn btn-danger btn-sm position-absolute close-icon {{ old('file_url', isset($banner) ? $banner->file_url : '') ? '' : 'd-none' }}"
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
                    {{ html()->select('type', $types, old('type', $banner->type))->class('form-control')->id('type') }}
                    @error('type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('banner.lbl_name') . '<span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                    {{ html()->select('name_id', [], old('name_id', $banner->type_id))->class('form-control select2')->id('name_id') }}
                    @error('name_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{ html()->hidden('type_id', old('type_id', $banner->type_id))->id('type_id') }}
            {{ html()->hidden('type_name', old('type_name', $banner->type_name))->id('type_name') }}

            <div class="row">
                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('banner.lbl_status'), 'status')->class('form-label') }}
                    <div class="form-check form-switch">
                        {{ html()->hidden('status', 0) }}
                        {{ html()->checkbox('status', old('status', $banner->status))->class('form-check-input')->id('status')->value(1) }}
                    </div>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <a href="{{ route('backend.banners.index') }}" class="btn btn-secondary">{{__('messages.close')}}</a>
            {{ html()->submit(__('messages.save'))->class('btn btn-md btn-primary float-right') }}
            {{ html()->form()->close() }}
        </div>
    </div>

@include('components.media-modal')
@endsection
{{-- 
@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            function readURL(input, imgElement) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        imgElement.attr('src', e.target.result).show();
                        $('#removeImageBtn').removeClass('d-none');
                        $('#file_url_removed').val(0);
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
                $('#file_url_removed').val(1);
            });

            const namesCache = {};

            async function fetchNames(type) {
                if (namesCache[type]) {
                    return namesCache[type];
                }

                const response = await fetch(
                    "{{ route('backend.banners.index_list', ['type' => ':type']) }}".replace(':type',
                        type));
                const result = await response.json();
                namesCache[type] = Object.entries(result).map(([id, name]) => ({
                    id,
                    text: name
                }));
                return namesCache[type];
            }

            async function getNames(type, selectedNameId = "") {
                const formattedResult = await fetchNames(type);

                $('#name_id').select2({
                    width: '100%',
                    data: formattedResult
                });

                if (selectedNameId) {
                    $('#name_id').val(selectedNameId).trigger('change');
                }
            }

            $('#type').select2({
                placeholder: 'Select type',
                width: '100%'
            });

            $('#type').change(async function() {
                const type = $(this).val();
                if (type) {
                    $('#name_id').empty();
                    await getNames(type);
                } else {
                    $('#name_id').empty();
                }
            });

            const initialType = $('#type').val();
            const initialTypeId = '{{ old('name_id', $banner->type_id) }}';
            if (initialType) {
                await getNames(initialType, initialTypeId);
            }

            $('#name_id').change(function() {
                const nameId = $(this).val();
                const nameText = $('#name_id option:selected').text();

                $('#type_id').val(nameId);
                $('#type_name').val(nameText);
            });

            // Set hidden fields when form is submitted
            $('form').on('submit', function() {
                $('#type_id').val($('#name_id').val());
                $('#type_name').val($('#name_id option:selected').text());
            });
        });
    </script>
@endpush  --}}
