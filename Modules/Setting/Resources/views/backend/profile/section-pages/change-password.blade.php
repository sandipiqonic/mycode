@extends('setting::backend.profile.profile-layout')

@section('profile-content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fa-solid fa-key"></i> Change Password</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('backend.profile.change_password') }}">
        @csrf

        <div class="form-group">
            <label for="old_password">{{ __('users.lbl_password') }}</label>
            <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="old_password" name="old_password" required>
            @error('old_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password">{{ __('users.lbl_new_password') }}</label>
            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
            @error('new_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="confirm_password">{{ __('users.lbl_confirm_password') }}</label>
            <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password" required>
            @error('confirm_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" id="submit-button">
            {{ __('Submit') }}
        </button>
    </form>
</div>
@endsection

