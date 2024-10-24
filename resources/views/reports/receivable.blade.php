@extends('adminlte::page')

@section('title', 'Ventas por cobrar')

@section('content_header')
    <h1>Ventas por cobrar</h1>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="#" method="POST" id="frmListOrders">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <select class="form-control" name="dateRange" id="dateRange" required>
                                    <option value="today">Solo Hoy</option>
                                    <option value="yesterday">Solo Ayer</option>
                                    <option value="this_week">Esta Semana</option>
                                    <option value="last_week">La Semana Pasada</option>
                                    <option value="this_month">Este Mes</option>
                                    <option value="last_month">El Mes Pasado</option>
                                    <option value="this_year">Este año</option>
                                    <option value="custom">Seleccionar Fechas</option>
                                </select>
                            </div>
                            <div class="col">
                                <x-adminlte-select2 id="clientId" name="clientId" label-class="text-lightblue" data-placeholder="Cliente">
                                    <option value="0"> - Todos - </option>
                                    @foreach($list as $client)
                                        <option value="{{$client->id}}" >{{$client->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>    
                            <div class="col">
                                <button id="showReport" type="submit" class="btn btn-primary">Ver Reporte</button>
                            </div>
                        </div>
                        <div id="rowDates" class="row mt-2" style="display:none;">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="endDate" name="endDate" placeholder="Fecha Fin">
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>        
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <x-adminlte-card>
                    <table id="dtSales" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Nro</th>
                                <th>Fecha y Hora</th>
                                <th>Cliente</th>
                                <th>Mesa</th>
                                <th>SubTotal</th>
                                <th style="width: 100px;">Desc.</th>
                                <th>Total</th>
                                <th>Pago</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </x-adminlte-card>    
            </div>        
        </div>
    </div>

    @include('reports.add-pay')
    @endrole   
    
    @role('Mozo')
    <p style="color: red">No tiene permisos para esta sección</p>
    @endrole 
@stop

@section('css')
<link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="/vendor/admin/main.css">
<style>
    
</style>
@stop


@section('js')
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _dtSales = $("#dtSales");
    let _ds = null;

    let _addModalPay = $("#addModalPay");
    let _saleId = $("#saleId");
    let _withCash = $("#withCash");
    let _companyPosId = $("#companyPosId");
    let _amount = $("#amount");
    let _lPOS = $("#lPOS");
    let _sClient = $("#sClient");
    let _sAmount = $("#sAmount");   
        
    $(function() {
        $("#startDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#endDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });

    function fetchReport() {
        let route = "{{ route('report.receivablelist') }}";        
        let data = getFormParams('frmListOrders');

        fetch(route, {
            method: 'post',
            body: data,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
                _ds = result.sales;
                _dtSales.DataTable().destroy();    
                _dtSales.DataTable({
                    "data": result.sales,
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
                                return row.client;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.createdDate;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.table;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.subtotal;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.discount > 0) {
                                    return '<span class="text-danger">' + row.discount + '%</span>';
                                }else{
                                    return `${row.discount}%`;
                                }
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.total;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return getPayType(row.withCash);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return '<a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-success item_pay"><i class="fas fa-dollar-sign"></i></a>';
                            }
                        }
                    ]
                });
            }
        });
    }    

    $(document).ready(function(){

        _amount.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");

        fetchReport();

        $("#addPay").on('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: "Atención",
                text: "Deseas agregar el pago, este proceso se agregara a la caja actual",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
            
                    let elements = [
                        ['amount', 'Ingrese el monto a pagar']
                    ];

                    if(emptyfy(elements)) {
                        let route = "{{ route('report.receivableadd') }}";
                        
                        let data = getFormParams('frmAddPay');
                        fetch(route, {
                            method: 'post',
                            body: data,
                        })
                        .then(response => response.json())
                        .then(result => {
                            if(result.status=="success"){
                                _addModalPay.modal('hide');
                                showSuccessMsg(result.message);
                                fetchReport(); 
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        })
                    }
                    
                }
            });
        })

        _withCash.on('change', function(e) {
            e.preventDefault();
            let id = $(this).val();
            if(id==1){
                _lPOS.show();
                _companyPosId.show();
            }else{
                _lPOS.hide();
                _companyPosId.hide();
            }
            setTimeout(function(){
                _amount.focus();
            }, 300);
        });
        
        $("#startDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $("#endDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $('#dateRange').on('change', function(e) {
            e.preventDefault();
            var range = this.value;
            if(range=="custom"){
                $("#rowDates").show();    
            }else{
                $("#rowDates").hide();    
            }
        });
        
        $('#showReport').on('click', function(e) {
            e.preventDefault();
            var range = $("#dateRange").val();
            if(range=="custom"){
                var start_date = $("#startDate").val();
                var end_date = $("#endDate").val();
                if (start_date == "" || end_date == "") {
                    alert("Las fechas son requeridas");
                } else {
                    $('#dtsales').DataTable().destroy();
                    fetchReport();
                }    
            }else{
                $('#dtsales').DataTable().destroy();
                fetchReport();
            }
        });

        $(document).on('click', '.item_pay', function(e){
            e.preventDefault();
            let index=$(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _saleId.val(id);
                _sClient.html(client);
                _sAmount.html(total);
            }
            _withCash.val(0).change();
            _addModalPay.modal('show');
        });
    });
</script>    
@stop    