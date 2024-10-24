@extends('adminlte::page')

@section('title', 'Mantenimiento de Servicios')

@section('content_header')
    <h1>Mantenimiento de Servicios</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newService" class="btn btn-primary">Crear Nuevo Servicio</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtService" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Servicio</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('service.add-modal')
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
    
    let _serviceId = $("#serviceId");
    let _service = $("#service");
    let _description = $("#description");
    let _addOtherPay = $("#addOtherPay");
    
    let _dtService = $("#dtService");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    $(document).ready(function() {

        fetchService();

        $('#newService').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nuevo Servicio");
            _modal.modal('show');
            
            setTimeout(function(){
                _service.focus();
            }, 300);
        });

        $('#addService').on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['service', 'Ingrese el servicio']
            ];

            if(emptyfy(elements)) {
                let serviceId = _serviceId.val();
                
                let route = "{{ route('service.add') }}";
                if(serviceId!="") {
                    route = "{{ route('service.edit') }}";
                }

                let data = getFormParams('frmAddService');
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
                        fetchService();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _dtService.on('click', '.editItem', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _serviceId.val(id);
                _service.val(service);
                
                _description.val(description);
            }
            
            _modalLabel.text("Editar Servicio");
            _modal.modal('show');
        });

        _dtService.on('click', '.removeItem', function (e) {
            e.preventDefault();
            let serviceId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el servicio?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/service/remove/" + serviceId, {
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
                            fetchService();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });
    
    async function fetchService() {
        const response = await fetch("/service/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch service");       
        }                    
        const data = await response.json();
        _ds = data.list;
        _dtService.DataTable().destroy();
        _dtService.DataTable({
            "data": data.list,
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
                        return row.service;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.description;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="/service/detail/'+row.id+'/" class="btn btn-sm btn-warning"><i class="far fa-eye"></i></a> <a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info editItem"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeItem"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _serviceId.val("");
        _service.val("");
        _description.val("");
    }
</script>        
@stop