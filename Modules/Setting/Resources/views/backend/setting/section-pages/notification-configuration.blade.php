@extends('setting::backend.setting.index')

@section('settings-content')
<form method="POST" action="{{ route('backend.setting.store') }}">
    @csrf

    <div class="card">
        <div class="card-header">
            <h4><i class="fa-solid fa-bell"></i> Notification Configuration Settings</h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    {{ html()->label('Expiry Plan')->class('form-label') }}
                    {{ html()->number('expiry_plan')
                        ->class('form-control')
                        ->placeholder('Expiry Plan Days')
                        ->value(old('expiry_plan', $settings['expiry_plan'] ?? '')) }}
                    @error('expiry_plan')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    {{ html()->label('Upcoming')->class('form-label') }}
                    {{ html()->number('upcoming')
                        ->class('form-control')
                        ->placeholder('Upcoming Days')
                        ->value(old('upcoming', $settings['upcoming'] ?? '')) }}
                    @error('upcoming')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    {{ html()->label('Continue Watch ')->class('form-label') }}
                    {{ html()->number('continue_watch')
                        ->class('form-control')
                        ->placeholder('Continue Watch Days')
                        ->value(old('continue_watch', $settings['continue_watch'] ?? '')) }}
                    @error('continue_watch')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
            </div>
        </div>
        <div class="card-footer">
            {{ html()->button('Submit')
                ->type('submit')
                ->class('btn btn-primary') }}
        </div>
    </div>
</form>
@endsection
