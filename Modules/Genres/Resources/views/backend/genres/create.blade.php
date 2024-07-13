@extends('backend.layouts.app')

@section('content')
        <div class="card">
            <div class="card-body">
                {{ html()->form('POST', route('backend.genres.store'))
                    ->attribute('enctype', 'multipart/form-data')
                    ->attribute('data-toggle', 'validator')
                    ->open()
                }}
                <div class="row">
                    <div class="col-md-12 mb-3">   
                    {{ html()->label(__('Image') . '<span class="text-danger"> *</span>', 'Image')->class('form-label')}}                     
                        <div class="input-group btn-file-upload">
                            {{ html()->button(__('messages.select'))
                                ->class('input-group-text form-control')
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
                        <div class="my-3" id="selectedImageContainerThumbnail">
                            @if(old('file_url', isset($data) ? $data->file_url : ''))
                                <img src="{{ old('file_url', isset($data) ? $data->file_url : '') }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
                            @endif
                        </div>
                    </div>
                    {{ html()->hidden('file_url')->id('file_url_image')->value(old('file_url', isset($data) ? $data->file_url : '')) }}
                    <div class="col-md-6 mb-3">
                        <div class="mb-3">
                            {{ html()->label(__('genres.lbl_name') . '<span class="text-danger"> *</span>', 'name')->class('form-label')}}
                            {{
                                html()->text('name', old('name'))
                                    ->class('form-control')
                                    ->id('name')
                                    ->placeholder(__('placeholder.lbl_genre_name'))
                            }}
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                            <div class="d-flex justify-content-between align-items-center form-control">
                                {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label mb-0') }}
                                <div class="form-check form-switch">
                                    {{ html()->hidden('status', 0) }}
                                    {{
                                        html()->checkbox('status', old('status', 1))
                                            ->class('form-check-input')
                                            ->id('status')
                                            ->value(1)
                                    }}
                                </div>
                            </div>
                            
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ html()->label(__('plan.lbl_description') . ' <span class="text-danger"> *</span>', 'description')->class('form-label') }}
                            {{
                                html()->textarea('description', old('description'))
                                    ->class('form-control')
                                    ->id('description')
                                    ->placeholder(__('placeholder.lbl_genre_description'))
                                    ->rows('5')
                            }}
                            @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <div class="d-grid d-sm-flex justify-content-sm-end gap-3">
                            <a href="{{ route('backend.genres.index') }}" class="btn btn-dark">{{__('messages.close')}}</a>
                            {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
                        </div>
                    </div>               

                    {{ html()->form()->close() }}
                </div>        
            </div>
        </div>
        

@include('components.media-modal')

@endsection
