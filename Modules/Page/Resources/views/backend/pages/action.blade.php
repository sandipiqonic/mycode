<div class="d-flex gap-2 align-items-center">
  {{-- @hasPermission('edit_pages') --}}
       <a  class="copy-url" href="{{ route('backend.copyurl',$data->slug) }}" onclick="copyURL(event)"> <i class="fa-solid fa-clipboard"></i></a>
       <a  class="btn btn-primary" href="{{ route('backend.pages.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>

  {{-- @endhasPermission --}}
  {{-- @hasPermission('delete_pages') --}}
  @if(!$data->trashed())
  <!-- Soft Delete (Trash) -->
  <a class="mr-3 delete-tax" href="{{ route('backend.pages.destroy', $data->id) }}">
      <i class="far fa-trash-alt text-danger"></i>
  </a>
@else
  <!-- Restore link -->
  <a class="restore-tax" href="{{ route('backend.pages.restore', $data->id) }}">
      <i class="fas fa-redo text-secondary"></i>
  </a>
  <!-- Force Delete link -->
  <a class="force-delete-tax" href="{{ route('backend.pages.force_delete', $data->id) }}">
      <i class="far fa-trash-alt text-danger"></i>
  </a>
@endif
  {{-- @endhasPermission --}}
</div>

