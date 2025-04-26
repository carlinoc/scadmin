@extends('adminlte::page')

@section('title', 'Reporte de Propinas')

@section('content_header')
    <h1>Reporte de Propinas</h1>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="#" method="POST" id="frmListTips">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <select class="form-control" name="dateRange" id="dateRange" required>
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
                                </select>
                            </div>
                            <div class="col-auto">
                                <select class="form-control" name="tipsType" id="tipsType">
                                    <option value="3">Ambos</option>
                                    <option value="1">En Efectivo</option>
                                    <option value="2">Con Tarjeta</option>
                                </select>
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
        <div class="col-3">
            <div class="info-box bg-gradient-success">
                <div class="info-box-content">
                    <span class="info-box-text text-center">En Efectivo</span>
                    <span id="lCash" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="info-box bg-gradient-secondary">
                <div class="info-box-content">
                    <span class="info-box-text text-center">En Tarjeta</span>
                    <span id="lCard" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="info-box bg-gradient-info">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Total</span>
                    <span id="lTotal" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="table-responsive">
                <x-adminlte-card>
                    <table id="dtTips" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Fecha y Hora</th>
                                <th>Venta</th>
                                <th>Propina</th>
                                <th>Tipo</th>
                                <th>POS</th>
                            </tr>
                        </thead>
                    </table>
                </x-adminlte-card>    
            </div>        
        </div>
        <div class="col-4">
            <div id="cardTips" class="card card-success">
                <div class="card-header">
                    <input type="hidden" value="{{$tipsPercent}}" id="percentList">
                    <h3 class="card-title">Asignación de Propinas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mt-2">
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <h6>Total Propinas S/ <span id="totalTips2">0.00</span></h6>
                </div>
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

    let _dtTips = $("#dtTips");
    let _lCash = $("#lCash");
    let _lCard = $("#lCard");
    let _lTotal = $("#lTotal");
    let _totalTips = $("#totalTips");
    let _percentList = $("#percentList");
    let _commision = {{env('DATA_COMPANY_POS_PERCENT'), '4.00'}};
    let _total = 0.00;

    $(function() {
        $("#startDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#endDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });
    
    $(document).ready(function(){

        fetchTips();

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
                    fetchTips();
                }    
            }else{
                fetchTips();
            }
        });

    });
    
    function fetchTips() {
        let route = "{{ route('report.tipslist') }}";        
        let data = getFormParams('frmListTips');

        fetch(route, {
            method: 'post',
            body: data,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success") {
                _dtTips.DataTable().destroy();    
                _dtTips.DataTable({
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
                                return row.total;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.tips;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return getTipsType(row.tipsType);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.pos;
                            }
                        },
                    ]
                });
                
                let tCash = result.tipsCash;
                let tCard1 = result.tipsCard;
                let desc = (tCard1 * _commision) / 100;
                let tCard2 = tCard1 - desc;
                let tTips = parseFloat(tCash) + parseFloat(tCard2);

                _lTotal.html('S/ ' + tTips.toFixed(2));
                _lCash.html('S/ ' + result.tipsCash);
                _lCard.html('S/ ' + result.tipsCard + ' -:- S/' + tCard2.toFixed(2));
                
                $("#tableBody").empty();
                if(tTips > 0) {
                    let _ds = $.parseJSON('[' + _percentList.val() + ']');
                    let tPoints = 0;
                    let valuePoint = 0.0;
                    for($i = 0; $i < _ds[0].length; $i++) {
                        tPoints += _ds[0][$i].points; 
                    }
                    
                    valuePoint = (tTips / tPoints);
                                        
                    let percent = 0.0;
                    let totalTips = 0.0;
                    for($i = 0; $i < _ds[0].length; $i++) {
                        percent = (valuePoint * _ds[0][$i].points)
                        totalTips += percent;
                        addRow(_ds[0][$i].employ, percent);
                    }
                    $("#totalTips2").html(totalTips.toFixed(2));
                }        
            }
        });    
    }

    function addRow(employ, percent) {
        let table = document.getElementById("tableBody");
        let row = document.createElement("tr");
        
        let c1 = document.createElement("td");
        let c2 = document.createElement("td");
        
        c1.innerText = employ;
        c2.innerHTML = 'S/ ' + percent.toFixed(2);
                    
        row.appendChild(c1);
        row.appendChild(c2);
        
        table.appendChild(row);
    }
</script>    
@stop    