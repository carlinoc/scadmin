@extends('adminlte::page')

@section('title', 'Detalle de Gastos')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Detalle de Gastos</h1>
        </div>
        <div class="col">
            <a href="/report/saleschart" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    @role(['Admin'])
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <form action="#" method="POST" id="frmExpense1">
                    @csrf
                    <input type="hidden" name="total" value="0">
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
                                <select class="form-control" name="expenseType" id="expenseType">
                                    <option value="0">Todos los Gastos</option>
                                    <option value="1">Solo Pago a Proveedores</option>
                                    <option value="2">Solo Pago de Servicios</option>
                                    <option value="3">Solo Pago a Personal</option>
                                    <option value="4">Otros Gastos</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <div id="staffdiv" style="display: none">
                                    <x-adminlte-select2 name="staffId" id="staffId" style="width: 220px!important">
                                        <option value="0"> - Todos -</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                                <div id="providerdiv" style="display: none" >
                                    <x-adminlte-select2 name="providerId" id="providerId" style="width: 220px!important">
                                        <option value="0"> - Todos -</option>
                                        @foreach($providers as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                                <div id="servicediv" style="display: none">
                                    <x-adminlte-select2 name="serviceId" id="serviceId" style="width: 220px!important">
                                        <option value="0"> - Todos -</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->service }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                                <div id="otherpaydiv" style="display: none">
                                    <x-adminlte-select2 name="otherpayId" id="otherpayId" style="width: 220px!important">
                                        <option value="0"> - Todos -</option>
                                        @foreach($otherpays as $otherpay)
                                            <option value="{{ $otherpay->id }}">{{ $otherpay->motive }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>    
                            </div>
                            <div class="col-auto">
                                <button id="viewReport" type="button" class="btn btn-danger">Ver Gastos</button>
                            </div>
                        </div>
                    </div>    
                </form>        
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-chart1-tab" data-toggle="pill"
                                href="#custom-tabs-chart1-data" role="tab"
                                aria-controls="custom-tabs-chart1-data" aria-selected="True">Gráfico 1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-top-tab" data-toggle="pill"
                                href="#custom-tabs-top-data" role="tab"
                                aria-controls="custom-tabs-top-data" aria-selected="false">Top Gastos</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-chart1-data" role="tabpanel"
                            aria-labelledby="custom-tabs-four-chart1-tab">
                            <select name="reporttype" id="reporttype">
                                <option value="0">Gráfico en barras</option>
                                <option value="1">Gráfico en lineas</option>
                            </select>
                            <canvas id="chart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 396px;"></canvas>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-top-data" role="tabpanel"
                            aria-labelledby="custom-tabs-four-top-tab">
                            <canvas id="chart3" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 396px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-danger">
                <div class="inner">
                    <span>Total Gastos</span>
                    <h4 id="hTotalExpenses">S/ 0.00</h4>
                </div>
                <div class="icon">
                    <i class="fas fa-fw fas fa-chart-bar"></i>
                </div>
                <div class="small-box-footer p-1">
                </div>    
            </div>
        </div>        
    </div>
    @endrole   
    
    @role(['Mozo', 'Maitre'])
    <p style="color: red">No tiene permisos para esta sección</p>
    @endrole 
@stop

@section('css')
<link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;
    const ctx1 = document.getElementById('chart1');   
    const ctx2 = document.getElementById('chart2');   
    const ctx3 = document.getElementById('chart3');   
    
    $(document).ready(function(){
        let dateRange = localStorage.getItem("salechart_daterange");
        if(dateRange == null) dateRange = "today";      
        $("#dateRange").val(dateRange).change();

        $("#expenseType").change(function(){
            clearSelects();
            if(this.value == 0) {
                $("#staffdiv").hide();
                $("#providerdiv").hide();
                $("#servicediv").hide();
                $("#otherpaydiv").hide();
            }
            if(this.value == 1) {
                $("#staffdiv").hide();
                $("#providerdiv").show();
                $("#servicediv").hide();
                $("#otherpaydiv").hide();
            }
            if(this.value == 2) {
                $("#providerdiv").hide();
                $("#staffdiv").hide();
                $("#servicediv").show();
                $("#otherpaydiv").hide();
            }
            if(this.value == 3) {
                $("#servicediv").hide();
                $("#providerdiv").hide();
                $("#staffdiv").show();   
                $("#otherpaydiv").hide();
            }
            if(this.value == 4) {
                $("#otherpaydiv").show();
                $("#providerdiv").hide();
                $("#staffdiv").hide();
                $("#servicediv").hide();
            }
        })

        $("#viewReport").on('click', function(e) {
            e.preventDefault();
            var range = $("#dateRange").val();
            if(range=="custom"){
                var start_date = $("#startDate").val();
                var end_date = $("#endDate").val();
                if (start_date == "" || end_date == "") {
                    showWarningMsg("Las fechas son requeridas");
                } else {
                    fetchExpenses();
                }    
            }else{
                fetchExpenses();
            }
        });

        $("#reporttype").change(function(){
            let type = 'bar';
            if(this.value == 1) {
                type = 'line';
            }

            let chartStatus = Chart.getChart("chart1");
            if (chartStatus != undefined) {
                chartStatus.destroy();
            }

            new Chart(ctx1, {
                type: type,
                data: datalist
            });
        });

        fetchExpenses();
    });
    
    let labels = [];
    let datalist = {};
    async function fetchExpenses() {
        let route = "{{ route('report.expensereport') }}";        
        let dt = getFormParams('frmExpense1');

        fetch(route, {
            method: 'post',
            body: dt,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
                const data = result;

                let chartLabel=[];
                let chartData=[];
                
                let totalExpense = 0.00;
                for (let i = 0; i < data.list.length; i++) {
                    let dt = data.list[i];
                    chartLabel[i] = dt.date;
                    chartData[i] = dt.total;
                    
                    totalExpense += parseFloat(dt.total);
                }
                $("#hTotalExpenses").html("S/ " + formatMoney(totalExpense));
                                                                
                let chartStatus = Chart.getChart("chart1");
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }

                labels = chartLabel;
                datalist = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Gastos',
                            data: chartData,
                            borderColor: CHART_COLORS.red,
                            backgroundColor: CHART_COLORS.red,
                        }    
                    ]
                };                

                $("#reporttype").val(0).change();
            }
        });
    }

    function clearSelects() {
        $("#providerId").val(0).change();
        $("#serviceId").val(0).change();
        $("#staffId").val(0).change();
        $("#otherPayId").val(0).change();
    }
</script>    
@stop    