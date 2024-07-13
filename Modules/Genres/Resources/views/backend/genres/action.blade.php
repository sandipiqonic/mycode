<div class="d-flex gap-2 align-items-center">
    @haspermission('delete_genres')
    @if(!$data->trashed())
<a  class="btn btn-primary" href="{{ route('backend.genres.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>
    <a href="{{route('backend.genres.destroy', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>
    @else
    <!-- Restore link -->
    <a class="restore-tax" href="{{ route('backend.genres.restore', $data->id) }}">
        <i class="fas fa-redo text-secondary"></i>
    </a>
    <!-- Force Delete link -->

    <a href="{{route('backend.genres.force_delete', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>

    @endif
@endhasPermission
</div>


