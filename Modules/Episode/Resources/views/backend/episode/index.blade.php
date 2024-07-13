@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
    <div class="card-main">
            <x-backend.section-header>
                <div class="d-flex flex-wrap gap-3">
                    {{-- @if(auth()->user()->can('edit_'.$module_name) || auth()->user()->can('delete_'.$module_name)) --}}
                    <x-backend.quick-action url="{{ route('backend.' . $module_name . '.bulk_action') }}">
                        <div class="">
                            <select name="action_type" class="form-control select2 col-12" id="quick-action-type"
                                style="width:100%">
                                <option value="">{{ __('messages.no_action') }}</option>
                                {{-- @can('edit_'.$module_name) --}}
                                <option value="change-status">{{ __('messages.status') }}</option>
                                {{-- @endcan --}}
                                {{-- @can('delete_'.$module_name) --}}
                                <option value="delete">{{ __('messages.delete') }}</option>
                                {{-- @endcan --}}
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
                    {{-- @endif --}}


                      <button type="button" class="btn btn-secondary" data-modal="export">
                        <i class="fa-solid fa-download"></i> {{ __('messages.export') }}
                      </button>
                </div>
                <div class="pt-5">
                    <label for="form-label"> {{ __('livetv.checkall') }} </label>
                    <input type="checkbox" class="form-check-input" name="select_all_table" id="select-all-table" data-type="episode" onclick="selectAllTable(this)">
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
                        <input type="text" class="form-control dt-search" placeholder="{{__('placeholder.lbl_search')}}" aria-label="Search"
                            aria-describedby="addon-wrapping">
                    </div>
                    <button class="btn btn-secondary d-flex align-items-center gap-1 btn-group" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"><i class="ph ph-funnel"></i>{{__('messages.advance_filter')}}</button>
                        @hasPermission('add_'.$module_name)
                            <a href="{{ route('backend.' . $module_name . '.create') }}" class="btn btn-dark" id="add-post-button"> <i class="ph ph-plus me-2"></i>{{__('messages.new')}}</a>
                        @endhasPermission
                    
                </x-slot>
            </x-backend.section-header>
            <table id="datatable" class="table table-responsive">
            </table>
    </div>
    <x-backend.advance-filter>
        <x-slot name="title">
            <h4>{{ __('service.lbl_advanced_filter') }}</h4>
        </x-slot>

            <div class="form-group">
                <div class="form-group datatable-filter">
                    <label class="form-label" for="entertainment_id">{{__('movie.lbl_tv_show')}}</label>
                    <select name="entertainment_id" id="entertainment_id" class="form-control select2" data-filter="select">
                        <option value="">{{ __('service.all') }} {{__('movie.lbl_tv_show')}}</option>
                        @foreach ($tvshows as $tvshow)
                            <option value="{{ $tvshow->id }}">{{ $tvshow->name }}</option>
                        @endforeach
                    </select>
                </div>

            <div class="form-group datatable-filter">
                {{ html()->label(__('episode.lbl_season'), 'season_id')->class('form-label') }}
                {{ html()->select(
                        'season_id',
                        $seasons->pluck('name', 'id')->prepend('Select Season',''),old('season_id'))->class('form-control select2')->id('season_id') }}
                @error('season_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
          </div>
        <button type="reset" class="btn btn-danger" id="reset-filter">Reset</button>
    </x-backend.advance-filter>
    @if(session('success'))
        <div class="snackbar" id="snackbar">
            <div class="d-flex justify-content-around align-items-center">
                <p class="mb-0">{{ session('success') }}</p>
                <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
            </div>
        </div>
    @endif
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
            { data: 'poster_url',
              name: 'poster_url',
              title: "{{ __('genres.lbl_image') }}",
              orderable: false,
              searchable: false,
             },
            {
                data: 'name',
                name: 'name',
                title: "{{ __('service.lbl_name') }}"
            },

            {
                data: 'entertainment_id',
                name: 'entertainment_id',
                title: "{{ __('season.lbl_tv_shows') }}"
            },

            {
                data: 'season_id',
                name: 'season_id',
                title: "{{ __('season.lbl_season') }}"
            },
            {
                data: 'status',
                name: 'status',
                title: "{{ __('service.lbl_status') }}",
                width: '5%',
            },
            {
              data: 'updated_at',
              name: 'updated_at',
              title: "{{ __('service.lbl_update_at') }}",
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
            ...actionColumn
        ]

        document.addEventListener('DOMContentLoaded', (event) => {

            var selectedaccess=null;


            $('#entertainment_id').on('input', function() {
               window.renderedDataTable.ajax.reload(null, false);
            });

            $('#season_id').on('input', function() {
               window.renderedDataTable.ajax.reload(null, false);
            });

            initDatatable({
                url: '{{ route("backend.$module_name.index_data") }}',
                finalColumns,
                orderColumn: [[ 5, "desc" ]],
                advanceFilter: () => {
                    return {
                        entertainment_id: $('#entertainment_id').val(),
                        season_id: $('#season_id').val(),
                    }
                }
            });
        })

        $('#reset-filter').on('click', function(e) {
            $('#entertainment_id').val(''),
            $('#season_id').val(''),

            window.renderedDataTable.ajax.reload(null, false)
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

        function getSeasons(entertainmentId, selectedSeasonId = "") {
       var get_seasons_list = "{{ route('backend.seasons.index_list', ['entertainment_id' => '']) }}" + entertainmentId;
       get_seasons_list = get_seasons_list.replace('amp;', '');

       $.ajax({
           url: get_seasons_list,
           success: function(result) {

               var formattedResult = result.map(function(season) {
                   return { id: season.id, text: season.name };
               });

               $('#season_id').select2({
                   width: '100%',
                   placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}",
                   data: formattedResult
               });

               if (selectedSeasonId != "") {
                   $('#season_id').val(selectedSeasonId).trigger('change');
               }
           }
       });
    }

    $(document).ready(function() {
    $('#entertainment_id').change(function() {

      var entertainmentId = $(this).val();

       if (entertainmentId) {
            $('#season_id').empty().select2({
                width: '100%',
                placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
            });
            getSeasons(entertainmentId);
        } else {
            $('#season_id').empty().select2({
                width: '100%',
                placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.season')]) }}"
            });
        }
    });
});



</script>
@endpush
