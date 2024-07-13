@extends('backend.layouts.app')

@section('content')
<form method="POST" id="form" action="{{ route('backend.constants.update', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Name Field -->
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('constant.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
            {{ html()->text('name')
                     ->value($data->name)
                     ->placeholder(__('constant.lbl_plan_name'))
                     ->class('form-control')
                     ->required() }}
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Type Field (Dropdown) -->
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('constant.lbl_type') . ' <span class="text-danger">*</span>', 'type')->class('form-control-label') }}
            <select name="type" class="form-control" required>
                <option value="">{{ __('constant.select_type') }}</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ $data->type == $type ? 'selected' : '' }}>
                        {{ $type == 'video_quality' ? __('constant.video_quality') : ($type == 'movie_language' ? __('constant.movie_language') : ($type == 'upload_type' ? __('constant.UPLOAD_URL_TYPE') : $type)) }}
                    </option>
                @endforeach
            </select>
            @error('type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Value Field -->
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('constant.lbl_value') . ' <span class="text-danger">*</span>', 'value')->class('form-control-label') }}
            {{ html()->text('value')
                     ->value($data->value)
                     ->placeholder(__('constant.lbl_value'))
                     ->class('form-control')
                     ->required() }}
            @error('value')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Status Field -->
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
            <div class="form-check form-switch">
                {{ html()->hidden('status', 0) }}
                {{
                    html()->checkbox('status', old('status', $data->status ?? 0))
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

    <a href="{{ route('backend.constants.index') }}" class="btn btn-secondary">Close</a>
    <button type="submit" class="btn btn-primary">Save changes</button>
</form>
@endsection
