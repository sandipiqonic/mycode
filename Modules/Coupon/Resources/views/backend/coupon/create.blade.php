@extends('backend.layouts.app')

@section('content')
<div class="card">
 <div class="card-body">
    {{ html()->form('POST' ,route('backend.coupons.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
      @csrf
      <div class="row">
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('coupon.lbl_coupon_code') .' <span class="text-danger">*</span>', 'coupon_code')->class('form-label') }}
            {{ html()->text('coupon_code')
                    ->value(old('coupon_code'))
                    ->placeholder('Enter coupon code')
                    ->class('form-control')
                    ->required() }}
            @error('coupon_code')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('coupon.lbl_type').' <span class="text-danger">*</span>', 'type')->class('form-label') }}
            {{ html()->select('type', ['fixed' => 'Fixed', 'percentage' => 'Percentage'])
              ->value(old('type'))
              ->class('form-control')
              ->required() }}
            @error('type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('coupon.lbl_value').' <span class="text-danger">*</span>', 'value')->class('form-label') }}
            {{ html()->text('value')
                    ->value(old('value'))
                    ->placeholder('Enter value')
                    ->class('form-control')
                    ->required() }}
            @error('value')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6 mb-3">
            {{ html()->label(__('coupon.lbl_usage').' <span class="text-danger">*</span>', 'coupon_usage')->class('form-label') }}
            {{ html()->text('coupon_usage')
                    ->value(old('coupon_usage'))
                    ->placeholder('Enter coupon usage')
                    ->class('form-control') }}
            @error('coupon_usage')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6 d-flex flex-column justify-content-center">
            {{ html()->label(__('coupon.lbl_expiry_date').' <span class="text-danger">*</span>', 'expiry_date')->class('form-label') }}
            {{ html()->date('expiry_date')
                    ->value(old('expiry_date'))
                    ->class('form-control')
                    ->required() }}
        </div>
        <div class="col-sm-6 mb-3 d-flex align-items-center gap-3">
            {{ html()->label('Status', 'status')->class('form-label mb-0') }}
            <div class="form-check form-switch">
                {{ html()->hidden('status', 0) }}
                {{ html()->checkbox('status', 1, old('status') == 1)
                        ->class('form-check-input')
                        ->id('status') }}
            </div>
        </div>
    </div>

      <a href="{{ route('backend.coupons.index') }}" class="btn btn-secondary">Close</a>
      {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
    {{ html()->form()->close() }}
  </div>
</div>
@endsection
