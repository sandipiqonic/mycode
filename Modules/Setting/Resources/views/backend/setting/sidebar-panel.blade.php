{{-- resources/views/partials/sidebar-panel.blade.php --}}
<div class="col-md-4 col-lg-3">
    <div id="setting-sidebar" class="setting-sidebar-inner">
        <div class="card">
            <div class="card-body">
                <div class="list-group list-group-flush" id="setting-list">
                    @hasPermission('setting_bussiness')
                        <div class="mb-3 active-menu" id="Settings.home">
                            <a href="{{ route('backend.settings.general') }}" class="btn btn-border"><i
                                    class="fas fa-cube"></i>{{ __('setting_sidebar.lbl_General') }}</a>
                        </div>
                    @endhaspermission
                    @hasPermission('setting_bussiness')
                        <div class="mb-3 active-menu" id="Settings.module">
                            <a href="{{ route('backend.settings.module') }}" class="btn btn-border"><i
                                    class="fas fa-cube"></i>{{ __('setting_sidebar.lbl_module_setting') }}</a>
                        </div>
                    @endhaspermission
                    @hasPermission('setting_custom_code')
                        <div class="mb-3 active-menu" id="Settings.module">
                            <a href="{{ route('backend.settings.custom-code') }}" class="btn btn-border"><i
                                    class="fas fa-cube"></i>{{ __('setting_sidebar.lbl_custom_code') }}</a>
                        </div>
                    @endhaspermission
                    @hasPermission('setting_misc')
                        <div class="mb-3 active-menu" id="Settings.misc">
                            <a href="{{ route('backend.settings.misc') }}" class="btn btn-border"><i
                                    class="fa-solid fa-screwdriver-wrench"></i>{{ __('setting_sidebar.lbl_misc_setting') }}</a>
                        </div>
                    @endhaspermission

                    @hasPermission('setting_invoice')
                        <div class="mb-3 active-menu" id="Settings.invoice-setting">
                            <a href="{{ route('backend.settings.invoice-setting') }}" class="btn btn-border"><i
                                    class="fa-solid fa-file-invoice"
                                    aria-hidden="true"></i>{{ __('setting_sidebar.lbl_inv_setting') }}</a>
                        </div>
                    @endhaspermission

                    @hasPermission('setting_app_setting')
                    <div class="mb-3 active-menu" id="Settings.other-settings">
                        <a href="{{ route('backend.settings.other-settings') }}" class="btn btn-border"><i class="fa-solid fa-sliders"></i>{{ __('setting_sidebar.lbl_app_configuration') }}</a>
                    </div>
                    @endhaspermission

                    @hasPermission('setting_customization')
                        <div class="mb-3 active-menu" id="Settings.customization">
                            <a href="{{ route('backend.settings.customization') }}" class="btn btn-border"><i
                                    class="fa-solid fa-swatchbook"></i>{{ __('setting_sidebar.lbl_customization') }}</a>
                        </div>
                    @endhaspermission

                    @hasPermission('setting_mail')
                        <div class="mb-3 active-menu" id="Settings.mail">
                            <a href="{{ route('backend.settings.mail') }}" class="btn btn-border"><i
                                    class="fas fa-envelope"></i>{{ __('setting_sidebar.lbl_mail') }}</a>
                        </div>
                    @endhaspermission

                    @hasPermission('setting_notification')
                        <div class="mb-3 active-menu" id="Settings.integration">
                            <a href="{{ route('backend.settings.notificationsetting') }}" class="btn btn-border"><i
                                    class="fa-solid fa-bullhorn"></i>{{ __('setting_sidebar.lbl_notification') }}</a>
                        </div>
                    @endhaspermission


                    @hasPermission('setting_intigrations')
                        <div class="mb-3 active-menu" id="Settings.notificationsetting">
                            <a href="{{ route('backend.settings.integration') }}" class="btn btn-border"><i
                                    class="fa-solid fa-sliders"></i>{{ __('setting_sidebar.lbl_integration') }}</a>
                        </div>
                    @endhaspermission

                    @hasPermission('setting_payment_method')
                        <div class="mb-3 active-menu" id="Settings.payment-method">
                            <a href="{{ route('backend.settings.payment-method') }}" class="btn btn-border"><i
                                    class="fa-solid fa-coins"></i>{{ __('setting_sidebar.lbl_payment') }}</a>
                        </div>
                    @endhaspermission

                    @hasPermission('setting_language')
                        <div class="mb-3 active-menu" id="Settings.language-settings">
                            <a href="{{ route('backend.settings.language-settings') }}" class="btn btn-border"><i
                                    class="fa fa-language"
                                    aria-hidden="true"></i>{{ __('setting_sidebar.lbl_language') }}</a>
                        </div>
                    @endhaspermission

                    {{-- @hasPermission('setting_notification_configuration') --}}
                        <div class="mb-3 active-menu" id="Settings.notification-configuration">
                            <a href="{{ route('backend.settings.notification-configuration') }}" class="btn btn-border">
                                <i class="fa-solid fa-bell"></i>{{ __('setting_sidebar.lbl_notification_configuration') }}</a>
                        </div>
                    {{-- @endhaspermission --}}




                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function toggle() {
            const formOffcanvas = document.getElementById('offcanvas');
            formOffcanvas.classList.add('show');
        }

        function hasPermission(permission) {
            return window.auth_permissions.includes(permission);
        }
    </script>
@endpush

<style scoped>
    .btn-border {
        text-align: left;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
</style>
