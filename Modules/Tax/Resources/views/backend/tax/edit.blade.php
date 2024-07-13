@extends('backend.layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
  {{ html()->form('POST' ,route('backend.taxes.update', $data->id))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
        @csrf
        @method('PUT')
        <div class="row">
            <div class="row">
                <div class="col-sm-6 mb-3">
                  {{ html()->label(__('tax.lbl_title') . ' <span class="text-danger">*</span>', 'title')->class('form-control-label') }}
                  {{ html()->text('title')
                          ->attribute('value', $data->title)
                          ->placeholder(__('tax.lbl_title'))
                          ->class('form-control')
                          ->required() }}
                  @error('title')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>
              <div class="col-sm-6 mb-3">
                  {{ html()->label(__('tax.lbl_Type') . ' <span class="text-danger">*</span>', 'type')->class('form-control-label') }}
                  {{ html()->select('type', ['fixed' => 'Fixed', 'percentage' => 'Percentage'])
                          ->class('form-control')
                          ->required() }}
                  @error('type')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>
              <div class="col-sm-6 mb-3">
                  {{ html()->label(__('tax.lbl_value') . ' <span class="text-danger">*</span>', 'value')->class('form-control-label') }}
                  {{ html()->text('value')
                          ->attribute('value',$data->value)
                          ->placeholder(__('tax.lbl_value'))
                          ->class('form-control')
                          ->required() }}
                  @error('value')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>
              <div class="col-sm-6 mb-3">
                  {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                  <div class="form-check form-switch">
                      {{ html()->hidden('status', 0) }}
                      {{
                      html()->checkbox('status',$data->status)
                          ->class('form-check-input')
                          ->id('status')
                      }}
                  </div>
                  @error('status')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
              </div>
            </div>
        </div>
        <a href="{{ route('backend.taxes.index') }}" class="btn btn-secondary">Close</a>
        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}
  </div>
</div>
@endsection
