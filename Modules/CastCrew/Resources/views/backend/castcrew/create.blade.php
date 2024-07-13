@extends('backend.layouts.app')
@section('content')

<div class="card">
    <div class="card-body">
        {{ html()->form('POST' ,route('backend.castcrew.store'))
                                    ->attribute('enctype', 'multipart/form-data')
                                    ->attribute('data-toggle', 'validator')
                                    ->open()
                                }}


<div class="mb-3" id="selectedImageContainerCastcerw">
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
        ->attribute('data-image-container', 'selectedImageContainerCastcerw')
        ->attribute('data-hidden-input', 'file_url_image')
    }}

    {{ html()->text('castcrew_input')
        ->class('form-control')
        ->placeholder('Select Image')
        ->attribute('aria-label', 'Castcrew Image')
        ->attribute('data-bs-toggle', 'modal')
        ->attribute('data-bs-target', '#exampleModal')
        ->attribute('data-image-container', 'selectedImageContainerCastcrew')
    }}
</div>

{{ html()->hidden('file_url')->id('file_url_image')->value(old('file_url', isset($data) ? $data->file_url : '')) }}

        <div class="row">

                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('castcrew.lbl_name') . '<span class="text-danger">*</span>', 'name')->class('form-label')}}
                    {{
                    html()->text('name', old('name'))
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
                    html()->textarea('bio', old('bio'))
                        ->class('form-control')
                        ->id('bio')
                        ->placeholder(__('placeholder.lbl_cast_bio'))
                }}
                    @error('bio')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-6 mb-3 d-none" >
                    {{ html()->label(__('castcrew.lbl_type') . '<span class="text-danger">*</span>', 'type')->class('form-label') }}
                    {{
                                html()->select('type', [
                                        '' => 'Select Type',
                                        'actor' => 'Actor',
                                        'director' => 'Director',
                                    ],$type)
                                    ->class('form-control select2')
                                    ->id('type')
                                    ->attribute('readonly')

                            }}
                    @error('type')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-sm-6 mb-3">
                    {{ html()->label(__('castcrew.lbl_birth_place') . '<span class="text-danger">*</span>', 'name')->class('form-label')}}
                    {{
                    html()->text('place_of_birth', old('place_of_birth'))
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
                    html()->date('dob', old('dob'))
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
                    html()->text('designation', old('designation'))
                        ->class('form-control')
                        ->id('designation')
                        ->placeholder(__('placeholder.lbl_cast_designation'))
                    }}
                    @error('designation')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


            </div>

         <a href="{{ route('backend.castcrew.index', ['type' => $type]) }}" class="btn btn-secondary">Close</a>

        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}
    </div>
</div>


@include('components.media-modal')

@endsection


