<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="dark" dir="{{ language_direction() }}" class="theme-fs-sm" data-bs-theme-color={{ getCustomizationSetting('theme_color') }}>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset(setting('logo')) }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset(setting('favicon')) }}">
    <meta name="keyword" content="{{ setting('meta_keyword') }}">
    <meta name="description" content="{{ setting('meta_description') }}">
    <meta name="setting_options" content="{{ setting('customization_json') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ env('APP_URL') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
    <!-- Shortcut Icon -->
    <link rel="shortcut icon" href="{{ asset(setting('favicon')) }}">
    <link rel="icon" type="image/ico" href="{{ asset(setting('favicon')) }}" />
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/css/intlTelInput.css"> -->



    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_name" content="{{ app_name() }}">

    <meta name="data_table_limit" content="{{ setting('data_table_limit') }}">


    <meta name="auth_user_roles" content="{{ auth()->user()->roles->pluck('name') }}">

    <meta name="baseUrl" content="{{ env('APP_URL') }}">


    <title>@yield('title') | {{ app_name() }}</title>

    <link rel="stylesheet" href="{{ mix('css/icon.min.css') }}">

    @stack('before-styles')
    <link rel="stylesheet" href="{{ mix('css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ mix('css/backend.css') }}">
    <link rel="stylesheet" href="{{ mix('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('custom-css/dashboard.css') }}">

    @if (language_direction() == 'rtl')
        <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/customizer.css') }}">


    <style>
        :root {
            <?php
            $rootColors = setting('root_colors'); // Assuming the setting() function retrieves the JSON string

            // Check if the JSON string is not empty and can be decoded
            if (!empty($rootColors) && is_string($rootColors)) {
                $colors = json_decode($rootColors, true);

                // Check if decoding was successful and the colors array is not empty
                if (json_last_error() === JSON_ERROR_NONE && is_array($colors) && count($colors) > 0) {
                    foreach ($colors as $key => $value) {
                        echo $key . ': ' . $value . '; ';
                    }
                } else {
                    echo 'Invalid JSON or empty colors array.';
                }
            }
            ?>
        }
    </style>

    <!-- Scripts -->
   <!-- Scripts -->
   @php
   $currentLang = App::currentLocale();
   $langFolderPath = base_path("lang/$currentLang");
   $filePaths = \File::files($langFolderPath);
 @endphp

@foreach ($filePaths as $filePath)
 @php
   $fileName = pathinfo($filePath, PATHINFO_FILENAME);

   $arr = require($filePath);
 @endphp
 <script>
   window.localMessagesUpdate = {
     ...window.localMessagesUpdate,
     "{{ $fileName }}": @json($arr)
   }
 </script>
@endforeach

     @php
    $role = !empty(auth()->user()) ? auth()->user()->getRoleNames() : null;
    $permissions = auth()->user()->getAllPermissions()->pluck('name')->toArray()
    @endphp
    <script>
        window.auth_role = @json($role);
        window.auth_permissions = @json($permissions)
    </script>
    <!-- Google Font -->

    <link rel="stylesheet" href="{{ asset('iconly/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('phosphor-icons/style.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
   @stack('after-styles')

    <x-google-analytics />

    <style>
        {!! setting('custom_css_block') !!}
    </style>
</head>

