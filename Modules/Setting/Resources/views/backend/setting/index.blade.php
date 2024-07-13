@extends('backend.layouts.app')

{{-- @section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection --}}

@section('content')
    <div class="card">
        <div class="row">
            @include('setting::backend.setting.sidebar-panel')
            @include('setting::backend.setting.main-content')
        </div>
    </div>

@endsection




@push ('after-styles')


@endpush
