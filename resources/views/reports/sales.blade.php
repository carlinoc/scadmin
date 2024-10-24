@extends('adminlte::page')

@section('title', 'Reporte de Ventas')

@section('content_header')
    <h1>Reporte de Ventas</h1>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="#" method="POST" id="frmListOrders">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
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
                            <div class="col-auto">
                                <select class="form-control" name="withCash" id="withCash">
                                    <option value="4">Todo tipo de pago</option>
                                    <option value="0">En Efectivo</option>
                                    <option value="1">Con Tarjeta</option>
                                    <option value="2">Yape - Plin</option>
                                    <option value="3">Por Pagar</option>
                                </select>
                                <select class="form-control mt-2" id="companyPosId" name="companyPosId" style="display: none">
                                    <option value="0"> - Todos -</option>
                                    @foreach($companyPosList as $companyPos)
                                        <option value="{{ $companyPos->id }}">{{ $companyPos->pos }}</option>
                                    @endforeach
                                </select>
                            </div>    
                            <div class="col-auto">
                                <select class="form-control" name="filterpay" id="filterpay">
                                    <option value="1">Ventas Efectivas</option>
                                    <option value="2">Ventas Anuladas</option>
                                    <option value="3">Efectivas + Anuladas</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="currentPayBox" name="currentPayBox" checked>
                                    <label class="custom-control-label" for="currentPayBox">Caja Activa</label>
                                </div>
                            </div>    
                            <div class="col">
                                <button id="showReport" type="submit" class="btn btn-primary">Ver Reporte</button>
                            </div>
                        </div>
                        <div id="rowDates" class="row mt-2" style="display:none;">
                            <div class="col-2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-2">
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
            <div class="info-box bg-gradient-success">
                <div class="info-box-content">
                    <span class="info-box-text text-center">En Efectivo</span>
                    <span id="lCash" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="info-box bg-gradient-secondary">
                <div class="info-box-content">
                    <span class="info-box-text text-center">En Tarjeta</span>
                    <span id="lCard" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="info-box bg-gradient-primary">
                <div class="info-box-content">
                    <span class="info-box-text text-center">En Yape/Plin</span>
                    <span id="lYape" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="info-box bg-gradient-danger">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Total</span>
                    <span id="lTotal" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="info-box bg-light">
                <div class="info-box-content">
                    <div class="row">
                        <div class="col col-vercent">
                            <span id="sTipsTotal" class="text-muted">Tips: S/ 0.00</span>    
                        </div>
                        <div class="col">
                            <span id="sTipsCash" class="info-box-number2 text-success">Efectivo: 0.00</span>
                            <span id="sTipsCard" class="info-box-number2 text-muted">Tarjeta: 0.00</span>
                        </div>
                    </div>            
                </div>
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
                                <th style="width: 100px;">Fecha</th>
                                <th style="width: 80px;">Mesa</th>
                                <th style="width: 80px;">SubTotal</th>
                                <th style="width: 80px;">Desc.</th>
                                <th style="width: 80px;">Total</th>
                                <th style="width: 80px;">Pago</th>
                                <th style="width: 80px;">POS</th>
                                <th style="width: 100px;">Comprobante</th>
                                <th style="width: 80px;">Propina</th>
                                <th style="width: 100px;">Atendido</th>
                                <th style="width: 80px;">Sunat</th>
                                <th style="width: 80px;">Extrangero</th>
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
<style>
    .info-box{
        min-height: 60px!important;
        padding: .2rem!important;
    }
    .info-box-text{
        padding: 0px!important;
        margin: 0px!important;
        line-height: 18px!important;
    }
    .info-box-number{
        margin-top: 0px!important;
    }
    .info-box-number2{
        margin-top: 0px!important;
        display: block!important;
        font-weight: 500!important;
    }
    .col-vercent{
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
</style>
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _dtSales = $("#dtSales");
    let _sTipsCash = $("#sTipsCash");
    let _sTipsCard = $("#sTipsCard");
    let _ds = null;
    let _sTipsTotal = $("#sTipsTotal");    

    $(function() {
        $("#startDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#endDate").datepicker({
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
                                return "<small>" +  row.createdDate + "</small>";
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.splitNumber > 0) {
                                    return "<span class='text-primary'>" +  row.table + "-" + row.splitNumber + "</span>";
                                }else{
                                    return "<span class='text-success'>" +  row.table + "</span>";
                                }
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
                                return '<span class="text-info font-weight-bold">' + row.total + '</span>';
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return getPayType(row.withCash);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.pos != null){
                                    return "<small>" + row.pos + "</small>";    
                                }else{
                                    return "";
                                } 
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.voucherType > 0){
                                    return getVoucherType(row.voucherType) + " <small>" + row.voucherSerie + "-" + row.voucherNumber + "</small>";
                                }else{
                                    return '';
                                }
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
                                return row.userName;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                let ix1 = meta.row;
                                if(row.sunat==0){
                                    return '<div class="custom-control custom-checkbox"><input class="custom-control-input custom-control-input-danger itemsunat" data-index="'+ix1+'" type="checkbox" id="cb1_'+ix1+'"><label for="cb1_'+ix1+'" class="custom-control-label"></label></div>';
                                }else{
                                    return '<div class="custom-control custom-checkbox"><input class="custom-control-input custom-control-input-danger itemsunat" data-index="'+ix1+'" type="checkbox" id="cb1_'+ix1+'" checked><label for="cb1_'+ix1+'" class="custom-control-label"></label></div>';
                                }
                                
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                let ix2 = meta.row;
                                if(row.isForeign==0){
                                    return '<div class="custom-control custom-checkbox"><input class="custom-control-input itemisforeign" data-index="'+ix2+'" type="checkbox" id="cb2_'+ix2+'"><label for="cb2_'+ix2+'" class="custom-control-label"></label></div>';
                                }else{
                                    return '<div class="custom-control custom-checkbox"><input class="custom-control-input itemisforeign" data-index="'+ix2+'" type="checkbox" id="cb2_'+ix2+'" checked><label for="cb2_'+ix2+'" class="custom-control-label"></label></div>';
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

                $('#lTotal').html('S/ ' + formatMoney(result.totalSales));
                $('#lCash').html('S/ ' + formatMoney(result.withCash));
                $('#lCard').html('S/ ' + formatMoney(result.withCard));
                $('#lYape').html('S/ ' + formatMoney(result.withYape));
                
                let _cashTips = 0.0;
                let _cardTips = 0.0;
                let _totalTips = 0.0;
                for($i = 0; $i < _ds.length; $i++) {
                    _totalTips += parseFloat(_ds[$i].tips);
                    if(_ds[$i].tipsType == 1) {
                        _cashTips += parseFloat(_ds[$i].tips);
                    }
                    if(_ds[$i].tipsType == 2) {
                        _cardTips += parseFloat(_ds[$i].tips);
                    }
                }
                _sTipsCash.html('Efectivo: ' + formatMoney(parseFloat(_cashTips)));
                _sTipsCard.html('Tarjeta: ' + formatMoney(parseFloat(_cardTips)));
                _sTipsTotal.html('Tips: S/' + formatMoney(parseFloat(_totalTips)));
            }
        });
    }    

    $(document).ready(function(){

        fetchReport();

        $('#withCash').on('change', function(e) {
            e.preventDefault();
            let id = $(this).val();
            if(id==1){
                $('#companyPosId').show()
            }else{
                $('#companyPosId').hide()
            }
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

        _dtSales.on('click', '.itemsunat', function (e) {
            let isChecked = $(this).is(':checked');
            let sunat = 0;
            if(isChecked){
                sunat = 1;
            }
            
            let index = $(this).data('index');
            let saleId = _ds[index].id;
            
            fetch("/report/sunat/" + saleId + "/" + sunat, {
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
                }
                if(result.status=="error"){
                    showErrorMsg(result.message);
                }
            });      
        });

        _dtSales.on('click', '.itemisforeign', function (e) {
            let isChecked = $(this).is(':checked');
            let isforeign = 0;
            if(isChecked){
                isforeign = 1;
            }
            
            let index = $(this).data('index');
            let saleId = _ds[index].id;
            
            fetch("/report/isforeign/" + saleId + "/" + isforeign, {
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
                }
                if(result.status=="error"){
                    showErrorMsg(result.message);
                }
            });      
        });
    });
</script>    
@stop    
