@extends('setting::backend.setting.index')

@section('settings-content')

<div class="container">
    <div>
        <h4><i class="fa-solid fa-coins"></i> {{ __('setting_sidebar.lbl_payment') }} </h4>
    </div>
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif



    <form method="POST" action="{{ route('backend.setting.store') }}" id="payment-settings-form">
        @csrf

        {{-- Razorpay --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="payment_method_razorpay">{{ __('Razorpay') }}</label>
                <input type="hidden" value="0" name="razor_payment_method">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#razorpay-fields" value="1" name="razor_payment_method" id="payment_method_razorpay" type="checkbox" {{ old('razor_payment_method', $settings['razor_payment_method'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="razorpay-fields" class="{{ old('razor_payment_method', $settings['razor_payment_method'] ?? 0) == 1 ? '' : 'd-none' }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="razorpay_secretkey">{{ __('Secret Key') }}</label>
                        <input type="text" class="form-control @error('razorpay_secretkey') is-invalid @enderror" name="razorpay_secretkey" id="razorpay_secretkey" value="{{ old('razorpay_secretkey', $settings['razorpay_secretkey'] ?? '') }}">
                        @error('razorpay_secretkey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="razorpay_publickey">{{ __('Public Key') }}</label>
                        <input type="text" class="form-control @error('razorpay_publickey') is-invalid @enderror" name="razorpay_publickey" id="razorpay_publickey" value="{{ old('razorpay_publickey', $settings['razorpay_publickey'] ?? '') }}">
                        @error('razorpay_publickey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Stripe --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="payment_method_stripe">{{ __('Stripe') }}</label>
                <input type="hidden" value="0" name="str_payment_method">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#stripe-fields" value="1" name="str_payment_method" id="payment_method_stripe" type="checkbox" {{ old('str_payment_method', $settings['str_payment_method'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="stripe-fields" class="{{ old('str_payment_method', $settings['str_payment_method'] ?? 0) == 1 ? '' : 'd-none' }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="stripe_secretkey">{{ __('Secret Key') }}</label>
                        <input type="text" class="form-control @error('stripe_secretkey') is-invalid @enderror" name="stripe_secretkey" id="stripe_secretkey" value="{{ old('stripe_secretkey', $settings['stripe_secretkey'] ?? '') }}">
                        @error('stripe_secretkey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="stripe_publickey">{{ __('Public Key') }}</label>
                        <input type="text" class="form-control @error('stripe_publickey') is-invalid @enderror" name="stripe_publickey" id="stripe_publickey" value="{{ old('stripe_publickey', $settings['stripe_publickey'] ?? '') }}">
                        @error('stripe_publickey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Paystack --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="payment_method_paystack">{{ __('Paystack') }}</label>
                <input type="hidden" value="0" name="paystack_payment_method">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#paystack-fields" value="1" name="paystack_payment_method" id="payment_method_paystack" type="checkbox" {{ old('paystack_payment_method', $settings['paystack_payment_method'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="paystack-fields" class="{{ old('paystack_payment_method', $settings['paystack_payment_method'] ?? 0) == 1 ? '' : 'd-none' }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="paystack_secretkey">{{ __('Secret Key') }}</label>
                        <input type="text" class="form-control @error('paystack_secretkey') is-invalid @enderror" name="paystack_secretkey" id="paystack_secretkey" value="{{ old('paystack_secretkey', $settings['paystack_secretkey'] ?? '') }}">
                        @error('paystack_secretkey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="paystack_publickey">{{ __('Public Key') }}</label>
                        <input type="text" class="form-control @error('paystack_publickey') is-invalid @enderror" name="paystack_publickey" id="paystack_publickey" value="{{ old('paystack_publickey', $settings['paystack_publickey'] ?? '') }}">
                        @error('paystack_publickey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- PayPal --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="payment_method_paypal">{{ __('PayPal') }}</label>
                <input type="hidden" value="0" name="paypal_payment_method">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#paypal-fields" value="1" name="paypal_payment_method" id="payment_method_paypal" type="checkbox" {{ old('paypal_payment_method', $settings['paypal_payment_method'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="paypal-fields" class="{{ old('paypal_payment_method', $settings['paypal_payment_method'] ?? 0) == 1 ? '' : 'd-none' }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="paypal_secretkey">{{ __('Secret Key') }}</label>
                        <input type="text" class="form-control @error('paypal_secretkey') is-invalid @enderror" name="paypal_secretkey" id="paypal_secretkey" value="{{ old('paypal_secretkey', $settings['paypal_secretkey'] ?? '') }}">
                        @error('paypal_secretkey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="paypal_clientid">{{ __('Client ID') }}</label>
                        <input type="text" class="form-control @error('paypal_clientid') is-invalid @enderror" name="paypal_clientid" id="paypal_clientid" value="{{ old('paypal_clientid', $settings['paypal_clientid'] ?? '') }}">
                        @error('paypal_clientid')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Flutterwave --}}
        <div class="form-group border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label m-0" for="payment_method_flutterwave">{{ __('Flutterwave') }}</label>
                <input type="hidden" value="0" name="flutterwave_payment_method">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input toggle-input" data-toggle-target="#flutterwave-fields" value="1" name="flutterwave_payment_method" id="payment_method_flutterwave" type="checkbox" {{ old('flutterwave_payment_method', $settings['flutterwave_payment_method'] ?? 0) == 1 ? 'checked' : '' }} />
                </div>
            </div>
        </div>
        <div id="flutterwave-fields" class="{{ old('flutterwave_payment_method', $settings['flutterwave_payment_method'] ?? 0) == 1 ? '' : 'd-none' }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_secretkey">{{ __('Secret Key') }}</label>
                        <input type="text" class="form-control @error('flutterwave_secretkey') is-invalid @enderror" name="flutterwave_secretkey" id="flutterwave_secretkey" value="{{ old('flutterwave_secretkey', $settings['flutterwave_secretkey'] ?? '') }}">
                        @error('flutterwave_secretkey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="flutterwave_publickey">{{ __('Public Key') }}</label>
                        <input type="text" class="form-control @error('flutterwave_publickey') is-invalid @enderror" name="flutterwave_publickey" id="flutterwave_publickey" value="{{ old('flutterwave_publickey', $settings['flutterwave_publickey'] ?? '') }}">
                        @error('flutterwave_publickey')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </form>
</div>
@endsection
@push('after-scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-input').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                var target = document.querySelector(toggle.getAttribute('data-toggle-target'));
                if (toggle.checked) {
                    target.classList.remove('d-none');
                    target.querySelectorAll('input').forEach(function(input) {
                        input.disabled = false;
                    });
                } else {
                    target.classList.add('d-none');
                    target.querySelectorAll('input').forEach(function(input) {
                        input.disabled = true;
                    });
                }
            });
        });

        // Disable fields if their toggle is not checked on page load
        document.querySelectorAll('.toggle-input').forEach(function(toggle) {
            var target = document.querySelector(toggle.getAttribute('data-toggle-target'));
            if (!toggle.checked) {
                target.querySelectorAll('input').forEach(function(input) {
                    input.disabled = true;
                });
            }
        });

        // Enable fields on form submission if they are visible
        document.getElementById('payment-settings-form').addEventListener('submit', function() {
            document.querySelectorAll('.toggle-input').forEach(function(toggle) {
                var target = document.querySelector(toggle.getAttribute('data-toggle-target'));
                if (toggle.checked) {
                    target.querySelectorAll('input').forEach(function(input) {
                        input.disabled = false;
                    });
                }
            });
        });
    });
</script>
@endpush

