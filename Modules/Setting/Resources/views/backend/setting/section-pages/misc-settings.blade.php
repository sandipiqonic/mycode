@extends('setting::backend.setting.index')

@section('settings-content')
<form method="POST" action="{{ route('backend.setting.store') }}">
    @csrf

    <div class="card">
        <div class="card-header">
            <h4><i class="fa-solid fa-screwdriver-wrench"></i> {{ __('setting_sidebar.lbl_misc_setting') }} </h4>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    {{ html()->label(__('setting_analytics_page.lbl_name'))->class('form-label') }}
                    {{ html()->text('google_analytics')
                        ->class('form-control')
                        ->placeholder(__('setting_analytics_page.lbl_name'))
                        ->value(old('google_analytics', $settings['google_analytics'] ?? '')) }}
                    @error('google_analytics')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-md-4">
                    {{ html()->label(__('setting_language_page.lbl_language'))->class('form-label') }}
                    {{ html()->select('default_language')
                        ->options(array_column($languages, 'name', 'id'))
                        ->class('form-control')
                        ->value(old('default_language', $settings['default_language'] ?? '')) }}
                    @error('default_language')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

               <div class="col-md-4">
                    {{ html()->label(__('setting_language_page.lbl_timezone')) }}
                    {{ html()->select('default_time_zone')
                        ->options(array_column($timezones, 'text', 'id'))
                        ->class('form-control')
                        ->value(old('default_time_zone', $settings['default_time_zone'] ?? '')) }}
                    @error('default_time_zone')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-md-4">
                    {{ html()->label(__('setting_language_page.lbl_data_table_limit'))->class('form-label') }}
                    {{ html()->select('data_table_limit')
                        ->options([
                            5 => 5,
                            10 => 10,
                            15 => 15,
                            20 => 20,
                            25 => 25,
                            50 => 50,
                            100 => 100,
                            -1 => 'All',
                        ])
                        ->class('form-control')
                        ->value(old('data_table_limit', $settings['data_table_limit'] ?? '')) }}
                    @error('data_table_limit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                  <!-- AWS Configuration Fields -->
                  <div class="col-md-4">
                    {{ html()->label('AWS_ACCESS_KEY_ID')->class('form-label') }}
                    {{ html()->text('AWS_ACCESS_KEY_ID')
                        ->class('form-control')
                        ->value(old('AWS_ACCESS_KEY_ID', $settings['AWS_ACCESS_KEY_ID'] ?? '')) }}
                    @error('AWS_ACCESS_KEY_ID')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    {{ html()->label('AWS_SECRET_ACCESS_KEY')->class('form-label') }}
                    {{ html()->text('AWS_SECRET_ACCESS_KEY')
                        ->class('form-control')
                        ->value(old('AWS_SECRET_ACCESS_KEY', $settings['AWS_SECRET_ACCESS_KEY'] ?? '')) }}
                    @error('AWS_SECRET_ACCESS_KEY')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    {{ html()->label('AWS_DEFAULT_REGION')->class('form-label') }}
                    {{ html()->text('AWS_DEFAULT_REGION')
                        ->class('form-control')
                        ->value(old('AWS_DEFAULT_REGION', $settings['AWS_DEFAULT_REGION'] ?? '')) }}
                    @error('AWS_DEFAULT_REGION')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    {{ html()->label('AWS_BUCKET')->class('form-label') }}
                    {{ html()->text('AWS_BUCKET')
                        ->class('form-control')
                        ->value(old('AWS_BUCKET', $settings['AWS_BUCKET'] ?? '')) }}
                    @error('AWS_BUCKET')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> 

                <div class="col-md-4">
                    {{ html()->label('AWS_USE_PATH_STYLE_ENDPOINT')->class('form-label') }}
                    {{ html()->select('AWS_USE_PATH_STYLE_ENDPOINT')
                        ->options([
                            'true' => 'true',
                            'false' => 'false',
                        ])
                        ->class('form-control')
                        ->value(old('AWS_USE_PATH_STYLE_ENDPOINT', $settings['AWS_USE_PATH_STYLE_ENDPOINT'] ?? '')) }}
                    @error('AWS_USE_PATH_STYLE_ENDPOINT')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    {{ html()->label('TMDB_API_KEY')->class('form-label') }}
                    {{ html()->text('TMDB_API_KEY')
                        ->class('form-control')
                        ->value(old('TMDB_API_KEY', $settings['TMDB_API_KEY'] ?? '')) }}
                    @error('TMDB_API_KEY')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

        </div>
        <div class="card-footer">
            {{ html()->button(__('Submit'))
                ->type('submit')
                ->class('btn btn-primary') }}
        </div>
    </div>
</form>
@endsection
