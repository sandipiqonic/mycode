@extends('backend.layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
  {{ html()->form('POST' ,route('backend.pages.update', $data->id))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
        @csrf
        @method('PUT')
        {{-- Name row --}}
        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('page.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
            {{ html()->text('name')
                        ->attribute('value', $data->name)  
                        ->placeholder(__('page.lbl_name'))
                        ->class('form-control')
                    }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        {{-- DESCRIPTION row --}}
      <div class="row">
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('page.lbl_description') . ' <span class="text-danger">*</span>', 'description')->class('form-control-label') }}
            {{ html()->textarea('description', $data->description)
                    ->placeholder(__('page.lbl_description'))
                    ->class('form-control')
            }}
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

{{-- STATUS SCROLL --}}
    <div class="col-sm-6 mb-3">
        {{ html()->label(__('page.lbl_status'), 'status')->class('form-label') }}
        <div class="form-check form-switch">
            {{ html()->hidden('status', 0) }}
            {{
                html()->checkbox('status', old('status', 1))
                    ->class('form-check-input')
                    ->id('status')
                    ->value(1)
            }}
        </div>
        @error('status')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

        <a href="{{ route('backend.pages.index') }}" class="btn btn-secondary">Close</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}
  </div>
</div>
@endsection
