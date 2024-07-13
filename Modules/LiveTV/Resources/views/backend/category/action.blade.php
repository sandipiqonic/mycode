<div class="d-flex gap-2 align-items-center">
  @hasPermission('edit_tvcategory')
       <a  class="btn btn-primary" href="{{ route('backend.tv-category.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>

  @endhasPermission
  @hasPermission('delete_tvcategory')
  @if(!$data->trashed())
  <!-- Soft Delete (Trash) -->
  <a class="mr-3 delete-tax" href="{{ route('backend.tv-category.destroy', $data->id) }}">
      <i class="far fa-trash-alt text-danger"></i>
  </a>

@else
  <!-- Restore link -->
  <a class="restore-tax" href="{{ route('backend.tv-category.restore', $data->id) }}">
      <i class="fas fa-redo text-secondary"></i>
  </a>
  <!-- Force Delete link -->

  <a class="force-delete-tax" href="{{ route('backend.tv-category.force_delete', $data->id) }}">
      <i class="far fa-trash-alt text-danger"></i>
  </a>

@endif
  @endhasPermission
</div>

