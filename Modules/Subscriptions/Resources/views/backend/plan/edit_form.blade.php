@extends('backend.layouts.app')
@section('content')

<h4>{{ __('plan.lbl_edit_plan') }}</h4>

<div class="card">
    <div class="card-body">
        {{ html()->form('PUT' ,route('backend.plans.update', $data->id))
                                    ->attribute('data-toggle', 'validator')
                                    ->open()
                                }}

        <div class="row">
            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                {{ html()->text('name')
                            ->attribute('value', $data->name)  ->placeholder(__('placeholder.lbl_plan_name'))
                            ->class('form-control')
                        }}
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_level') . '<span class="text-danger">*</span>', 'level')->class('form-label') }}
                {{
                html()->select('level',
                    isset($plan) && $plan > 0
                        ? collect(range(1, $plan + 1))->mapWithKeys(fn($i) => [$i => 'Level ' . $i])->prepend(__('Select Level'), '')->toArray()
                        : ['1' => 'Level 1'],
                    old('level', $data->level ?? '')
                )->class('form-control select2')->id('level')->attribute('placeholder', __('placeholder.lbl_plan_level'))
            }}
                @error('level')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_duration') . '<span class="text-danger">*</span>', 'duration')->class('form-label') }}
                {{
                            html()->select('duration', [
                                    '' => 'Select Duration',
                                    'week' => 'Week',
                                    'month' => 'Month',
                                    'year' => 'Year'
                                ], $data->duration)
                                ->class('form-control select2')
                                ->id('duration')
                                ->attribute('placeholder', __('placeholder.lbl_plan_duration_type'))
                        }}
                @error('duration')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_duration_value') . '<span class="text-danger">*</span>', 'duration_value')->class('form-label') }}
                {{
                        html()->input('number', 'duration_value', $data->duration_value)
                            ->class('form-control')
                            ->id('duration_value')
                            ->attribute('placeholder', __('placeholder.lbl_plan_duration_value'))
                            ->attribute('min', '1')
                    }}
                @error('duration_value')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_amount') . '<span class="text-danger">*</span>', 'price')->class('form-label') }}
                {{
                    html()->input('number', 'price', $data->price)
                        ->class('form-control')
                        ->id('price')
                        ->attribute('step', '0.01')
                        ->attribute('placeholder', __('placeholder.lbl_plan_price'))
                        ->attribute('min', '0')
                }}
                @error('price')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_description') . ' <span class="text-danger"></span>', 'description')->class('form-control-label') }}
                {{ html()->textarea('description', $data->description)
                            ->placeholder(__('placeholder.lbl_plan_limit_description'))
                            ->class('form-control')
                        }}
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6 mb-3">
                {{ html()->label(__('plan.lbl_status'), 'status')->class('form-label') }}
                <div class="form-check form-switch">
                    {{ html()->hidden('status', 0) }}
                    {{
                            html()->checkbox('status',$data->status )
                                ->class('form-check-input')
                                ->id('status')
                        }}
                </div>
                @error('status')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="row"> 
                @foreach($planLimits as $planLimit)
                    <div class="col-sm-12 mb-3 d-flex gap-3">
                        <label for="{{ $planLimit->limitation_slug }}" class="form-label">{{ $planLimit->limitation_slug }}</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="limits[{{ $planLimit->id }}][planlimitation_id]" value="{{ $planLimit->planlimitation_id }}">
                            <input type="hidden" name="limits[{{ $planLimit->id }}][limitation_slug]" value="{{ $planLimit->limitation_slug }}">
                            <input type="hidden" name="limits[{{ $planLimit->id }}][value]" value="0">
                            <input type="checkbox" name="limits[{{ $planLimit->id }}][value]" id="{{ $planLimit->limitation_slug }}" class="form-check-input" value="1" {{ old("limits.{$planLimit->id}.value", $planLimit->limitation_value) ? 'checked' : '' }} onchange="toggleQualitySection()">
                        </div>
                        @error("limits.{$planLimit->id}.value")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
    
                    @if($planLimit->limitation_slug == 'device-limit')
                        <div class="col-sm-6 mb-3" id="deviceLimitInput">
                            {{ html()->label(__('plan.lbl_device_limit'), 'device_limit_value')->class('form-control-label') }}
                            {{
                                html()->input('number', 'device_limit_value', old('device_limit_value', $planLimit->limit))
                                    ->class('form-control')
                                    ->id('device_limit_value')
                                    ->attribute('placeholder', __('placeholder.lbl_device_limit'))
                                    ->attribute('min', '1')
                            }}
                            @error('device_limit_value')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif   
    
                    @if($planLimit->limitation_slug == 'download-status')
                    <div class="col-sm-6 mb-3" id="DownloadStatus">
                        @php
                            $downloadOptions = json_decode($planLimit->limit, true) ?? [];
                        @endphp
                        @foreach($downloadoptions as $option)
                            <div class="d-flex gap-5">
                                <label for="{{ $option->value }}" class="form-label">{{ $option->name }}</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="download_options[{{ $option->value }}]" value="0">
                                    <input type="checkbox" name="download_options[{{ $option->value }}]" id="{{ $option->value }}" class="form-check-input" value="1" {{ (isset($downloadOptions[$option->value]) && $downloadOptions[$option->value] == "1") ? 'checked' : '' }}>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif  
                @endforeach
            </div>
        </div>

        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
        {{ html()->form()->close() }}
    </div>
</div>
@endsection
@push('after-scripts')
      <script>
          function toggleQualitySection() {

             var enableQualityCheckbox = document.getElementById('device-limit');
             var enableQualitySection = document.getElementById('deviceLimitInput');
             
             if (enableQualityCheckbox.checked) {
             
              enableQualitySection.classList.remove('d-none');
              
             } else {
             
               enableQualitySection.classList.add('d-none');
             }
             }
             document.addEventListener('DOMContentLoaded', function () {
             toggleQualitySection();
        });

        function toggleDownloadSection() {
           
            
            var enableDownloadCheckbox = document.getElementById('download-status');
            var enableDownloadSection = document.getElementById('DownloadStatus');
            
            if (enableDownloadCheckbox.checked) {
                enableDownloadSection.classList.remove('d-none');
            } else {
                enableDownloadSection.classList.add('d-none');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var enableDownloadCheckbox = document.getElementById('download-status');

              toggleDownloadSection();
        
            enableDownloadCheckbox.addEventListener('change', toggleDownloadSection);
        });

   </script>
@endpush
