@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <div class="header-title">
            <h4 class="mb-0">{{ __('settings.change_layout_order') }}</h4>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"> <i class="fas fa-plus-circle me-2"></i>{{__('messages.new')}}</button>
        </div>

        @if(session('success'))
            <div class="snackbar" id="snackbar">
                <div class="d-flex justify-content-around align-items-center">
                    <p class="mb-0">{{ session('success') }}</p>
                    <a href="#" class="dismiss-link text-decoration-none text-success" onclick="dismissSnackbar(event)">Dismiss</a>
                </div>
            </div>
        @endif
    </div>

    <div id="sortable">
        @foreach($data as $mobile_setting)
            <div class="row mobile-setting-row" data-id="{{ $mobile_setting->id }}" data-position="{{ $mobile_setting->position }}">
                <div class="col-md-1">
                    <button class="drag-button" data-id="{{ $mobile_setting->id }}"><i class="fas fa-plus me-2"></i></button> 
                </div>
                <div class="col-md-11">
                    <div class="card">
                        <div class="card-body">   
                            <div class="d-flex">
                                <h5 class="me-5">{{ $mobile_setting->name }}:</h5>
                                @if($mobile_setting->slug == 'banner' || $mobile_setting->slug == 'continue-watching' || $mobile_setting->slug == 'rate-our-app')
                                    <div class="form-check form-switch">
                                        {{ html()->hidden('value', 0) }}
                                        {{
                                            html()->checkbox('value', old('value', 1))
                                                    ->class('form-check-input status-switch')
                                                    ->id('value')
                                                    ->value(1) 
                                                    ->data('id', $mobile_setting->id)
                                                    ->data('name', $mobile_setting->name)
                                                    ->data('position', $mobile_setting->position)
                                        }}
                                    </div>
                                @endif

                                @if($mobile_setting->slug !== 'banner' && $mobile_setting->slug !== 'continue-watching' && $mobile_setting->slug !== 'rate-our-app')
                                    <p class="position-number">{{ __('settings.lbl_position_number') }}: {{ $mobile_setting->position }}</p>
                                    <div class="d-flex justify-content-end">
                                        <button class="accordion-button collapsed mx-3 " data-id="{{ $mobile_setting->id }}" data-bs-toggle="collapse" data-bs-target="#accordian_btn_{{ $mobile_setting->id }}" aria-expanded="false" aria-controls="accordian_btn_{{ $mobile_setting->id }}">
                                            <i class="fas fa-plus me-2"></i>
                                        </button> 
                                        <button class="mx-3 edit-button" data-id="{{ $mobile_setting->id }}"> 
                                            <i class="fa-solid fa-pen-clip text-success"></i>
                                        </button>
                                        <button class="mx-3 delete-button" data-id="{{ $mobile_setting->id }}"> 
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="accordian_btn_{{ $mobile_setting->id }}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            {{ html()->form('POST' ,route('backend.mobile-setting.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
                            @csrf
                            {{ html()->hidden('id')->value($mobile_setting->id) }}
                            {{ html()->hidden('name')->value($mobile_setting->name) }}
                            {{ html()->hidden('position')->value($mobile_setting->position) }}
                            
                            <div class="mb-3">
                                {{ html()->label(__('movie.lbl_select'). ' ' . $mobile_setting->name .':'. '<span class="text-danger">*</span>', 'dashboard_select')->class('form-label') }}
                                {{ html()->select('dashboard_select[]', old('dashboard_select'))->class('form-control select2')->id('dashboard_select_'.$mobile_setting->id)->multiple() }}
                                @error('dashboard_select')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
                            {{ html()->form()->close() }}
                        </div>
                    </div> 
                </div>
            </div>
        @endforeach
    </div>


    <div class="modal fade @if ($errors->any()) show @endif" id="addModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"> 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('settings.mobile_setting') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                {{ html()->form('POST' ,route('backend.mobile-setting.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}
                    @csrf
                    {{ html()->hidden('id')->id('mobileSettingId')->value(isset($mobileSetting) ? $mobileSetting->id : '') }}
                    <div class="mb-3">
                        {{ html()->label(__('settings.lbl_name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                        {{ html()->text('name')
                                    ->attribute('value', old('name'))  ->placeholder(__('placeholder.lbl_mobile_setting_name'))
                                    ->class('form-control')
                                }}
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        {{ html()->label(__('settings.lbl_position_number') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                        {{ html()->number('position')
                                    ->attribute('value', old('position'))  ->placeholder(__('placeholder.lbl_position'))
                                    ->class('form-control')
                                }}
                        @error('position')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <a href="{{ route('backend.mobile-setting.index') }}" class="btn btn-secondary">Close</a>
                    {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-right') }}
                {{ html()->form()->close() }}
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
     
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
     
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addModalElement = document.getElementById('addModal');
        const addModalInstance = new bootstrap.Modal(addModalElement, {});

        @if ($errors->any())
            addModalInstance.show();
            addModalElement.addEventListener('hide.bs.modal', function (event) {
                event.preventDefault();
            });
        @endif

        addModalElement.addEventListener('hidden.bs.modal', function () {
            addModalElement.querySelectorAll('input:not([name="_token"])').forEach(input => input.value = '');
            addModalElement.querySelectorAll('textarea').forEach(textarea => textarea.value = '');
        });
    });

    function showMessage(message) {
        Snackbar.show({
            text: message,
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        $('.edit-button').on('click', function() {
            var id = $(this).data('id');
            var editUrl = '{{ route('backend.mobile-setting.edit', ':id') }}';
            editUrl = editUrl.replace(':id', id);

            $.ajax({
                url: editUrl,
                method: 'GET',
                success: function(data) {
                    $('#mobileSettingId').val(data.id);
                    $('input[name="name"]').val(data.name);
                    $('input[name="position"]').val(data.position);
                    $('#addModal').modal('show');
                }
            });
        });

        $('.delete-button').on('click', function() {
            var id = $(this).data('id');
            var deleteUrl = '{{ route('backend.mobile-setting.destroy', ':id') }}';
            deleteUrl = deleteUrl.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Done',
                                text: data.message,
                                icon: 'success',
                                iconColor: '#5F60B9'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });

        $('.accordion-button').on('click', function() {
            var mobileSettingId = $(this).data('id');
            var dropdown = $('#dashboard_select_' + mobileSettingId);

            $.ajax({
                url: '{{ route('backend.mobile-setting.get-dropdown-value', ':id') }}'.replace(':id', mobileSettingId),
                method: 'GET',
                success: function(data) {
                    dropdown.empty();

                    if (data.selected) {
                        $.each(data.selected, function(key, value) {
                            dropdown.append($('<option>', {
                                value: value.id,
                                text: value.name,
                                selected: true
                            }));
                        });
                    }

                    if (data.available) {
                        $.each(data.available, function(key, value) {
                            if (!data.selected || !data.selected.some(selectedItem => selectedItem.id === value.id)) {
                                dropdown.append($('<option>', {
                                    value: value.id,
                                    text: value.name
                                }));
                            }
                        });
                    }

                    dropdown.trigger('change'); 
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch dropdown values:', error);
                }
            });
        });

        $('.status-switch').on('change', function() {
            var value = $(this).is(':checked') ? 1 : 0;
            var id = $(this).data('id');
            var name = $(this).data('name');
            var position = $(this).data('position');

            $.ajax({
                url: '{{ route('backend.mobile-setting.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    name: name,
                    position: position,
                    value: value
                },
                success: function(response) {
                    showMessage(response.message);
                },
                error: function(xhr, status, error) {
                    console.error('Failed to update status:', error);
                }
            });
        });
    }); 


    document.addEventListener('DOMContentLoaded', (event) => {
        let draggedElement = null;

        document.querySelectorAll('.drag-button').forEach(button => {
            button.addEventListener('mousedown', (e) => {
                const row = document.querySelector(`[data-id="${button.dataset.id}"]`);
                row.setAttribute('draggable', 'true');

                row.addEventListener('dragstart', (e) => {
                    draggedElement = row;
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/html', row.innerHTML);
                    row.classList.add('dragging');
                });

                row.addEventListener('dragend', (e) => {
                    row.classList.remove('dragging');
                    row.removeAttribute('draggable');
                    updatePositions();
                }, { once: true });

                row.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';

                    const draggingElement = document.querySelector('.dragging');
                    const rows = [...document.querySelectorAll('.mobile-setting-row:not(.dragging)')];
                    const nextElement = rows.find(row => e.clientY < row.offsetTop + row.offsetHeight / 2);
                    
                    const parent = document.getElementById('sortable');
                    if (nextElement) {
                        parent.insertBefore(draggingElement, nextElement);
                    } else {
                        parent.appendChild(draggingElement);
                    }
                });

                row.addEventListener('drop', (e) => {
                    e.stopPropagation();
                    e.preventDefault();
                });
            });
        });

        function updatePositions() {
            const rows = document.querySelectorAll('.mobile-setting-row');
            let sortedIDs = [];

            rows.forEach((row, index) => {
                row.setAttribute('data-position', index + 1);
                sortedIDs.push(row.getAttribute('data-id'));

                const positionNumberElement = row.querySelector('.position-number');
                if (positionNumberElement) {
                    positionNumberElement.textContent = `{{ __('settings.lbl_position_number') }}: ${index + 1}`;
                }
            });

            $.ajax({
                url: '{{ route('backend.mobile-setting.update-position') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    sortedIDs: sortedIDs
                },
                success: function(response) {
                    console.log('Positions updated successfully.');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to update positions:', error);
                }
            });
        }
    });

</script>
@endpush
