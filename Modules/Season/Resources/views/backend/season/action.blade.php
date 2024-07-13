<div class="d-flex gap-2 align-items-center">


    @hasPermission('edit_seasons')
    <a  class="btn btn-primary" href="{{ route('backend.seasons.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>
    @endhasPermission
    @hasPermission('delete_seasons')
    @if(!$data->trashed())
    <!-- Soft Delete (Trash) -->
  <a href="{{route('backend.seasons.destroy', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>

  @else
  <!-- Restore link -->
  <a class="restore-tax" href="{{ route('backend.seasons.restore', $data->id) }}">
      <i class="fas fa-redo text-secondary"></i>
  </a>


  <a href="{{route('backend.seasons.force_delete', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>

@endif
  @endhasPermission
</div>

