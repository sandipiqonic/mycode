@extends('backend.layouts.app')

{{-- @section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection --}}

@section('content')
    <div class="card">
        <div class="row">
            @include('setting::backend.profile.sidebar-panel')
            @include('setting::backend.profile.main-content')
        </div>
    </div>

@endsection




@push ('after-styles')


@endpush
