@extends('backend.layouts.app')
@section('content')

<form method="POST" id="form"
    action="{{ route('backend.users.update_password', $id) }}"
   data-toggle="validator">
    @csrf
   
        
        <div class="row">

          <div class="col-sm-6 mb-3">
                <label for="old_password" class="form-label">{{ __('users.lbl_old_password') }}<span class="text-danger">*</span></label>
                <input type="password" class="form-control" value="{{ old('old_password', $data->old_password ?? '') }}"
                    name="old_password" id="old_password" placeholder="Enter old_password">
                @error('old_password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
         
            <div class="col-sm-6 mb-3">
                <label for="password" class="form-label">{{ __('users.lbl_password') }}<span class="text-danger">*</span></label>
                <input type="password" class="form-control" value="{{ old('password', $data->password ?? '') }}"
                    name="password" id="password" placeholder="Enter Password">
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-sm-6 mb-3">
                <label for="password_confirmation" class="form-label">{{ __('users.lbl_confirm_password') }}<span
                        class="text-danger">*</span></label>
                <input type="password" class="form-control"
                    value="{{ old('password_confirmation', $data->password_confirmation ?? '') }}"
                    name="password_confirmation" id="password_confirmation" placeholder="Enter Confirm Password">
                @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
       
        </div>
   
    <a href="{{ route('backend.users.index') }}"><button type="button" class="btn btn-secondary">Close</button></a>
    <button type="submit" class="btn btn-primary">Save changes</button>
</form>
@endsection
