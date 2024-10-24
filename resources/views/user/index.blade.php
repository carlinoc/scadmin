@extends('adminlte::page')

@section('title', 'Mantenimiento de Usuarios')

@section('content_header')
    <h1>Mantenimiento de Usuarios</h1>
@stop

@section('content')
    @role(['Admin'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newUser" class="btn btn-primary">Nuevo Usuario</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtUser" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('user.create')
@endrole   

@role(['Mozo', 'Maitre'])
<p style="color: red">No tiene permisos para esta sección</p>
@endrole 
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _userId = $("#userId");
    let _name = $("#name");
    let _email = $("#email");
    let _password = $("#password");
    let _roleId = $("#roleId");

    let _dtUser = $("#dtUser");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    $(document).ready(function() {

        fetchUsers();    

        $('#newUser').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nuevo Usuario");
            _modal.modal('show');
            
            setTimeout(function(){
                _name.focus();
            }, 300);
        });

        _dtUser.on('click', '.editUser', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _userId.val(id);
                _name.val(name);
                _email.val(email);
                _password.val('');
                _roleId.val(role).change();
            }
            
            _modalLabel.text("Editar Usuario");
            _modal.modal('show');
        });

        _dtUser.on('click', '.removeUser', function (e) {
            e.preventDefault();
            let userId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el usuario?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/user/remove/" + userId, {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-Token": _token
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            showSuccessMsg(result.message);
                            fetchUsers();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });

    $('#addUser').on('click', function(e) {
        e.preventDefault();
        let elements = [
            ['name', 'Ingrese el nombre'],
            ['email', 'Ingrese el email'],
            ['password', 'Ingrese la contraseña']
        ];

        if(emptyfy(elements)) {
            let userId = _userId.val();
            
            let route = "{{ route('user.add') }}";
            if(userId!="") {
                route = "{{ route('user.edit') }}";
            }

            const data = new URLSearchParams();
            const myform = document.getElementById('frmAddUser');
            for (const pair of new FormData(myform)) {
                data.append(pair[0], pair[1]);
            }

            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    _modal.modal('hide');
                    clearForm();
                    fetchUsers();
                }
                if(result.status=="error"){
                    showErrorMsg(result.message);
                }
            })
        }
    });

    function clearForm() {
        _userId.val("");     
        _name.val("");
        _email.val("");
        _password.val("");
    }

    async function fetchUsers(){
        try{
            const response = await fetch("/user/list", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch users")       
            }                    
            const data = await response.json();
            _ds = data.users;
            _dtUser.DataTable().destroy();
            _dtUser.DataTable({
                "data": data.users,
                "responsive": true,
                order: [[0, 'desc']],
                "columns": [
                    {
                        "render": function(data, type, row, meta) {
                            return row.id;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.name;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.email;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.role;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return '<a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info editUser"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeUser"><i class="far fa-trash-alt"></i></a>';
                        }
                    }
                ]
            });
        }catch(error){
            console.log(error);
        }
    }
</script>        
@stop