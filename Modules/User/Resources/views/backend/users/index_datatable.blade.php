@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-backend.section-header>
        @if($type == null )
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
                            <input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="users" onclick="selectAllTable(this)">
                        </div>
                        @endif
            <x-slot name="toolbar">
            @if($type=='soon-to-expire')
            <button id="send-email-btn" class="btn btn-primary">{{ __('messages.send_reminder') }}</button>
          @endif
          @if($type == null )
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
                @endif
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i
                            class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control dt-search" placeholder="Search..." aria-label="Search"
                        aria-describedby="addon-wrapping">
                </div>
                @if($type == null )
                <a href="{{ route('backend.' . $module_name . '.create') }}" class="btn btn-primary"
                    id="add-post-button"> <i class="fas fa-plus-circle me-2"></i>{{__('messages.new')}}</a>
                @endif
                {{-- <button class="btn btn-secondary d-flex align-items-center gap-1 btn-group" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i
                        class="ph ph-funnel"></i>{{__('messages.advance_filter')}}</button> --}}
            </x-slot>
        </x-backend.section-header>
        <table id="datatable" class="table table-responsive">
        </table>
    </div>
</div>
<!-- Success Message Container -->
<div id="success-message" class="alert alert-success" style="display: none; text-align: center; width: auto; position: fixed; top: 0; right: 0; margin: 50px;">
  <strong>{{__('messages.mail_success')}}</strong> {{__('messages.mail_send')}}
</div>

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
            <label class="form-label" for="email">{{ __('users.lbl_email') }}</label>
            <input type="text" name="email" id="email" class="form-control"
                placeholder="">

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
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>



<script type="text/javascript" defer>
const columns = [{
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
        title: "{{ __('users.lbl_name') }}"
    },
    {
        data: 'mobile',
        name: 'mobile',
        title: "{{ __('users.lbl_contact_number') }}"
    },
    {
        data: 'gender',
        name: 'gender',
        title: "{{ __('users.lbl_gender') }}"
    },

    {
        data: 'status',
        name: 'status',
        orderable: false,
        searchable: true,
        title: "{{ __('users.lbl_status') }}"
    },
    {
        data: 'updated_at',
        name: 'updated_at',
        title: "{{ __('users.lbl_update_at') }}",
        orderable: true,
        visible: false,
    },

]


const actionColumn = [{
    data: 'action',
    name: 'action',
    orderable: false,
    searchable: false,
    title: "{{ __('service.lbl_action') }}",
    width: '5%'
}]

let finalColumns = [
    ...columns,
]
if (!('{{ $type }}')) {
        finalColumns = [...finalColumns, ...actionColumn];
    }

    if (('{{ $type }}' == 'soon-to-expire')) {
      finalColumns.push({
          name: 'expire_date',
          data: 'expire_date',
          title: 'Expire Date',
          orderable: false,
          searchable: false,
      });
    }

   $('#name').on('input', function() {
        window.renderedDataTable.ajax.reload(null, false);
    });
    $('#email').on('input', function() {
        window.renderedDataTable.ajax.reload(null, false);
    });

document.addEventListener('DOMContentLoaded', (event) => {
    initDatatable({
        url: '{{ route("backend.$module_name.index_data",['type' => $type]) }}',
        finalColumns,
        orderColumn: [
            [4, "desc"]
        ],
        advanceFilter: () => {
            return {
                name: $('#name').val(),
                email: $('#email').val()
            }
        }
    });
    const datatable = $('#datatable').DataTable();

      datatable.on('draw', function () {
          const rowCount = datatable.rows().count();

          if (rowCount === 0) {
              document.getElementById('send-email-btn').style.display = 'none';
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


function showMessage(message) {
            Snackbar.show({
                text: message,
                pos: 'bottom-left'
            });
        }
        $(document).ready(function() {
          $('#send-email-btn').click(function() {
              const confirmationMessage = "Are you sure you want to send the email?";
              confirmSwal(confirmationMessage).then((result) => {
                  if (result.isConfirmed) {
                      sendEmail();
                  }
              });
          });

          function sendEmail() {
            
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              $.ajax({
                  url: '{{ route("backend.send.email") }}',
                  type: 'POST',
                  data: {
                      _token: csrfToken 
                  },
                  success: function(response) {
                      showMessage(response.message);
                  },
                  error: function(xhr, status, error) {
                      console.log('Failed to send emails' + error);
                  }
              });
          }
        });
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
