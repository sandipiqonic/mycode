<div class="d-flex gap-2 align-items-center">

    <!-- Soft Delete (Trash) -->
    @hasPermission('edit_episodes')
    <a  class="btn btn-primary" href="{{ route('backend.episodes.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>
    @endhasPermission

    <a class="btn btn-primary btn-sm"  href="{{ route('backend.episodes.download-option', $data->id) }}" title="Download">
        <i class="fa-solid fa-download"></i>
    </a>
   
    @hasPermission('delete_episodes')
    @if(!$data->trashed())
    <a href="{{route('backend.episodes.destroy', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>

@else
  <!-- Restore link -->
  <a class="restore-tax" href="{{ route('backend.episodes.restore', $data->id) }}">
      <i class="fas fa-redo text-secondary"></i>
  </a>

  <a href="{{route('backend.episodes.force_delete', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>
  <!-- Force Delete link -->

  @endif

  @endhasPermission
</div>

