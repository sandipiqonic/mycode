@extends('backend.layouts.app')
@section('content')

<form method="POST" id="form"
    action="{{ isset($data) ? route('backend.users.update', $data->id) : route('backend.users.store') }}"
    enctype="multipart/form-data" data-toggle="validator">
    @csrf

    <div class="row">
        <div class="row">
            <div class="col-6">
                <div class="text-center mb-3">
                    <img id="selectedImage"
                        src="{{ isset($data) ? $data->profile_image : 'https://dummyimage.com/600x300/cfcfcf/000000.png' }}"
                        alt="feature-image" class="img-fluid mb-2 avatar-80 avatar-rounded" />
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <input type="file" id="profile_image" class="form-control d-none" name="profile_image"
                            accept=".jpeg, .jpg, .png, .gif" />
                        <label for="profile_image" class="btn btn-info">Upload</label>
                        <button id="removeBtn" class="btn btn-danger">Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 mb-3">
                <label for="first_name" class="form-label">{{ __('users.lbl_first_name') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{ old('first_name', $data->first_name ?? '') }}"
                    name="first_name" id="first_name" placeholder="{{ __('placeholder.lbl_user_first_name') }}" require>
                <div class="help-block with-errors text-danger"></div>
                @error('first_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                <label for="last_name" class="form-label">{{ __('users.lbl_last_name') }}<span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{ old('last_name', $data->last_name ?? '') }}"
                    name="last_name" id="last_name" placeholder="{{ __('placeholder.lbl_user_last_name') }}">
                @error('last_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-sm-6 mb-3">
                <label for="email" class="form-label">{{ __('users.lbl_email') }}<span class="text-danger">*</span></label>
                <input type="email" class="form-control" value="{{ old('email', $data->email ?? '') }}" name="email"
                    id="email" placeholder="{{ __('placeholder.lbl_user_email') }}">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-sm-6 mb-3">
                <label for="mobile" class="form-label">{{ __('users.lbl_contact_number') }}<span class="text-danger">*</span></label>
                <input type="tel" class="form-control" value="{{ old('mobile', $data->mobile ?? '') }}" name="mobile"
                    id="mobile" placeholder="{{ __('placeholder.lbl_user_conatct_number') }}">
                @error('mobile')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            @if (!isset($data->id))
            <div class="col-sm-6 mb-3">
                <label for="password" class="form-label">{{ __('users.lbl_password') }}<span class="text-danger">*</span></label>
                <input type="password" class="form-control" value="{{ old('password', $data->password ?? '') }}"
                    name="password" id="password" placeholder="{{ __('placeholder.lbl_user_password') }}">
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-sm-6 mb-3">
                <label for="password_confirmation" class="form-label">{{ __('users.lbl_confirm_password') }}<span
                        class="text-danger">*</span></label>
                <input type="password" class="form-control"
                    value="{{ old('password_confirmation', $data->password_confirmation ?? '') }}"
                    name="password_confirmation" id="password_confirmation" placeholder="{{ __('placeholder.lbl_user_confirm_password') }}">
                @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            @endif

            <div class="col-sm-6 mb-3">
                <label class="form-label">{{ __('users.lbl_gender') }}</label><span class="text-danger">*</span>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                            {{ (old('gender', isset($data) ? $data->gender : '') == 'male') ? 'checked' : '' }}>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                            {{ (old('gender', isset($data) ? $data->gender : '') == 'female') ? 'checked' : '' }}>
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="other" value="other"
                            {{ (old('gender', isset($data) ? $data->gender : '') == 'other') ? 'checked' : '' }}>
                        <label class="form-check-label" for="other">Other</label>
                    </div>
                </div>
                @error('gender')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-sm-6 mb-3">
                <label for="date_of_birth" class="form-label">{{ __('users.lbl_date_of_birth') }} <span class="text-danger">*</span></label>
                <input type="date" class="form-control"
                    value="{{ old('date_of_birth', isset($data) ? $data->date_of_birth : '') }}" name="date_of_birth"
                    id="date_of_birth" max="{{ date('Y-m-d') }}" placeholder="{{ __('placeholder.lbl_user_date_of_birth') }}">
                @error('date_of_birth')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-sm-6 mb-3">
                <label for="address" class="form-label">{{ __('users.lbl_address') }}</label>
                <textarea class="form-control" name="address" id="address"
                placeholder="{{ __('placeholder.lbl_user_address') }}">{{ old('address', $data->address ?? '') }}</textarea>
                @error('address')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                <label for="status" class="form-label"> {{ __('users.lbl_status') }}</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="status" value="0"> <!-- Hidden input field -->
                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                        {{ old('status', $data->status ?? '') == 1 ? 'checked' : '' }}>
                </div>
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <a href="{{ route('backend.users.index') }}"><button type="button" class="btn btn-secondary">Close</button></a>
    <button type="submit" class="btn btn-primary">Save changes</button>
</form>
@endsection
@push('after-scripts')
<script>
$(document).ready(function() {
    var selectedImage = document.getElementById('selectedImage');
    var inputFile = document.getElementById('profile_image');
    var removeBtn = document.getElementById('removeBtn');
    inputFile.addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                selectedImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            if (selectedImage.src) {
                selectedImage.src = '';
                inputFile.value = '';
            }
        }
    });
    removeBtn.addEventListener('click', function() {
        event.preventDefault();
        selectedImage.src =
        'https://dummyimage.com/600x300/cfcfcf/000000.png'; // Reset image to default
        inputFile.value = ''; // Clear file input
    });
});
</script>
@endpush