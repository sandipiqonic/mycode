@extends('setting::backend.profile.profile-layout')

@section('profile-content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="fa-solid fa-user"></i> Personal Information</h2>
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

    <form method="POST" action="{{ route('backend.profile.information-update') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="first_name">{{ __('profile.lbl_first_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">{{ __('profile.lbl_last_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">{{ __('profile.lbl_email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mobile">{{ __('profile.lbl_contact_number') }} <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                            @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <img src="{{ $user->profile_image ?? 'https://dummyimage.com/600x300/cfcfcf/000000.png' }}" class="img-fluid avatar avatar-120 avatar-rounded mb-2" alt="profile-image" />
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <input type="file" class="form-control d-none" id="profile_image" name="profile_image" accept=".jpeg, .jpg, .png, .gif" onchange="previewImage(event)" />
                        <label class="btn btn-info" for="profile_image">{{ __('messages.upload') }}</label>
                        <button type="button" class="btn btn-danger" onclick="removeImage()" id="removeImageButton" style="display: none;">Remove</button>
                    </div>
                    @error('profile_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="" class="w-100">{{ __('profile.lbl_gender') }}</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }} />
                        <label class="form-check-label" for="male"> Male </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }} />
                        <label class="form-check-label" for="female"> Female </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="other" value="other" {{ old('gender', $user->gender) == 'other' ? 'checked' : '' }} />
                        <label class="form-check-label" for="other"> Intersex </label>
                    </div>
                    @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="show_in_calender" id="show_in_calender" value="1" {{ old('show_in_calender', $user->show_in_calender) ? 'checked' : '' }} />
                        <label class="form-check-label" for="show_in_calender">
                            {{ __('profile.lbl_show_in_calender') }}
                        </label>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('after-scripts')


<script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function() {
            const image = document.querySelector('.avatar-rounded');
            image.src = reader.result;
            document.getElementById('removeImageButton').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }

    function removeImage() {
        const image = document.querySelector('.avatar-rounded');
        image.src = 'https://dummyimage.com/600x300/cfcfcf/000000.png';
        document.getElementById('profile_image').value = '';
        document.getElementById('removeImageButton').style.display = 'none';
    }
</script>
@endpush
