@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('content')
    <form method="POST" action="{{ route('backend.setting.store') }}">
        @csrf
        {{-- Social Login Section --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center ">
                <label class="form-label m-0" for="is_social_login">Social Login</label>
                <input type="hidden" value="0" name="is_social_login">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#social-login-section" value="1"
                        name="is_social_login" id="is_social_login" type="checkbox"
                        {{ old('is_social_login', $data['is_social_login'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="social-login-section"
            class="{{ old('is_social_login', $data['is_social_login'] ?? 0) == 1 ? '' : 'd-none' }}">
            <ul class="list-group">
                {{-- Google Login --}}
                <li class="list-group-item border-bottom pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label m-0" for="is_google_login">Google Login</label>
                        <input type="hidden" value="0" name="is_google_login">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input toggle-input" data-toggle-target="#google-key-field"
                                value="1" name="is_google_login" id="is_google_login" type="checkbox"
                                {{ old('is_google_login', $data['is_google_login'] ?? 0) == 1 ? 'checked' : '' }} />
                        </div>
                    </div>
                    <div id="google-key-field"
                        class="{{ old('is_google_login', $data['is_google_login'] ?? 0) == 1 ? '' : 'd-none' }}">
                        <!-- Place input field or any other content for Google Login here -->
                    </div>
                </li>
                {{-- OTP Login --}}
                <li class="list-group-item border-bottom pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label m-0" for="is_otp_login">OTP Login</label>
                        <input type="hidden" value="0" name="is_otp_login">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input toggle-input" data-toggle-target="#otp-key-field" value="1"
                                name="is_otp_login" id="is_otp_login" type="checkbox"
                                {{ old('is_otp_login', $data['is_otp_login'] ?? 0) == 1 ? 'checked' : '' }} />
                        </div>
                    </div>
                    <div id="otp-key-field"
                        class="{{ old('is_otp_login', $data['is_otp_login'] ?? 0) == 1 ? '' : 'd-none' }}">
                        <!-- Place input field or any other content for OTP Login here -->
                    </div>
                </li>
                {{-- Apple Login --}}
                <li class="list-group-item border-bottom pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label m-0" for="is_apple_login">Apple Login</label>
                        <input type="hidden" value="0" name="is_apple_login">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input toggle-input" data-toggle-target="#apple-key-field"
                                value="1" name="is_apple_login" id="is_apple_login" type="checkbox"
                                {{ old('is_apple_login', $data['is_apple_login'] ?? 0) == 1 ? 'checked' : '' }} />
                        </div>
                    </div>
                    <div id="apple-key-field"
                        class="{{ old('is_apple_login', $data['is_apple_login'] ?? 0) == 1 ? '' : 'd-none' }}">
                        <!-- Place input field or any other content for Apple Login here -->
                    </div>
                </li>
            </ul>
        </div>

        {{-- Firebase Notification --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="is_firebase_notification">Firebase Notification</label>
                <input type="hidden" value="0" name="is_firebase_notification">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#firebase-key-field" value="1"
                        name="is_firebase_notification" id="is_firebase_notification" type="checkbox"
                        {{ old('is_firebase_notification', $data['is_firebase_notification'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="firebase-key-field"
            class="{{ old('is_firebase_notification', $data['is_firebase_notification'] ?? 0) == 1 ? '' : 'd-none' }}">
            <div class="col-md-6">
                <input type="text" class="form-control @error('firebase_key') is-invalid @enderror" name="firebase_key"
                    id="firebase_key" value="{{ old('firebase_key', $data['firebase_key'] ?? '') }}"
                    placeholder="Firebase Key">
                @error('firebase_key')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>


        {{-- User Push Notification --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="is_user_push_notification">User Push Notification</label>
                <input type="hidden" value="0" name="is_user_push_notification">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#user-push-key-field" value="1"
                        name="is_user_push_notification" id="is_user_push_notification" type="checkbox"
                        {{ old('is_user_push_notification', $data['is_user_push_notification'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="user-push-key-field"
            class="{{ old('is_user_push_notification', $data['is_user_push_notification'] ?? 0) == 1 ? '' : 'd-none' }}">
            <!-- Place input field or any other content for User Push Notification here -->
        </div>

        {{-- Application Links --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="is_application_link">Application Links</label>
                <input type="hidden" value="0" name="is_application_link">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#application-links-section"
                        value="1" name="is_application_link" id="is_application_link" type="checkbox"
                        {{ old('is_application_link', $data['is_application_link'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="application-links-section"
            class="{{ old('is_application_link', $data['is_application_link'] ?? 0) == 1 ? '' : 'd-none' }}">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control @error('ios_url') is-invalid @enderror" name="ios_url"
                        id="ios_url" value="{{ old('ios_url', $data['ios_url'] ?? '') }}" placeholder="iOS URL">
                    @error('ios_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control @error('android_url') is-invalid @enderror"
                        name="android_url" id="android_url" value="{{ old('android_url', $data['android_url'] ?? '') }}"
                        placeholder="Android URL">
                    @error('android_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Force Update --}}
        <div class="form-group border-bottom pb-3 ">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="force_update">Force Update</label>
                <input type="hidden" value="0" name="force_update">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#force-update-field" value="1"
                        name="force_update" id="force_update"
                        type="checkbox"{{ old('force_update', $data['force_update'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>

        <div id="force-update-field" class="{{ old('force_update', $data['force_update'] ?? 0) == 1 ? '' : 'd-none' }}">

            {{-- Enter App Version and Message --}}
            <div class="form-group border-bottom pb-3">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('app_version') is-invalid @enderror"
                            name="app_version" id="app_version"
                            value="{{ old('app_version', $data['app_version'] ?? '') }}" placeholder="Enter App Version">
                        @error('app_version')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('message_text') is-invalid @enderror"
                            name="message_text" id="message_text"
                            value="{{ old('message_text', $data['message_text'] ?? '') }}" placeholder="Enter Message">
                        @error('message_text')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        {{-- Submission --}}
        <div class="form-group mt-3 text-end">
            <button class="btn btn-primary w-sm" type="submit">{{ __('settings.appconfigsave') }}</button>
        </div>
    </form>
@endsection

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function toggleSection(checkbox) {
                const targetId = checkbox.getAttribute('data-toggle-target');
                const targetElement = document.querySelector(targetId);
                if (checkbox.checked) {
                    targetElement.classList.remove('d-none');
                } else {
                    targetElement.classList.add('d-none');
                }
            }

            document.querySelectorAll('.toggle-input').forEach(function(checkbox) {
                toggleSection(checkbox);
                checkbox.addEventListener('change', function() {
                    toggleSection(checkbox);
                });
            });

            const firebaseCheckbox = document.getElementById('is_firebase_notification');
            firebaseCheckbox.addEventListener('change', function() {
                toggleSection(firebaseCheckbox);
                if (firebaseCheckbox.checked) {
                    document.getElementById('force_update').checked = false;
                    document.getElementById('enter_app_version').checked = false;
                    document.getElementById('message').checked = false;
                    document.getElementById('force-update-field').classList.add('d-none');
                    document.getElementById('enter-app-version-field').classList.add('d-none');
                    document.getElementById('message-field').classList.add('d-none');
                }
            });
        });
    </script>
@endpush
