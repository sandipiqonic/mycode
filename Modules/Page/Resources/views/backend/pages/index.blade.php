@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
                <div class="d-flex flex-wrap gap-3">

                    {{-- @if(auth()->user()->can('edit_'.$module_name) || auth()->user()->can('delete_'.$module_name)) --}}
                    <x-backend.quick-action url="{{ route('backend.' . $module_name . '.bulk_action') }}">
                        <div class="">
                            <select name="action_type" class="form-control select2 col-12" id="quick-action-type" style="width:100%">
                                <option value="">{{ __('messages.no_action') }}</option>
                                {{-- @can('edit_'.$module_name) --}}
                                <option value="change-status">{{ __('messages.status') }}</option>
                                {{-- @endcan --}}
                                {{-- @can('delete_'.$module_name) --}}
                                <option value="delete">{{ __('messages.delete') }}</option>
                                <option value="restore">{{ __('messages.restore') }}</option>
                                <option value="permanently-delete">{{ __('messages.permanent_dlt') }}</option>
                                {{-- @endcan --}}
                            </select>
                        </div>
                        <div class="select-status d-none quick-action-field" id="change-status-action">
                            <select name="status" class="form-control select2" id="status" style="width:100%">
                                <option value="1" selected>{{ __('messages.active') }}</option>
                                <option value="0">{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                    </x-backend.quick-action>
                    {{-- @endif --}}
                      <button type="button" class="btn btn-secondary" data-modal="export">
                        <i class="fa-solid fa-download"></i> {{ __('messages.export') }}
                      </button>
                </div>
                
                {{-- CHECK ALL  --}}
                <div class="pt-5">
                    <label for="form-label"> {{ __('livetv.checkall') }} </label>
                    <input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="genres" onclick="selectAllTable(this)">
                </div>
                

                <x-slot name="toolbar">

                    <div>
                        <div class="datatable-filter">
                            <select name="column_status" id="column_status" class="select2 form-control"
                                data-filter="select" style="width: 100%">
                                <option value="">{{__('messages.all')}}</option>
                                <option value="0" {{ $filter['status'] == '0' ? 'selected' : '' }}>
                                    {{ __('messages.inactive') }}</option>
                                <option value="1" {{ $filter['status'] == '1' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping"><i
                                class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control dt-search" placeholder="Search..." aria-label="Search"
                            aria-describedby="addon-wrapping">
                    </div>

                     {{-- ADD NEW PAGE DATA BUTTON --}}

                      {{-- @hasPermission('add_'.$module_name) --}}
                     <a href="{{ route('backend.' . $module_name . '.create') }}" class="btn btn-primary" id="add-post-button"> 
                        <i class="fas fa-plus-circle me-2"></i>{{ __('messages.new') }}
                    </a>
                  {{-- @endhasPermission --}}
                    {{-- <button class="btn btn-secondary d-flex align-items-center gap-1 btn-group" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i class="ph ph-funnel"></i>{{__('messages.advance_filter')}}</button> --}}

                </x-slot>

            </x-backend.section-header>

            {{-- ALL THE DATA WILL BE SHOWN IN THIS TABLE USING JAVASCRIPT TO READ DATA --}}
            <table id="datatable" class="table table-responsive">
            </table>


        </div>
    </div>

    
    {{-- card END --}}
{{-- ADVANCED FILTER SECTION --}}
    <x-backend.advance-filter>
        <x-slot name="title">
            <h4>{{ __('service.lbl_advanced_filter') }}</h4>
        </x-slot>
        <form action="javascript:void(0)" class="datatable-filter">
            <div class="form-group">
                <label for="form-label"> {{ __('booking.lbl_customer_name') }} </label>
                <select name="filter_service_id" id="user_name" name="user_name" data-filter="select"
                    class="select2 form-control"
                    data-ajax--url="{{ route('backend.get_search_data', ['type' => 'posts']) }}"
                    data-ajax--cache="true">
                </select>
            </div>
        </form>
        <button type="reset" class="btn btn-danger" id="reset-filter">Reset</button>
    </x-backend.advance-filter>
    
{{-- COPYURL SNACKBAR --}}
    <div id="copy-url-snackbar" class="snackbar">
        <p class="mb-0">{{ __('Copy Page URL !') }}</p>
        <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
    </div>

    @if(session('success'))
        <div class="snackbar" id="snackbar">
            <div class="d-flex justify-content-around align-items-center">
                <p class="mb-0">{{ session('success') }}</p>
                <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
            </div>
        </div>
    @endif

@endsection

{{-- DATATABLE SECTION --}}
@push ('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

    @push('after-scripts')
    <!-- DataTables Core and Extensions -->
     <script src="{{ asset('js/form-modal/index.js') }}" defer></script>
     <script src="{{ asset('js/form/index.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" defer>
        const columns = [
            {
                name: 'check',
                data: 'check',
                title: '<input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" onclick="selectAllTable(this)">',
                width: '0%',
                exportable: false,
                orderable: false,
                searchable: false,
            },
            {
                data: 'name',
                name: 'name',
                title: "{{ __('page.lbl_name') }}"
            },
            {
                data: 'status',
                name: 'status',
                title: "{{ __('page.lbl_status') }}",
                width: '5%',
            },
            {
              data: 'updated_at',
              name: 'updated_at',
              title: "{{ __('page.lbl_update_at') }}",
              orderable: true,
             visible: false,
           },

        ]


        const actionColumn = [{
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            title: "{{ __('page.lbl_action') }}",
            width: '10%'
        }]

        let finalColumns = [
            ...columns,
            ...actionColumn
        ]

        // SHOW ALL PAGES DATA IN TABLE FORM 
        document.addEventListener('DOMContentLoaded', (event) => {
            initDatatable({
                url: '{{ route("backend.$module_name.index_data") }}',
                finalColumns,
                orderColumn: [[ 4, "desc" ]],
                advanceFilter: () => {
                    return {
                        name: $('#user_name').val()
                    }
                }
            });
        })

function copyURL(event) {
    event.preventDefault(); // Prevent the default behavior of the <a> tag
    const originalUrl = event.currentTarget.href; // Get the href attribute of the clicked <a> tag
    const modifiedUrl = originalUrl.replace(/%20/g, '-');

    // Create a temporary input element to copy the URL to the clipboard
    const tempInput = document.createElement('input');
    document.body.appendChild(tempInput);
    tempInput.value = modifiedUrl;
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);

    // Show the pop-up notification
    showSnackbar();
}

function showSnackbar() {
    const snackbar = document.getElementById('copy-url-snackbar');
    snackbar.classList.add('show');

    // Automatically hide the snackbar after 3 seconds
    setTimeout(function() {
        snackbar.classList.remove('show');
    }, 3000);
}

function dismissSnackbar(event) {
    event.preventDefault();
    const snackbar = document.getElementById('copy-url-snackbar');
    snackbar.classList.remove('show');
}

document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.copy-url');
    links.forEach(link => {
        link.addEventListener('click', copyURL);
    });
});


        $('#reset-filter').on('click', function(e) {
            $('#user_name').val('')
            window.renderedDataTable.ajax.reload(null, false)
        })

        function resetQuickAction() {
            const actionValue = $('#quick-action-type').val();
            if (actionValue != '') {
                $('#quick-action-apply').removeAttr('disabled');

                if (actionValue == 'change-status') {
                    
                    $('.quick-action-field').addClass('d-none');
                    $('#change-status-action').removeClass('d-none');
                } else {
                    $('.quick-action-field').addClass('d-none');
                }
            } else {
                $('#quick-action-apply').attr('disabled', true);
                $('.quick-action-field').addClass('d-none');
            }
        }

        $('#quick-action-type').change(function() {
            resetQuickAction()
        });

        $(document).on('update_quick_action', function() {
            // resetActionButtons()
        })
</script>

@endpush
