<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddUser">    
        @csrf
        <input type="hidden" id="userId" name="userId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">Nuevo Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="for-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del Usuario" required>
                    </div>
                    <div class="for-group mt-2">
                        <label>Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Ingresar Email">
                    </div>
                    <div class="for-group mt-2">
                        <label>Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group mt-2">
                        <label>Rol de Usuario:</label>
                        <x-adminlte-select2 id="roleId" name="roleId" label-class="text-lightblue" >
                            <option value=""></option>
                            @foreach($roles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>    
                </div>
                <div class="modal-footer">
                    <button id="addUser" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>