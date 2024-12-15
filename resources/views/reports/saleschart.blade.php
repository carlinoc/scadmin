@extends('adminlte::page')

@section('title', 'Reporte de Ingresos')

@section('content_header')
    <h1>Reporte de Ingresos</h1>
@stop

@section('content')
    @role(['Admin'])
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="#" method="POST" id="frmChartList">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <select class="form-control" name="dateRange" id="dateRange" required>
                                    <option value="this_week">Esta Semana</option>
                                    <option value="last_week">La Semana Pasada</option>
                                    <option value="this_month">Este Mes</option>
                                    <option value="last_month">El Mes Pasado</option>
                                    <option value="custom">Seleccionar Fechas</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select class="form-control" name="incometype" id="incometype">
                                    <option value="0"> - Todos los Ingresos - </option>
                                    <option value="1">Por tipo de Pago</option>
                                    <option value="2">Por Usuario - Maitre</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <div id="usersdiv" style="display: none">
                                    <x-adminlte-select2 name="usersId" id="usersId" style="width: 220px!important">
                                        <option value="0"> - Todos los Usuario -</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                            </div>
                            <div class="col-auto" id="withCashDiv" style="display: none">
                                <select class="form-control" name="withCash" id="withCash">
                                    <option value="4">Todos los pagos</option>
                                    <option value="0">En Efectivo</option>
                                    <option value="1">Con Tarjeta</option>
                                    <option value="2">Yape - Plin</option>
                                </select>
                                <select class="form-control mt-2" id="companyPosId" name="companyPosId" style="display: none">
                                    <option value="0"> - Todos -</option>
                                    @foreach($companyPosList as $companyPos)
                                        <option value="{{ $companyPos->id }}">{{ $companyPos->pos }}</option>
                                    @endforeach
                                </select>
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
                        <div class="row mt-2">
                            <div class="col-10">
                                <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 396px;"></canvas>    
                            </div>
                            <div class="col-2">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <span>Total Ingresos</span>
                                        <h4 id="hTotal">S/ 0:00</h4>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fw fas fa-chart-bar"></i>
                                    </div>
                                    <div class="small-box-footer p-1">
                                    </div>
                                </div>
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <span>Total Efectivo:</span>
                                        <h5 id="hCash">S/ 0:00</h5>
                                        <span>Total Tarjeta:</span>
                                        <h5 id="hCard">S/ 0:00</h5>
                                        <span>Total Yape:</span>
                                        <h5 id="hYape">S/ 0:00</h5>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fw fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>        
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
    const ctx = document.getElementById('salesChart');
        
    $(function() {
        $("#startDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#endDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });

    $(document).ready(function(){
        let dateRange = localStorage.getItem("salechart_daterange");
        if(dateRange == null) dateRange = "today";
        $("#dateRange").val(dateRange).change();
        
        fetchSales();
        fetchSales2();
                
        $("#startDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $("#endDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $('#incometype').on('change', function(e) {
            e.preventDefault();
            $("#usersId").val(0).change();
            $("#withCash").val(4).change();
            switch(this.value){
                case "1":
                    $("#withCashDiv").show();
                    $("#usersdiv").hide();
                    break;
                case "2":
                    $("#withCashDiv").hide();
                    $("#usersdiv").show();
                    break;
                default:
                    $("#withCashDiv").hide();
                    $("#usersdiv").hide();
            }
        })

        $('#dateRange').on('change', function(e) {
            e.preventDefault();
            var range = this.value;
            localStorage.setItem("salechart_daterange", range);
            if(range=="custom"){
                $("#rowDates").show();    
            }else{
                $("#rowDates").hide();    
            }
        });

        $('#withCash').on('change', function(e) {
            e.preventDefault();
            let id = $(this).val();
            if(id==1){
                $('#companyPosId').show()
            }else{
                $('#companyPosId').hide()
            }
        });

        $('#showReport').on('click', function(e) {
            e.preventDefault();
            
            chartLabel=[];
            chartData=[];
            chartLabel3=[];
            chartData3=[];

            var range = $("#dateRange").val();
            if(range=="custom"){
                var start_date = $("#startDate").val();
                var end_date = $("#endDate").val();
                if (start_date == "" || end_date == "") {
                    showWarningMsg("Las fechas son requeridas");
                } else {
                    fetchSales();
                    fetchSales2();
                }    
            }else{
                fetchSales();
                fetchSales2();
            }
        });
    });

    let chartLabel=[];
    let chartData=[];

    async function fetchSales(){
        let route = "{{ route('report.salesreport') }}";        
        let dt = getFormParams('frmChartList');

        fetch(route, {
            method: 'post',
            body: dt,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
                const data = result;
                
                let totalIncome = 0.00;
                for (let i = 0; i < data.list.length; i++) {
                    let dt = data.list[i];
                    chartLabel[i] = dt.date;
                    chartData[i] = dt.total;
                    totalIncome += parseFloat(dt.total);
                }
                $("#hTotal").html("S/ " + formatMoney(totalIncome));
                                                                
                let chartStatus = Chart.getChart("salesChart");
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }
                
                const datalist = {
                    labels: chartLabel,
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: chartData,
                            borderColor: CHART_COLORS.blue,
                            backgroundColor: CHART_COLORS.blue,
                        }    
                    ]
                };                

                new Chart(ctx, {
                    type: 'bar',
                    data: datalist
                });
            }
        });
    }

    async function fetchSales2(){
        let route = "{{ route('report.salesreport2') }}";        
        let dt = getFormParams('frmChartList');

        fetch(route, {
            method: 'post',
            body: dt,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
                const data = result;     
                console.log(data);
                                                           
                
                $("#hCash").html("S/ 0.00");
                let totalCash = 0.00;
                for (let i = 0; i < data.list1.length; i++) {
                    let dt = data.list1[i];
                    totalCash += parseFloat(dt.total);
                }
                $("#hCash").html("S/ " + formatMoney(totalCash));
                
                $("#hCard").html("S/ 0.00");
                let totalCard = 0.00;
                for (let i = 0; i < data.list2.length; i++) {
                    let dt = data.list2[i];
                    totalCard += parseFloat(dt.total);
                }
                $("#hCard").html("S/ " + formatMoney(totalCard));
                
                $("#hYape").html("S/ 0.00");
                let totalYape = 0.00;
                for (let i = 0; i < data.list3.length; i++) {
                    let dt = data.list3[i];
                    totalYape += parseFloat(dt.total);
                }
                $("#hYape").html("S/ " + formatMoney(totalYape));
            }
        });
    }
</script>    
@stop    