<body
    class="{{ !empty(getCustomizationSetting('card_style')) ? getCustomizationSetting('card_style') : '' }}">
    <!-- Loader Start -->
    <div id="loading">
        <x-partials._body_loader />
    </div>
    <!-- Loader End -->
    <!-- Sidebar -->
    @if (!isset($noLayout) || !$noLayout)

        @include('backend.includes.sidebar')
    @endif

    <!-- /Sidebar -->

    @yield('navbars')

    <div class="main-content wrapper">
        @if (!isset($noLayout) || !$noLayout)

        <div class="position-relative pr-hide
{{ !isset($isBanner) ? 'iq-banner' : '' }} default">
            <!-- Header -->
            @include('backend.includes.header')
            <!-- /Header -->
            @if (!isset($isBanner))
                <!-- Header Banner Start-->

                    @include('components.partials.sub-header')

                <!-- Header Banner End-->
            @endif
        </div>
        @endif

        <div class="conatiner-fluid content-inner pb-0" id="page_layout">
            <!-- Main content block -->
            @yield('content')
            <!-- / Main content block -->
            @if (isset($export_import) && $export_import)
                <div data-render="import-export">
                    <export-modal export-url="{{ $export_url }}"
                        :module-column-prop="{{ json_encode($export_columns) }}"
                        module-name="{{ $module_name }}"></export-modal>
                    <import-modal></import-modal>
                </div>
            @endif
        </div>

        <!-- Footer block -->
        @if (!isset($noLayout) || !$noLayout)

        @include('backend.includes.footer')
        @include('backend.layouts._dynamic_script')

        @endif
        <!-- / Footer block -->

    </div>

    <!-- Modal -->
    <div class="modal fade" data-iq-modal="renderer" id="renderModal" tabindex="-1" aria-labelledby="renderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" data-iq-modal="content">
                <div class="modal-header">
                    <h5 class="modal-title" data-iq-modal="title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" data-iq-modal="body">
                    <p>Modal body text goes here.</p>
                </div>
            </div>
        </div>
    </div>

    <div data-render="global-booking">
        <global-appointment-offcanvas booking-type="GLOBAL_BOOKING" ></global-appointment-offcanvas>
      </div>

    @php
    $multiVendorEnabled = multiVendor();
    $bodyChartEnabled=bodychart();
    $telemedSettingEnabled=telemedSetting();
    $encounter =encounter();

@endphp


    @stack('before-scripts')
    <script src="{{ mix('js/backend.js') }}"></script>
    <script src="{{ asset('js/iqonic-script/utility.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>
    <script>
  window.bodyChartEnabled = @json($bodyChartEnabled);
  window.telemedSettingEnabled = @json($telemedSettingEnabled);
  window.encounter=@json($encounter);
    </script>

    <script>
      window.multiVendorEnabled = @json($multiVendorEnabled);
    </script>
    <script src="{{ mix('js/import-export.min.js') }}"></script>
    @if (isset($assets) && (in_array('textarea', $assets) || in_array('editor', $assets)))
        <script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('vendor/tinymce/js/tinymce/jquery.tinymce.min.js') }}"></script>
    @endif

    @stack('after-scripts')
    <!-- / Scripts -->
    <script>
        $('.notification_list').on('click', function() {
            notificationList();
        });

        $(document).on('click', '.notification_data', function(event) {
            event.stopPropagation();
        })

        function notificationList(type = '') {
            var url = "{{ route('notification.list') }}";
            $.ajax({
                type: 'get',
                url: url,
                data: {
                    'type': type
                },
                success: function(res) {
                    $('.notification_data').html(res.data);
                    getNotificationCounts();
                    if (res.type == "markas_read") {
                        notificationList();
                    }
                    $('.notify_count').removeClass('notification_tag').text('');
                }
            });
        }

        function setNotification(count) {
            if (Number(count) >= 100) {
                $('.notify_count').text('99+');
            }
        }

        function getNotificationCounts() {
            var url = "{{ route('notification.counts') }}";

            $.ajax({
                type: 'get',
                url: url,
                success: function(res) {
                    if (res.counts > 0) {
                        $('.notify_count').addClass('notification_tag').text(res.counts);
                        setNotification(res.counts);
                        $('.notification_list span.dots').addClass('d-none')
                        $('.notify_count').removeClass('d-none')
                    } else {
                        $('.notify_count').addClass('d-none')
                        $('.notification_list span.dots').removeClass('d-none')
                    }

                    if (res.counts <= 0 && res.unread_total_count > 0) {
                        $('.notification_list span.dots').removeClass('d-none')
                    } else {
                        $('.notification_list span.dots').addClass('d-none')
                    }
                }
            });
        }

        getNotificationCounts();
    </script>

    <script>
        {!! setting('custom_js_block') !!}
        @if (\Session::get('error'))
            Swal.fire({
                title: 'Error',
                text: '{{ session()->get('error') }}',
                icon: "error",
                showClass: {
                    popup: 'animate__animated animate__zoomIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut'
                }
            })
        @endif

          // dark and light mode code
    const theme_mode = sessionStorage.getItem('theme_mode')
    const element = document.querySelector('html');
    if(theme_mode === null ){
        element.setAttribute('data-bs-theme', 'dark')
    } else {
        document.documentElement.setAttribute('data-bs-theme', theme_mode)
    }
    </script>
<script src="{{ asset('vendor/flatpickr/flatpicker.min.js') }}"></script>
<script src={{mix('js/media/media.min.js')}}></script>
</body>

</html>
