@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-backend.section-header>
            <div class="d-flex flex-wrap gap-3">

                <x-backend.quick-action url="{{ route('backend.' . $module_name . '.bulk_action') }}">
                    <div class="">
                        <select name="action_type" class="form-control select2 col-12" id="quick-action-type"
                            style="width:100%">
                            <option value="">{{ __('messages.no_action') }}</option>

                            <option value="change-status">{{ __('messages.status') }}</option>

                            <option value="delete">{{ __('messages.delete') }}</option>
                            <option value="restore">{{ __('messages.restore') }}</option>
                            <option value="permanently-delete">{{ __('messages.permanent_dlt') }}</option>
                        </select>
                    </div>
                    <div class="select-status d-none quick-action-field" id="change-status-action">
                        <select name="status" class="form-control select2" id="status" style="width:100%">
                            <option value="1" selected>{{ __('messages.active') }}</option>
                            <option value="0">{{ __('messages.inactive') }}</option>
                        </select>
                    </div>
                </x-backend.quick-action>


                <div>
                    <button type="button" class="btn btn-secondary" data-modal="export">
                        <i class="fa-solid fa-download"></i> {{ __('messages.export') }}
                    </button>
                    {{--          <button type="button" class="btn btn-secondary" data-modal="import">--}}
                    {{--            <i class="fa-solid fa-upload"></i> Import--}}
                    {{--          </button>--}}

                </div>
            </div>
            <div class="pt-5">
                            <label for="form-label"> {{ __('livetv.checkall') }} </label>
                            <input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="plan" onclick="selectAllTable(this)">
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

                @hasPermission('add_plans')
                <a href="{{ route('backend.' . $module_name . '.create') }}" class="btn btn-primary"
                    id="add-post-button"> <i class="fas fa-plus-circle me-2"></i>{{__('messages.new')}}</a>
                    @endhasPermission
                {{-- <button class="btn btn-secondary d-flex align-items-center gap-1 btn-group" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i
                        class="ph ph-funnel"></i>{{__('messages.advance_filter')}}</button> --}}
            </x-slot>
        </x-backend.section-header>
        <table id="datatable" class="table table-responsive">
        </table>
    </div>
</div>
@if(session('success'))
<div class="snackbar" id="snackbar">
    <div class="d-flex justify-content-around align-items-center">
        <p class="mb-0">{{ session('success') }}</p>
        <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
    </div>
</div>
@endif
<x-backend.advance-filter>
    <x-slot name="title">
        <h4>{{ __('service.lbl_advanced_filter') }}</h4>
    </x-slot>
    <form action="javascript:void(0)" class="datatable-filter">
        <div class="form-group">

            <label class="form-label" for="name">{{ __('users.lbl_name') }}</label>
            <input type="text" name="name" id="name" class="form-control"
                placeholder="">
        </div>

        <div class="form-group">
        <label for="level" class="form-label">{{ __('plan.lbl_level') }}</label>
        <select class="form-control select2" name="level" id="level">
                <option value="">{{ __('Select Level') }}</option>
                @if(isset($plan) && $plan > 0)
                @for($i = 1; $i <= $plan + 1; $i++)
                  <option value="{{ $i }}">{{ 'Level ' . $i }}</option>
                @endfor
               @else
               <option value="1" selected>{{ 'Level 1' }}</option>
               @endif
           </select>
        </div>

        <div class="form-group">
        <label for="level" class="form-label">{{ __('plan.lbl_amount') }}</label>

        <select class="form-control select2" name="price" id="price" required>
        <option value="">{{ __('Select Price') }}</option>
        @for($price = $minPrice; $price <= $maxPrice-50; $price += 50) {{-- Change 10 to the desired step value --}}
            <option value="{{ $price }} - {{ $price + 50}}" {{ old('price') == $price ? 'selected' : '' }}>{{Currency::format($price) }} - {{Currency::format($price + 50)}}</option>
        @endfor
    </select>
   </div>
    </form>
    <button type="reset" class="btn btn-danger" id="reset-filter">Reset</button>
</x-backend.advance-filter>
@endsection

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
            { data: 'name', name: 'name', title: "{{ __('plan.lbl_name') }}"  },
            { data: 'duration', name: 'duration', title: "{{ __('plan.lbl_duration') }}" },
            { data: 'level', name: 'level', title: "{{ __('plan.lbl_level') }}"  },
            { data: 'price', name: 'price', title: "{{ __('plan.lbl_amount') }}" },
            { data: 'status', name: 'status', orderable: false, searchable: true, title: "{{ __('plan.lbl_status') }}" },
        ]

        const actionColumn = [
            { data: 'action', name: 'action', orderable: false, searchable: false, title: 'Action' }
        ]



       let finalColumns = [
           ...columns,
           ...actionColumn
       ]


   $('#name').on('input', function() {
        window.renderedDataTable.ajax.reload(null, false);
    });
    $('#price').on('input', function() {
        window.renderedDataTable.ajax.reload(null, false);
    });
    $('#level').on('input', function() {
        window.renderedDataTable.ajax.reload(null, false);
    });

document.addEventListener('DOMContentLoaded', (event) => {
    initDatatable({
        url: '{{ route("backend.$module_name.index_data") }}',
        finalColumns,
        orderColumn: [
            [4, "desc"]
        ],
        advanceFilter: () => {
            return {
                name: $('#name').val(),
                price: $('#price').val(),
                level: $('#level').val()
            }
        }
    });
})



$('#reset-filter').on('click', function(e) {
    $('#name').val(''),
    $('#email').val('')
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


<script>
function tableReload() {
    $('#datatable').DataTable().ajax.reload();
}
$(document).on('click', '[data-form-delete]', function() {
    const URL = $(this).attr('data-form-delete')
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: URL,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Deleted Successfully!",
                        icon: "success"
                    });
                    tableReload()
                }
            })
        }
    });
});
</script>
@endpush
