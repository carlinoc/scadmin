@extends('adminlte::page')

@section('title', 'Gráfico de Ingresos y Gastos')

@section('content_header')
    <h1>Gráfico de Ingresos y Gastos</h1>
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
                                <select class="form-control" name="movetype" id="movetype">
                                    <option value="0">Ver Ingresos y Gastos</option>
                                    <option value="1">Solo Ingresos</option>
                                    <option value="2">Solo Gastos</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select class="form-control" name="incometype" id="incometype">
                                    <option value="0"> - Todos - </option>
                                    <option value="1">Tipo de Pago</option>
                                    <option value="2">Usuario / Maitre</option>
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
                            <div class="col-auto" id="expenseTypeDiv" style="display: none">
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
                                        <option value="0"> - Todo el Personal -</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                                <div id="providerdiv" style="display: none" >
                                    <x-adminlte-select2 name="providerId" id="providerId" style="width: 220px!important">
                                        <option value="0"> - Todos los Proveedores -</option>
                                        @foreach($providers as $provider)
                                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                                <div id="servicediv" style="display: none">
                                    <x-adminlte-select2 name="serviceId" id="serviceId" style="width: 220px!important">
                                        <option value="0"> - Todos los Servicios -</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->service }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>
                                <div id="otherpaydiv" style="display: none">
                                    <x-adminlte-select2 name="otherpayId" id="otherpayId" style="width: 220px!important">
                                        <option value="0"> - Todos Otros Gastos -</option>
                                        @foreach($otherpays as $otherpay)
                                            <option value="{{ $otherpay->id }}">{{ $otherpay->motive }}</option>
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>    
                            </div>
                            <div class="col">
                                <button id="showReport" type="submit" class="btn btn-primary">Ver Gráfico</button>
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

                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <span>Total Gastos</span>
                                        <h4 id="hTotalExpenses">S/ 0.00</h4>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fw fas fa-chart-bar"></i>
                                    </div>
                                    {{-- <a href="/report/expensechart" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a> --}}
                                    <div class="small-box-footer p-1">
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4" id="chart2Div">
                            <div class="col-10">
                                <h5 class="text-info text-center">Ingresos</h5>
                                <canvas id="salesChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 396px;"></canvas>    
                            </div>
                            <div class="col-2">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <span>Total Efectivo:</span>
                                        <h4 id="hCash">S/ 0:00</h4>
                                        <span>Total Tarjeta:</span>
                                        <h4 id="hCard">S/ 0:00</h4>
                                        <span>Total Yape:</span>
                                        <h4 id="hYape">S/ 0:00</h4>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fw fas fa-chart-bar"></i>
                                    </div>
                                    <div class="small-box-footer p-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4" id="chart3Div" style="display: none">
                            <div class="col-10">
                                <h5 class="text-info text-center">Top Gastos</h5>
                                <table id="dtExpenses" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">Nro</th>
                                            <th style="width: 80px;">Fecha</th>
                                            <th style="width: 100px;">Gasto</th>
                                            <th style="width: 120px;">Descripcion</th>
                                            <th style="width: 80px;">S/ Total</th>
                                        </tr>
                                    </thead>
                                </table>
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
    const ctx2 = document.getElementById('salesChart2');
    let _dtExpenses = $("#dtExpenses");
    
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
        $("#incometype").hide();      

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
        });

        $('#movetype').on('change', function(e) {
            e.preventDefault();
            $("#expenseType").val(0).change();
            $("#withCash").val(4).change();
            $("#incometype").val(0).change();
            let id = this.value;
            switch(id){
                case "1":
                    $("#expenseTypeDiv").hide();
                    $("#incometype").show();
                    break;
                case "2":
                    $("#incometype").hide();      
                    $("#expenseTypeDiv").show();    
                    break;    
                default:
                    $("#incometype").hide();      
                    $("#expenseTypeDiv").hide();
            }
        });

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
            $("#chart2Div").hide();
            $("#chart3Div").hide();
            if($("#movetype").val()==2){
                $("#chart3Div").show();
            }else{
                $("#chart2Div").show();
            }

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
                    if($("#movetype").val() == 2){
                        fetchSales3();
                    }
                }    
            }else{
                fetchSales();
                fetchSales2();
                if($("#movetype").val() == 2){
                    fetchSales3();
                }
            }
        });
    });

    let chartLabel=[];
    let chartData=[];

    let chartLabel3=[];
    let chartData3=[];

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
                    chartLabel3[i] = dt.date;
                    chartData3[i] = 0.00;
                    totalIncome += parseFloat(dt.total);
                }
                $("#hTotal").html("S/ " + formatMoney(totalIncome));
                                
                let totalExpense = 0.00;
                if(data.list.length > 0){
                    for (let i = 0; i < data.list2.length; i++) {
                        let dt = data.list2[i];
                        let index = findDate(dt.date);
                        if(index>-1){
                            chartData3[index] = dt.total;
                        }
                        totalExpense += parseFloat(dt.total);
                    }
                    $("#hTotalExpenses").html("S/ " + formatMoney(totalExpense));
                }else{
                    for (let i = 0; i < data.list2.length; i++) {
                        let dt = data.list2[i];
                        chartLabel3[i] = dt.date;
                        chartData3[i] = dt.total;
                        totalExpense += parseFloat(dt.total);
                    }
                    $("#hTotalExpenses").html("S/ " + formatMoney(totalExpense));  
                }
                                                                
                let chartStatus = Chart.getChart("salesChart");
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }

                const labels = (data.list.length > 0? chartLabel: chartLabel3);
                const datalist = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: chartData,
                            borderColor: CHART_COLORS.blue,
                            backgroundColor: CHART_COLORS.blue,
                        },
                        {
                            label: 'Gastos',
                            data: chartData3,
                            borderColor: CHART_COLORS.red,
                            backgroundColor: CHART_COLORS.red,
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
                                                                
                let chartLabel=[];
                let chartData=[];
                
                let totalCash = 0.00;
                for (let i = 0; i < data.list1.length; i++) {
                    let dt = data.list1[i];
                    chartLabel[i] = dt.date;
                    chartData[i] = dt.total;
                    totalCash += parseFloat(dt.total);
                }
                $("#hCash").html("S/ " + formatMoney(totalCash));

                let chartLabel2=[];
                let chartData2=[];

                let totalCard = 0.00;
                for (let i = 0; i < data.list2.length; i++) {
                    let dt = data.list2[i];
                    chartLabel2[i] = dt.date;
                    chartData2[i] = dt.total;
                    totalCard += parseFloat(dt.total);
                }
                $("#hCard").html("S/ " + formatMoney(totalCard));

                let chartLabel3=[];
                let chartData3=[];

                let totalYape = 0.00;
                for (let i = 0; i < data.list3.length; i++) {
                    let dt = data.list3[i];
                    chartLabel3[i] = dt.date;
                    chartData3[i] = dt.total;
                    totalYape += parseFloat(dt.total);
                }
                $("#hYape").html("S/ " + formatMoney(totalYape));
                
                let chartStatus = Chart.getChart("salesChart2");
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }

                const labels = chartLabel;
                const datalist = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Efectivo',
                            data: chartData,
                            borderColor: CHART_COLORS.blue,
                            backgroundColor: CHART_COLORS.blue,
                        },
                        {
                            label: 'Tarjeta',
                            data: chartData2,
                            borderColor: CHART_COLORS.red,
                            backgroundColor: CHART_COLORS.red,
                        },
                        {
                            label: 'Yape-Plin',
                            data: chartData3,
                            borderColor: CHART_COLORS.purple,
                            backgroundColor: CHART_COLORS.purple,
                        }    
                    ]
                };

                new Chart(ctx2, {
                    type: 'bar',
                    data: datalist
                });

                
            }
        });
    }

    async function fetchSales3(){
        let route = "{{ route('report.salesreport3') }}";        
        let dt = getFormParams('frmChartList');

        fetch(route, {
            method: 'post',
            body: dt,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
                //const data = result;
                _dtExpenses.DataTable().destroy();    
                _dtExpenses.DataTable({
                    "data": result.list,
                    "responsive": true,
                    "columns": [
                        {
                            "render": function(data, type, row, meta) {
                                return (meta.row + 1);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.date;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                let concept = "";   
                                if(row.expenseType == 1){
                                    concept = " <small class='badge badge-light'>" + row.providerName + "</small>";
                                }
                                if(row.expenseType == 2){
                                    concept = " <small class='badge badge-light'>" + row.serviceName + "</small>";
                                }
                                if(row.expenseType == 3){
                                    concept = " <small class='badge badge-light'>" + row.staffName + "</small>";
                                }
                                if(row.expenseType == 4){
                                    concept = " <small class='badge badge-light'>" + row.otherPayName + "</small>";
                                }
                                return getExpenseType(row.expenseType) + concept;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.description;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return  formatMoney(row.total);
                            }
                        }
                    ]
                });
            }
        });
    }

    function findDate(cdate) {
        for (let i = 0; i < chartLabel.length; i++) {
            if (chartLabel[i] == cdate) {
                return i;
            }    
        }
        return -1;
    }

    function clearSelects() {
        $("#providerId").val(0).change();
        $("#serviceId").val(0).change();
        $("#staffId").val(0).change();
        $("#otherPayId").val(0).change();
    }
</script>    
@stop    