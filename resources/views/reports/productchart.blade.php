@extends('adminlte::page')

@section('title', 'Gráfico de Ventas')

@section('content_header')
    <h1>Gráfico de Ventas</h1>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
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
                                <x-adminlte-select2 name="productId" data-placeholder="- Todos los productos -">
                                    <option value="0">- Todos los productos -</option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>        
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
                        <div class="row">
                            <div class="col-10">
                                <canvas id="productChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 396px;"></canvas>    
                            </div>
                            <div class="col-2">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <span>Total Vendidos</span>
                                        <h4 id="hTotal">99</h4>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-fw fas fa-chart-bar"></i>
                                    </div>
                                    <div class="small-box-footer p-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 396px;"></canvas>    
                        </div>
                    </div>    
                </form>        
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;
    const ctx = document.getElementById('productChart');
    const ctx2 = document.getElementById('salesChart');

    $(function() {
        $("#startDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#endDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });

    $(document).ready(function(){

        fetchProducts();

        fetchSales();

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
                    showWarningMsg("Las fechas son requeridas");
                } else {
                    fetchProducts();

                    fetchSales();
                }    
            }else{
                fetchProducts();

                fetchSales();
            }
        });
    });

    async function fetchProducts(){
        let route = "{{ route('report.productlist') }}";        
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
                
                let total = 0;
                for (let i = 0; i < data.list.length; i++) {
                    let dt = data.list[i];
                    chartLabel[i] = dt.date;
                    chartData[i] = dt.total;
                    total += parseInt(dt.total);
                }
                $("#hTotal").html(total);
                
                let chartStatus = Chart.getChart("productChart");
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartLabel,
                        datasets: [{
                            label: 'Productos',
                            data: chartData,
                            borderColor: CHART_COLORS.blue,
                            backgroundColor: CHART_COLORS.blue,
                        }]
                    }
                });
            }
        });
    }

    async function fetchSales(){
        let route = "{{ route('report.saleschartlist') }}";        
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
                
                for (let i = 0; i < data.list.length; i++) {
                    let dt = data.list[i];
                    chartLabel[i] = dt.date;
                    chartData[i] = dt.total;
                }
                
                let chartStatus = Chart.getChart("salesChart");
                if (chartStatus != undefined) {
                chartStatus.destroy();
                }

                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: chartLabel,
                        datasets: [{
                            label: 'Ventas',
                            data: chartData,
                            borderColor: CHART_COLORS.red,
                            backgroundColor: CHART_COLORS.red,
                        }]
                    }
                });
            }
        });
    }
</script>    
@stop    