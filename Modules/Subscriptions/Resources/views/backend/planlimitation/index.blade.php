@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection


@push('after-styles')
    <link rel="stylesheet" href="{{ mix('modules/subscriptions/style.css') }}">
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <x-backend.section-header>
              <div class="d-flex flex-wrap gap-3">

                <x-backend.quick-action url='{{ route("backend.$module_name.bulk_action") }}'>
                  <div class="">
                    <select name="action_type" class="form-control select2 col-12" id="quick-action-type" style="width:100%">
                        <option value="">{{ __('messages.no_action') }}</option>

                        <option value="change-status">{{ __('messages.status') }}</option>

                        <option value="delete">{{ __('messages.delete') }}</option>
                        <option value="restore">{{ __('messages.restore') }}</option>
                        <option value="permanently-delete">{{ __('messages.permanent_dlt') }}</option>

                    </select>
                  </div>
                  <div class="select-status d-none quick-action-field" id="change-status-action">
                      <select name="status" class="form-control select2" id="status" style="width:100%">
                        <option value="" selected>{{ __('messages.select_status') }}</option>
                        <option value="1">{{ __('messages.active') }}</option>
                        <option value="0">{{ __('messages.inactive') }}</option>
                      </select>
                  </div>
                </x-backend.quick-action>

                <div>
                    <button type="button" class="btn btn-primary" data-modal="export">
                    <i class="ph ph-download-simple me-1"></i> {{ __('messages.export') }}
                    </button>
                    {{-- <button type="button" class="btn btn-secondary" data-modal="import">--}}
                    {{-- <i class="fa-solid fa-upload"></i> Import--}}
                    {{-- </button>--}}
                </div>
              </div>
              <div class="pt-5">
                            <label for="form-label"> {{ __('livetv.checkall') }} </label>
                            <input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="plan-limitation" onclick="selectAllTable(this)">
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
                    <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control dt-search" placeholder="{{ __('messages.search') }}..." aria-label="Search" aria-describedby="addon-wrapping">
                  </div>

                  @hasPermission('add_planlimitation')
                  <a href="{{ route('backend.'. $module_name . '.create') }}" class="btn btn-primary"
                    id="add-post-button"> <i class="fas fa-plus-circle me-2"></i>{{__('messages.new')}}</a>
                    @endhasPermission


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

    <div data-render="app">


        <!-- <plan-limit-offcanvas
            create-title="{{ __('messages.create') }} {{ __('messages.new') }} {{ __($module_title) }}"
            edit-title="{{ __('messages.edit') }} {{ __($module_title) }}">
        </plan-limit-offcanvas> -->

        <x-backend.advance-filter>
          <x-slot name="title"><h4>Advanced Filter</h4></x-slot>
          <select name="" id="" class="select2">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>
        </x-backend.advance-filter>
    </div>
@endsection

@push('after-styles')
    <!-- DataTables Core and Extensions -->
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush

@push('after-scripts')
<script src="{{ asset('js/form-modal/index.js') }}" defer></script>
<script src="{{ asset('js/form/index.js') }}" defer></script>

    <!-- DataTables Core and Extensions -->
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
            { data: 'title', name: 'title', title: "{{ __('plan_limitation.lbl_title') }}" },
            { data: 'description', name: 'description',  title: "{{ __('plan_limitation.lbl_description') }}" },
            { data: 'status', name: 'status', orderable: false, searchable: true,  title: "{{ __('plan_limitation.lbl_status') }}" },
        ]

        const actionColumn = [
            { data: 'action', name: 'action', orderable: false, searchable: false, title: 'Action' }
        ]


        let finalColumns = [
            ...columns,
            ...actionColumn
        ]

        document.addEventListener('DOMContentLoaded', (event) => {
            initDatatable({
                url: '{{ route("backend.$module_name.index_data") }}',
                finalColumns,
            })
        })







    function resetQuickAction () {
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

      $('#quick-action-type').change(function () {
        resetQuickAction()
      });

      $(document).on('update_quick_action', function() {
        // resetActionButtons()
      })
    </script>
@endpush
