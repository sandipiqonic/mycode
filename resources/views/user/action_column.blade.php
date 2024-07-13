<div class="d-flex gap-2 align-items-center">
  @hasPermission('edit_taxes')
       <a  class="btn btn-primary" href="{{ route('backend.taxes.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>

  @endhasPermission
  @hasPermission('delete_taxes')
        <button type="button" class="btn btn-danger" data-form-delete="{{ route('backend.taxes.destroy', $data->id) }}">Delete</button>
  @endhasPermission
</div>

