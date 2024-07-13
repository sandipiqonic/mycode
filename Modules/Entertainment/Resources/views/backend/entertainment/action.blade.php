<div class="d-flex gap-2 align-items-center">

  @if(!$data->trashed())
  <!-- Soft Delete (Trash) -->

    @hasPermission('edit_movies')
    <a class="btn btn-primary btn-sm" href="{{ route('backend.entertainments.edit', $data->id) }}" title="Edit">
      <i class="fa-solid fa-pen-clip"></i>
  </a>
  @endhasPermission
  <a class="btn btn-primary btn-sm"  href="{{ route('backend.entertainments.details', $data->id) }}" title="Details">
    <i class="fa fa-circle-info"></i>
</a>

  <a class="btn btn-primary btn-sm"  href="{{ route('backend.entertainments.download-option', $data->id) }}" title="Download">
      <i class="fa-solid fa-download"></i>
  </a>

  @hasPermission('delete_movies')
  <a href="{{route("backend.entertainments.destroy", $data->id)}}" id="delete-{{$module_name}}-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>

  @else
  <!-- Restore link -->
  <a class="restore-tax" href="{{ route('backend.entertainments.restore', $data->id) }}">
      <i class="fas fa-redo text-secondary"></i>
  </a>
  <!-- Force Delete link -->
  <a href="{{route("backend.entertainments.force_delete", $data->id)}}" id="delete-{{$module_name}}-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>
  @endhasPermission
  @endif
</div>

