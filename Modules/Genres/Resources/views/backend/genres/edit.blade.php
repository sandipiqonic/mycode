@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        {{ html()->form('PUT', route('backend.genres.update', $genre->id))
                ->attribute('enctype', 'multipart/form-data')
                ->attribute('data-toggle', 'validator')
                ->open()
        }}

<div class="mb-3" id="selectedImageContainer1">
    @if ($genre->file_url)
        <img src="{{ $genre->file_url }}" class="img-fluid mb-2" style="max-width: 100px; max-height: 100px;">
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

{{ html()->hidden('file_url')->id('file_url1')->value($genre->file_url) }}

        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('genres.lbl_name') . '<span class="text-danger">*</span>', 'name')->class('form-label')}}
                {{
                    html()->text('name', $genre->name)
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
                    html()->textarea('description', $genre->description)
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
                        html()->checkbox('status', $genre->status)
                            ->class('form-check-input')
                            ->id('status')
                            ->value(1)
                    }}
                </div>
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}

        {{ html()->form()->close() }}
    </div>
</div>

@include('components.media-modal')

@endsection
