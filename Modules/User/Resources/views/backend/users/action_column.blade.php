<div class="d-flex gap-2 align-items-center">

       <a  class="btn btn-primary" href="{{ route('backend.users.edit', $data->id) }}"> <i class="fa-solid fa-pen-clip"></i></a>

       <a class="btn btn-primary" href="{{ route('backend.users.changepassword', $data->id) }}">
    <i class="fa-solid fa-lock"></i>
</a>

       <button type="button" class="btn btn-danger" data-form-delete="{{ route('backend.users.destroy', $data->id) }}">Delete</button>

</div>

