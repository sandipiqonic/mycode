<div class="d-flex gap-2 align-items-center">

    @if (!$data->trashed())
        {{-- @hasPermission('edit_videos') --}}
        <a class="btn btn-primary" href="{{ route('backend.videos.edit', $data->id) }}"> <i
                class="fa-solid fa-pen-clip"></i></a>

        {{-- @endhasPermission
  @hasPermission('delete_videos') --}}
        <!-- Soft Delete (Trash) -->

        <a class="btn btn-primary btn-sm" href="{{ route('backend.videos.download-option', $data->id) }}"
            title="Download">
            <i class="fa-solid fa-download"></i>
        </a>

        <a href="{{ route('backend.videos.destroy', $data->id) }}" id="delete-{{ $module_name }}-{{ $data->id }}"
            class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{ csrf_token() }}"
            data-bs-toggle="tooltip" title="{{ __('messages.delete') }}"
            data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>
    @else
        <!-- Restore link -->
        <a class="restore-tax" href="{{ route('backend.videos.restore', $data->id) }}">
            <i class="fas fa-redo text-secondary"></i>
        </a>

        <a href="{{ route('backend.videos.force_delete', $data->id) }}"
            id="delete-{{ $module_name }}-{{ $data->id }}" class="btn btn-danger-subtle btn-sm" data-type="ajax"
            data-method="DELETE" data-token="{{ csrf_token() }}" data-bs-toggle="tooltip"
            title="{{ __('messages.delete') }}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i
                class="fa-solid fa-trash"></i></a>
    @endif
    {{-- @endhasPermission --}}
</div>
