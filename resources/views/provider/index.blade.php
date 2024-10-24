@extends('adminlte::page')

@section('title', 'Mantenimiento de Proveedores')

@section('content_header')
    <h1>Mantenimiento de Proveedores</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newProvider" class="btn btn-primary">Crear Nuevo Proveedor</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtProvider" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Contacto</th>
                            <th>Contacto Telf</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('provider.add-modal')
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

    let _providerId = $("#providerId");
    let _name = $("#name");
    let _phone = $("#phone");
    let _contactName = $("#contactName");
    let _contactPhone = $("#contactPhone");
    let _paymentMethod = $("#paymentMethod");
    let _titleNumber = $("#titleNumber");
    let _idNumber = $("#idNumber");
    let _description = $("#description");
    let _address = $("#address");
    
    let _dtProvider = $("#dtProvider");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    $(document).ready(function() {

        fetchProviders();
        
        _paymentMethod.on("change", function() {
            let _payment = _paymentMethod.val();
            switch (_payment) {
                case "Efectivo":
                    _titleNumber.html('');
                    _idNumber.hide();
                    break;
                case "Yape":
                    _titleNumber.show().html('Numero de Yape');
                    _idNumber.show().attr('name', 'yapeNumber');
                    if(_phone.val()!=""){
                        _idNumber.val(_phone.val());
                    }else{
                        _idNumber.focus();
                    }
                    break;
                case "Plin":
                    _titleNumber.show().html('Numero de Plin');
                    _idNumber.show().attr('name', 'plinNumber');
                    if(_phone.val()!=""){
                        _idNumber.val(_phone.val());
                    }else{
                        _idNumber.focus();
                    }
                    break;
                case "Transferencia":
                    _titleNumber.show().html('Numero de Cuenta');
                    _idNumber.show().attr('name', 'accountNumber');
                    _idNumber.val('').focus();
                    break;        
                case "Otros":
                    _titleNumber.show().html('');
                    _idNumber.hide().attr('name', 'other');
                    _description.focus();
                    break;    
                default:
                    _titleNumber.hide();
                    _idNumber.hide();
                    break;
            }
        });

        $('#newProvider').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nuevo Proveedor");
            _modal.modal('show');
            
            setTimeout(function(){
                _name.focus();
            }, 300);
        });

        $('#addProvider').on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['name', 'Ingrese el nombre del proveedor'],
                ['phone', 'Ingrese el telefono del proveedor'],
                ['paymentMethod', 'Seleccione el metodo de pago']
            ];

            if(emptyfy(elements)) {
                let providerId = _providerId.val();
                
                let route = "{{ route('provider.add') }}";
                if(providerId!="") {
                    route = "{{ route('provider.edit') }}";
                }

                let data = getFormParams('frmAddProvider');
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
                        fetchProviders();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _dtProvider.on('click', '.editProvider', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _providerId.val(id);
                _name.val(name);
                _phone.val(phone);
                _contactName.val(contactName);
                _contactPhone.val(contactPhone);
                _address.val(address);
                _paymentMethod.val(paymentMethod).change();
                if(paymentMethod=="Yape"){
                    $('[name="Yape"]').val(yapeNumber);
                }
                if(paymentMethod=="Plin"){
                    $('[name="Plin"]').val(plinNumber);
                }
                if(paymentMethod=="Transferencia"){
                    $('[name="accountNumber"]').val(accountNumber);
                }
                _description.val(description);
            }
            
            _modalLabel.text("Editar Proveedor");
            _modal.modal('show');
        });

        _dtProvider.on('click', '.removeProvider', function (e) {
            e.preventDefault();
            let providerId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el proveedor?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/provider/remove/" + providerId, {
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
                            fetchProviders();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });
    
    async function fetchProviders() {
        const response = await fetch("/provider/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch providers");       
        }                    
        const data = await response.json();
        _ds = data.providers;
        _dtProvider.DataTable().destroy();
        _dtProvider.DataTable({
            "data": data.providers,
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
                        return row.phone;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.contactName;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.contactPhone;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="/provider/detail/'+row.id+'/" class="btn btn-sm btn-warning"><i class="far fa-eye"></i></a> <a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info editProvider"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeProvider"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _providerId.val("");
        _name.val("");
        _phone.val("");
        _contactName.val("");
        _contactPhone.val("");
        _address.val("");
        _paymentMethod.val("").change();

        _description.val("");
    }
</script>        
@stop