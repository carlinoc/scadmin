@extends('adminlte::page')

@section('title', 'Panel de Control')

@section('content_header')
    <h1>Panel de Control</h1>
@stop

@section('content')
    {{-- {{ Auth::user()->name }}
    @role('Admin')
    <p>Welcome ADMIN</p>
    @endrole
    @role('Maitre')
    <p>Welcome MAITRE</p>
    @endrole
    @role('Mozo')
    <p>Welcome MOZO</p>
    @endrole --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Top platos más vendidos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-control" name="dateRange" id="dateRange" required>
                                <option value="this_week">Esta Semana</option>
                                <option value="last_week">La Semana Pasada</option>
                                <option value="this_month">Este Mes</option>
                                <option value="last_month">El Mes Pasado</option>
                                <option value="this_year">Este año</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" name="top" id="top" required>
                                <option value="7">7</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                        <div class="col">
                            <button id="showFood" type="submit" class="btn btn-sm btn-primary">Ver Gráfico</button>
                        </div>
                    </div>
                    <div class="row">
                        <canvas id="foodChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 469px;"
                        width="469" height="250" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Top bebidas más vendidas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <select class="form-control" name="dateRange2" id="dateRange2" required>
                                    <option value="this_week">Esta Semana</option>
                                    <option value="last_week">La Semana Pasada</option>
                                    <option value="this_month">Este Mes</option>
                                    <option value="last_month">El Mes Pasado</option>
                                    <option value="this_year">Este año</option>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-control" name="top2" id="top2" required>
                                    <option value="7">7</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                            <div class="col">
                                <button id="showDrink" type="submit" class="btn btn-sm btn-primary">Ver Gráfico</button>
                            </div>
                        </div>
                        <div class="row">
                            <canvas id="drinkChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 469px;"
                            width="469" height="250" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const _token = document.head.querySelector("[name~=csrf-token][content]").content;
        const ctx = document.getElementById('foodChart');
        const ctx2 = document.getElementById('drinkChart');

        $(document).ready(function () {
            fetchTopFood(); 

            fetchTopDrink();

            $('#showFood').on('click', function(e) {
                e.preventDefault();
                fetchTopFood(); 
            });

            $('#showDrink').on('click', function(e) {
                e.preventDefault();
                fetchTopDrink();
            });
        });

        async function fetchTopFood() {
            let dateRange = $("#dateRange").val();
            let top = $("#top").val();

            const response = await fetch("/report/topfood/" + dateRange + "/" + top + "/cocina", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch topfood");       
            }                    
            const data = await response.json();
            
            let chartLabel=[];
            let chartData=[];
            
            for (let i = 0; i < data.list.length; i++) {
                let dt = data.list[i];
                chartLabel[i] = dt.name;
                chartData[i] = dt.total;
            }
            
            let chartStatus = Chart.getChart("foodChart");
            if (chartStatus != undefined) {
            chartStatus.destroy();
            }

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartLabel,
                    datasets: [{
                        label: 'Platos',
                        data: chartData,
                        hoverOffset: 4
                    }]
                }
            });
        }

        async function fetchTopDrink() {
            let dateRange = $("#dateRange2").val();
            let top = $("#top2").val();

            const response = await fetch("/report/topfood/" + dateRange + "/" + top + "/barra", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch topfood");       
            }                    
            const data = await response.json();
            
            let chartLabel=[];
            let chartData=[];
            
            for (let i = 0; i < data.list.length; i++) {
                let dt = data.list[i];
                chartLabel[i] = dt.name;
                chartData[i] = dt.total;
            }
            
            let chartStatus = Chart.getChart("drinkChart");
            if (chartStatus != undefined) {
            chartStatus.destroy();
            }

            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: chartLabel,
                    datasets: [{
                        label: 'Bebidas',
                        data: chartData,
                        hoverOffset: 4
                    }]
                }
            });
        }
    </script>
@stop
