@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
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
                    <h3 class="card-title">Top 5 platos más vendidos</h3>
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
                    <canvas id="foodChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 469px;"
                        width="469" height="250" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Top 5 bebidas más vendidas</h3>
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
                        <canvas id="lineChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 469px;"
                            width="469" height="250" class="chartjs-render-monitor"></canvas>
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

        $(document).ready(function () {
            fetchTopFood(); 
        });

        async function fetchTopFood() {
            const response = await fetch("/report/topfood", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch topfood");       
            }                    
            const data = await response.json();
            _ds = data.list;
              
        }
    </script>
@stop
