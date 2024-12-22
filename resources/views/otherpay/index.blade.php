@extends('adminlte::page')

@section('title', 'Mantenimiento de Otros de Pagos')

@section('content_header')
    <h1>Mantenimiento de Otros de Pagos</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newOtherPay" class="btn btn-primary">Nuevo Motivo de Pago</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtOtherPay" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Motivo</th>
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('otherpay.add-modal')
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

    let _otherpayId = $("#otherpayId");
    let _motive = $("#motive");
    let _description = $("#description");
    let _addOtherPay = $("#addOtherPay");
    
    let _dtOtherPay = $("#dtOtherPay");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    $(document).ready(function() {

        fetchOtherPay();

        $('#newOtherPay').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nuevo Motivo de Pago");
            _modal.modal('show');
            
            setTimeout(function(){
                _motive.focus();
            }, 300);
        });

        $('#addOtherPay').on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['motive', 'Ingrese el motivo de pago']
            ];

            if(emptyfy(elements)) {
                let otherpayId = _otherpayId.val();
                
                let route = "{{ route('otherpay.add') }}";
                if(otherpayId!="") {
                    route = "{{ route('otherpay.edit') }}";
                }

                let data = getFormParams('frmAddOtherPay');
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
                        fetchOtherPay();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _dtOtherPay.on('click', '.editProvider', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _otherpayId.val(id);
                _motive.val(motive);
                
                _description.val(description);
            }
            
            _modalLabel.text("Editar Motivo de Pago");
            _modal.modal('show');
        });

        _dtOtherPay.on('click', '.removeProvider', function (e) {
            e.preventDefault();
            let otherpayId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el motivo de pago?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/otherpay/remove/" + otherpayId, {
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
                            fetchOtherPay();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });
    
    async function fetchOtherPay() {
        const response = await fetch("/otherpay/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch otherpay");       
        }                    
        const data = await response.json();
        _ds = data.list;
        _dtOtherPay.DataTable().destroy();
        _dtOtherPay.DataTable({
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
                        return row.motive;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.description;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="/otherpay/detail/'+row.id+'/" class="btn btn-sm btn-warning"><i class="far fa-eye"></i></a> <a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info editProvider"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeProvider"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _otherpayId.val('');
        _motive.val("");

        _description.val("");
    }
</script>        
@stop