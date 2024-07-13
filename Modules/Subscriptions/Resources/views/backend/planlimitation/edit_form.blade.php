@extends('backend.layouts.app')
@section('content')

<div class="card">
    <div class="card-body">
        {{ html()->form('PUT' ,route('backend.planlimitation.update', $data->id))
                                    ->attribute('enctype', 'multipart/form-data')
                                    ->attribute('data-toggle', 'validator')
                                    ->open()
                                }}

        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan_limitation.lbl_title') . ' <span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                {{ html()->text('title')
                            ->attribute('value',$data->title)->placeholder(__('placeholder.lbl_plan_limit_title'))
                            ->class('form-control')
                        }}
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_description') . ' <span class="text-danger">*</span>', 'description')->class('form-control-label') }}
                {{ html()->textarea('description', $data->description)
                ->placeholder(__('placeholder.lbl_plan_limit_description'))
                ->class('form-control')
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
                            html()->checkbox('status',$data->status )
                                ->class('form-check-input')
                                ->id('status')
                        }}
                </div>
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <a href="{{ route('backend.planlimitation.index') }}" class="btn btn-secondary">Close</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}
    </div>
</div>

@endsection
