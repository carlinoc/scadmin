@extends('adminlte::page')

@section('title', 'Yape')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Yape - Plin</h1>
        </div>
    </div>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form action="#" method="POST" id="frmListYape">
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
                                <div class="col">
                                    <button id="showReport" type="submit" class="btn btn-primary">Ver Detalle</button>
                                </div>
                            </div>
                            <div id="rowDates" class="row mt-2" style="display:none;">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="startDate" name="startDate"
                                            placeholder="Fecha Inicio">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="endDate" name="endDate"
                                            placeholder="Fecha Fin">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <div class="info-box bg-gradient-success">
                    <div class="info-box-content">
                        <span class="info-box-text text-center">Ingresos</span>
                        <span id="lIncome" class="info-box-number text-center">s/ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="info-box bg-gradient-danger">
                    <div class="info-box-content">
                        <span class="info-box-text text-center">Gastos</span>
                        <span id="lExpense" class="info-box-number text-center">s/ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="info-box bg-gradient-info">
                    <div class="info-box-content">
                        <span class="info-box-text text-center">Diferencia</span>
                        <span id="lTotal" class="info-box-number text-center">s/ 0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Ingresos</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="dtPosIncome" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width:80px">Id</th>
                                    <th style="width:100px">Fecha</th>
                                    <th style="width:100px">Monto S/</th>
                                    <th style="width:80px">Opc.</th>
                                </tr>
                            </thead>
                            <tbody id="tbIncome">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Gastos</h3>
                        <div class="card-tools">
                            <a href="#" id="newExpense" class="btn btn-tool btn-sm bg-danger">
                                <i class="fas fa-plus"></i> Nuevo
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="dtPosExpense" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width:80px">Id</th>
                                    <th style="width:100px">Fecha</th>
                                    <th style="width:100px">Categoria</th>
                                    <th style="width:120px">Descripción</th>
                                    <th style="width:100px">Monto S/</th>
                                    <th style="width:80px">Opc.</th>
                                </tr>
                            </thead>
                            <tbody id="tbExpense">
                            </tbody>
                        </table>
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
    <link rel="stylesheet" href="/vendor/admin/main.css">
    <style>
        div.dataTables_wrapper {
            width: 100%;
        }

        .info-box {
            min-height: 60px !important;
            padding: .2rem !important;
        }

        .info-box-text {
            padding: 0px !important;
            margin: 0px !important;
            line-height: 18px !important;
        }

        .info-box-number {
            margin-top: 0px !important;
        }

        .info-box-number2 {
            margin-top: 0px !important;
            display: block !important;
            font-weight: 500 !important;
        }

        .col-vercent {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
@stop

@section('js')
    <script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/vendor/admin/main.js"></script>
    <script>
        let _token = document.head.querySelector("[name~=csrf-token][content]").content;    
    </script>
@stop
