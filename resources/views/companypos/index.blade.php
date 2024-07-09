@extends('adminlte::page')

@section('title', 'Mantenimiento de POS')

@section('content_header')
    <h1>Mantenimiento de POS</h1>
@stop

@section('content')
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newPOS" class="btn btn-primary">Crear Nuevo POS</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtPos" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>POS</th>
                            <th>Comisión %</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Ingresos S/</th>
                            <th>Gastos S/</th>
                            <th>Saldo S/</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('companypos.add-modal')
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _companyPosId = $("#companyPosId");
    let _pos = $("#pos");
    let _commission = $("#commission");
    let _contactName = $("#contactName");
    let _contactPhone = $("#contactPhone");
    let _description = $("#description");
    
    let _dtPos = $("#dtPos");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _ds=null;

    $(document).ready(function() {

        fetchCompanyPos();
        
        $('#newPOS').on('click', function(e) {
            e.preventDefault();
            clearForm();
            _modalLabel.text("Nuevo POS");
            _modal.modal('show');
            
            setTimeout(function(){
                _pos.focus();
            }, 300);
        });

        $('#addContactPos').on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['pos', 'Ingrese el nombre del POS']
            ];

            if(emptyfy(elements)) {
                let companyPosId = _companyPosId.val();
                
                let route = "{{ route('companypos.add') }}";
                if(companyPosId!="") {
                    route = "{{ route('companypos.edit') }}";
                }

                let data = getFormParams('frmAddCompanyPos');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modal.modal('hide');
                        showSuccessMsg(result.message);
                        fetchCompanyPos();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _dtPos.on('click', '.editCompanyPos', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _companyPosId.val(id);
                _pos.val(pos);
                _commission.val(commission);
                _contactName.val(contactName);
                _contactPhone.val(contactPhone);
                _description.val(description);
            }
            
            _modalLabel.text("Editar POS");
            _modal.modal('show');
        });

        _dtPos.on('click', '.removeCompanyPos', function (e) {
            e.preventDefault();
            let companyPosId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el POS ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/companypos/remove/" + companyPosId, {
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
                            fetchCompanyPos();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });
    
    async function fetchCompanyPos() {
        const response = await fetch("/companypos/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch company pos");       
        }                    
        const data = await response.json();
        _ds = data.list;
        _dtPos.DataTable().destroy();
        _dtPos.DataTable({
            "data": data.list,
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
                        return row.pos;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.commission;
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
                        return 0.00;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return 0.00;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return 0.00;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info editCompanyPos"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger removeCompanyPos"><i class="far fa-trash-alt"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _companyPosId.val('');
        _pos.val('');
        _commission.val('');
        _contactName.val('');
        _contactPhone.val('');
        _description.val('');
    }
</script>        
@stop