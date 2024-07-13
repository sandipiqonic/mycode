
     <div class="d-flex gap-2 align-items-center">

         @hasPermission('edit_plans')
         <a  class="btn btn-primary" href="{{ route('backend.plans.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>
         @endhasPermission

         @hasPermission('delete_plans')
         @if(!$data->trashed())
        <a href="{{route('backend.plans.destroy', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>

        @else

        <a class="restore-tax" href="{{ route('backend.plans.restore', $data->id) }}">
            <i class="fas fa-redo text-secondary"></i>
        </a>



        <a href="{{route('backend.plans.force_delete', $data->id)}}" id="delete-locations-{{$data->id}}" class="btn btn-danger-subtle btn-sm" data-type="ajax" data-method="DELETE" data-token="{{csrf_token()}}" data-bs-toggle="tooltip" title="{{__('messages.delete')}}" data-confirm="{{ __('messages.are_you_sure?') }}"> <i class="fa-solid fa-trash"></i></a>
        @endif
        @endhasPermission
  </div>





