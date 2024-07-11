@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>Reporte de Ventas</h1>
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
                                <x-adminlte-select name="dateRange" id="dateRange" required>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text bg-gradient-info">
                                            <i class="fas fa-location-arrow"></i>
                                        </div>
                                    </x-slot>
                                    <option value="today">Solo Hoy</option>
                                    <option value="yesterday">Solo Ayer</option>
                                    <option value="this_week">Esta Semana</option>
                                    <option value="last_week">La Semana Pasada</option>
                                    <option value="this_month">Este Mes</option>
                                    <option value="last_month">El Mes Pasado</option>
                                    <option value="this_year">Este año</option>
                                    <option value="custom">Seleccionar Fechas</option>
                                </x-adminlte-select>
                            </div>
                            <div class="col-auto">
                                <select class="form-control" name="withCash" id="withCash">
                                    <option value="3">Todo tipo de pago</option>
                                    <option value="0">En Efectivo</option>
                                    <option value="1">Con Tarjeta</option>
                                    <option value="2">Yape - Plin</option>
                                </select>
                            </div>    
                            <div class="col-auto">
                                <select class="form-control" name="filterpay" id="filterpay">
                                    <option value="1">Ventas Efectivas</option>
                                    <option value="2">Ventas Anuladas</option>
                                    <option value="3">Efectivas + Anuladas</option>
                                </select>
                            </div>    
                            <div class="col">
                                <x-adminlte-button class=".btn-sm" id="showReport" type="submit" label=" Ver Reporte" theme="primary" icon="fas fa-save"/>
                            </div>
                        </div>
                        <div id="rowDates" class="row" style="display:none;">
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
        <div class="col">
            <label id="lCash" class="col-form-label" style="color:#17a2b8!important;">En Efectivo: 00</label>
        </div>
        <div class="col">
            <label id="lCard" class="col-form-label" style="color:#28a745!important;">En Tarjeta: 00</label>
        </div>
        <div class="col">
            <label id="lTotal" class="col-form-label col-md-6" style="color:#dc3545!important;"></label>        
        </div>
        {{-- <div class="col">
            <blockquote>
            <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
            </blockquote>
        </div> --}}
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
                                <th>Mesa</th>
                                <th>SubTotal</th>
                                <th style="width: 100px;">Desc.</th>
                                <th>Total</th>
                                <th>Pago</th>
                                <th>POS</th>
                                <th style="width: 100px;">Comprobante</th>
                                <th style="width: 100px;">Propina</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </x-adminlte-card>    
            </div>        
        </div>
    </div>
    @endrole   
    
    @role('Mozo')
    <p style="color: red">No tiene permisos para esta sección</p>
    @endrole 
@stop

@section('css')
<link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
@stop


@section('js')
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _dtSales = $("#dtSales");    

    $(function() {
        $("#start_date").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#end_date").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });

    function fetchReport() {
        let route = "{{ route('report.saleslist') }}";        
        let data = getFormParams('frmListOrders');

        fetch(route, {
            method: 'post',
            body: data,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
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
                                return row.pos;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return getVoucherType(row.voucherType);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.tips > 0){
                                    if(row.tipsType == 1){
                                        return '<span class="text-success"><i class="fas fa-coins"></i> ' + row.tips +'</span>';
                                    }else{
                                        return '<span class="text-secondary"><i class="fas fa-coins"></i> ' + row.tips +'</span>';
                                    }
                                    
                                }else{
                                    return row.tips;
                                }
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.history_count == 0){
                                    return '<a href="/report/detail/'+row.id+'" class="btn btn-sm btn-info edit_product"><i class="fas fa-edit"></i></a>';
                                }else{
                                    return '<a href="/report/detail/'+row.id+'" class="btn btn-sm btn-info edit_product"><i class="fas fa-edit"></i></a> <a href="/report/history/'+row.id+'" class="btn btn-sm btn-success historysale"><i class="fas fa-history"></i></a>';
                                }
                                
                            }
                        }
                    ]
                });

                $('#lTotal').html('<h5>Total S/' + result.totalSales + '</h5>');
                $('#lCash').html('<h5>En Efectivo S/' + result.withCash + '</h5>');
                $('#lCard').html('<h5>En Tarjeta S/' + result.withCard + '</h5>');
            }
        });
    }    

    $(document).ready(function(){

        fetchReport();

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
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
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
    });
</script>    
@stop    
