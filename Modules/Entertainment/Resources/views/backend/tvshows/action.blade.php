<div class="d-flex gap-2 align-items-center">


    @hasPermission('edit_tvshows')
    <a  class="btn btn-primary btn-sm" href="{{ route('backend.tvshows.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>
    @endhasPermission
    <a class="btn btn-primary btn-sm"  href="{{ route('backend.entertainments.details', $data->id) }}" title="Details">
        <i class="fa-solid fa-circle-info"></i>
    </a>

    {{-- @endhasPermission
    @hasPermission('delete_entertainments') --}}
    <!-- Soft Delete (Trash) -->
    @hasPermission('delete_tvshows')
    @if(!$data->trashed())
  <a href="{{route("backend.entertainments.destroy", $data->id)}}" id="delete-{{$module_name}}-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>

@else
  <!-- Restore link -->
  <a class="restore-tax" href="{{ route('backend.entertainments.restore', $data->id) }}">
      <i class="fas fa-redo text-secondary"></i>
  </a>
  <!-- Force Delete link -->
  <a href="{{route("backend.entertainments.force_delete", $data->id)}}" id="delete-{{$module_name}}-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>
  @endif
  @endhasPermission
  {{-- @endhasPermission --}}
</div>

