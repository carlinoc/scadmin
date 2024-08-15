@extends('adminlte::page')

@section('title', 'Mantenimiento de Personal')

@section('content_header')
    <h1>Mantenimiento de Personal</h1>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newStaff" class="btn btn-primary">Crear Nuevo Personal</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtStaff" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Telefono 1</th>
                            <th>Telefono 2</th>
                            <th>Area</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('staff.add-modal')
    @endrole   
    
    @role('Mozo')
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

    let _staffId = $("#staffId");
    let _name = $("#name");
    let _dni = $("#dni");
    let _phone1 = $("#phone1");
    let _phone2 = $("#phone2");
    let _address = $("#address");
    let _email = $("#email");
    let _description = $("#description");
    let _areaId = $("#areaId");
        
    let _dtStaff = $("#dtStaff");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    $(document).ready(function() {

        fetchStaff();

        $('#newStaff').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nuevo Personal");
            _modal.modal('show');
            
            setTimeout(function(){
                _name.focus();
            }, 300);
        });

        $('#addStaff').on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['name', 'Ingrese el nombre del personal'],
                ['phone1', 'Ingrese el telefono del personal'],
                ['areaId', 'Seleccione el area de trabajo']
            ];

            if(emptyfy(elements)) {
                let staffId = _staffId.val();
                
                let route = "{{ route('staff.add') }}";
                if(staffId!="") {
                    route = "{{ route('staff.edit') }}";
                }

                let data = getFormParams('frmAddStaff');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modal.modal('hide');
                        clearForm();
                        showSuccessMsg(result.message);
                        fetchStaff();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _dtStaff.on('click', '.editStaff', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _staffId.val(id);
                _name.val(name);
                _dni.val(dni);
                _phone1.val(phone1);
                _phone2.val(phone2);
                _address.val(address);
                _email.val(email);
                _description.val(description);
                _areaId.val(areaId).change();
            }
            
            _modalLabel.text("Editar Staff");
            _modal.modal('show');
        });

        _dtStaff.on('click', '.removeStaff', function (e) {
            e.preventDefault();
            let staffId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el Personal?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/staff/remove/" + staffId, {
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
                            fetchStaff();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });
    
    async function fetchStaff() {
        const response = await fetch("/staff/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch staff");       
        }                    
        const data = await response.json();
        _ds = data.staffs;
        _dtStaff.DataTable().destroy();
        _dtStaff.DataTable({
            "data": data.staffs,
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
                        return row.phone1;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.phone2;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.area;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="/staff/detail/' + row.id + '" class="btn btn-sm btn-info detailStaff"><i class="far fa-eye"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeStaff"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _staffId.val("");
        _name.val("");
        _dni.val("");
        _phone1.val("");
        _phone2.val("");
        _address.val("");
        _email.val("");
        _description.val("");
        _areaId.val("").change();
    }
</script>        
@stop