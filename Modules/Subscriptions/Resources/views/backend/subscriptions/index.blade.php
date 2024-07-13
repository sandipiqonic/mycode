@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
                <div class="d-flex flex-wrap gap-3">
                    {{-- @if (auth()->user()->can('edit_' . $module_name) ||
    auth()->user()->can('delete_' . $module_name)) --}}
                    <!-- <x-backend.quick-action url="{{ route('backend.' . $module_name . '.bulk_action') }}">
                        {{-- <div class="">
                            <select name="action_type" class="form-control select2 col-12" id="quick-action-type"
                                style="width:100%">
                                <option value="">{{ __('messages.no_action') }}</option>

                                <option value="change-status">{{ __('messages.status') }}</option>

                                <option value="delete">{{ __('messages.delete') }}</option>
                                <option value="restore">{{ __('messages.restore') }}</option>
                                <option value="permanently-delete">{{ __('messages.permanent_dlt') }}</option>
                            </select>
                        </div> --}}
                        <div class="select-status d-none quick-action-field" id="change-status-action">
                            <select name="status" class="form-control select2" id="status" style="width:100%">
                                <option value="1" selected>{{ __('messages.active') }}</option>
                                <option value="0">{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                    </x-backend.quick-action> -->
                    {{-- @endif --}}


                    <button type="button" class="btn btn-secondary" data-modal="export">
                        <i class="ph ph-download-simple me-1"></i> {{ __('messages.export') }}
                    </button>
                </div>
                <!-- <div class="pt-5">
                    <label for="form-label"> {{ __('livetv.checkall') }} </label>
                    <input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table"
                        data-type="genres" onclick="selectAllTable(this)">
                </div> -->
                <x-slot name="toolbar">

                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping"><i
                                class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control dt-search" placeholder="Search..." aria-label="Search"
                            aria-describedby="addon-wrapping">

                    </div>


                </x-slot>
            </x-backend.section-header>
            <table id="datatable" class="table table-responsive"></table>
        </div>
    </div>

    @if (session('success'))
        <div class="snackbar" id="snackbar">
            <div class="d-flex justify-content-around align-items-center">
                <p class="mb-0">{{ session('success') }}</p>
                <a href="#" class="dismiss-link text-decoration-none text-success"
                    onclick="dismissSnackbar(event)">Dismiss</a>
            </div>
        </div>
    @endif
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
    <script src="{{ asset('js/form-modal/index.js') }}" defer></script>
    <script src="{{ asset('js/form/index.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" defer>
        const columns = [ 
            {
                data: 'user_id',
                name: 'user_id',
                title: "{{ __('User Name') }}"
            },
            {
                data: 'start_date',
                name: 'start_date',
                title: "{{ __('Start Date') }}"
            },
            {
                data: 'end_date',
                name: 'end_date',
                title: "{{ __('End Date') }}"
            },
            {
                data: 'amount',
                name: 'amount',
                title: "{{ __('Amount') }}"
            },
            {
                data: 'name',
                name: 'name',
                title: "{{ __('Plan Name') }}"
            },
            {
                data: 'status',
                name: 'status',
                title: "{{ __('subscriptions.lbl_status') }}",
                orderable: false,
                searchable: true,
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                title: "{{ __('subscriptions.lbl_update_at') }}",
                orderable: true,
                visible: false,
            }
        ];

        const actionColumn = [];
        // const actionColumn = [{
        //     data: 'action',
        //     name: 'action',
        //     orderable: false,
        //     searchable: false,
        //     title: "{{ __('subscriptions.lbl_action') }}",
        //     width: '5%'
        // }];

        const finalColumns = [...columns, ...actionColumn];

        document.addEventListener('DOMContentLoaded', (event) => {
            initDatatable({
                url: '{{ route("backend.$module_name.index_data") }}',
                finalColumns,
                orderColumn: [
                    [4, "desc"]
                ],
                search: {
                    selector: '.dt-search',
                    smart: true
                }
            });
        });

        function resetQuickAction() {
            const actionValue = $('#quick-action-type').val();
            $('#quick-action-apply').attr('disabled', actionValue == '');
            $('.quick-action-field').addClass('d-none');
            if (actionValue == 'change-status') {
                $('#change-status-action').removeClass('d-none');
            }
        }

        $('#quick-action-type').change(resetQuickAction);
    </script>
@endpush
