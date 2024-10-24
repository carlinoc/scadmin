@extends('adminlte::page')

@section('title', 'Mantenimiento de Clientes')

@section('content_header')
    <h1>Mantenimiento de Clientes</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])    
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newClient" class="btn btn-primary">Crear Nuevo Cliente</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtClient" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Descuento %</th>
                            <th>Tipo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('client.add-modal')
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

    let _clientId = $("#clientId");
    let _name = $("#name");
    let _phone = $("#phone");
    let _email = $("#email");
    let _dni = $("#dni");
    let _address = $("#address");
    let _description = $("#description");
    let _discount = $("#discount");
    
    let _dtClient = $("#dtClient");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    let _clientType = $("#clientType");
    let _labelDni = $("#ldni");
    let _ruc = $("#ruc");
       

    $(document).ready(function() {

        fetchClients();

        _clientType.on('change', function(e){
            e.preventDefault();
            
            let id = $(this).val();
            console.log(id);
            if(id==1){
                _labelDni.text('DNI');
                _dni.show();
                _ruc.hide();
                _name.attr("placeholder", "Nombre del cliente");
            }else{
                _dni.hide();
                _labelDni.text('RUC');
                _ruc.show();
                _name.attr("placeholder", "Nombre de la empresa");
            }
            _name.focus();    
        });

        $('#newClient').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nuevo Cliente");
            _modal.modal('show');
            
            setTimeout(function(){
                _name.focus();
            }, 300);
        });

        $('#addClient').on('click', function(e) {
            e.preventDefault();
            const elements = [];
            if(_clientType.val()==1){
                elements.push(['name', 'Ingrese el nombre del cliente']);
            }else{
                elements.push(['name', 'Ingrese el nombre de la empresa']);
                elements.push(['ruc', 'Ingrese el RUC de la empresa']);
            }

            if(emptyfy(elements)) {
                let clientId = _clientId.val();
                
                let route = "{{ route('client.add') }}";
                if(clientId!="") {
                    route = "{{ route('client.edit') }}";
                }

                let data = getFormParams('frmAddClient');
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
                        fetchClients();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _dtClient.on('click', '.editClient', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _clientId.val(id);
                _name.val(name);
                _phone.val(phone);
                _dni.val(dni);
                _email.val(email);
                _address.val(address);
                _discount.val(discount).change();
                _description.val(description);

                _clientType.val(clientType).change();
                _ruc.val(ruc);
            }
            
            _modalLabel.text("Editar Cliente");
            _modal.modal('show');
        });

        _dtClient.on('click', '.removeClient', function (e) {
            e.preventDefault();
            let clientId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el Cliente?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/clients/remove/" + clientId, {
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
                            fetchClients();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });

        _clientType.val(1).change();
    });
    
    async function fetchClients() {
        const response = await fetch("/clients/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch clients");       
        }                    
        const data = await response.json();
        _ds = data.clients;
        _dtClient.DataTable().destroy();
        _dtClient.DataTable({
            "data": data.clients,
            "responsive": true,
            order: [[1, 'asc']],
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
                        return row.phone;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.email;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.discount;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return (row.clientType==1?'<small class="badge badge-primary">Cliente</small>':'<small class="badge badge-secondary">Empresa</small>');
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="/clients/detail/' + row.id + '" class="btn btn-sm btn-warning"><i class="far fa-eye"></i></a> <a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info editClient"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeClient"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _clientId.val("");
        _name.val("");
        _phone.val("");
        _address.val("");
        _dni.val("");
        _email.val("");
        _discount.val(0).change();
        _description.val("");

        _clientType.val(1).change();
        _ruc.val("");
    }
</script>        
@stop