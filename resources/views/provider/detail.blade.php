@extends('adminlte::page')

@section('title', 'Detalle del Proveedor')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Detalle de Proveedor: {{ $provider->name }}</h1>
        </div>
        <div class="col">
            <a href="{{ route('provider.index') }}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form action="#" method="POST" id="frmListPayments">
                        @csrf
                        <input type="hidden" name="providerId" id="providerId" value="{{ $provider->id }}">
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
                                    <button id="showReport" type="submit" class="btn btn-primary">Ver Pagos</button>
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
                        <span class="info-box-text text-center">Pagos</span>
                        <span id="lExpense" class="info-box-number text-center">s/ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-2">
            </div>
            <div class="col-2">
            </div>
            <div class="col">
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                <x-adminlte-card>
                    <div class="card-body">
                        <table id="dtPayments" class="row-border" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Fecha</th>
                                    <th>Monto S/</th>
                                    <th>Tipo Doc.</th>
                                    <th>Numero Doc.</th>
                                    <th>Descripción</th>
                                    <th>Caja</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </x-adminlte-card>    
            </div>
            <div class="col-4">
                
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
        let _dtPayments = $("#dtPayments");
        let _lExpense = $("#lExpense");

        $(function() {
            $("#startDate").datepicker({
                "dateFormat": "yy-mm-dd"
            });
            $("#endDate").datepicker({
                "dateFormat": "yy-mm-dd"
            });
        });

        $(document).ready(function () {

            fetchPayments();

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
                        fetchPayments();
                    }    
                }else{
                    fetchPayments();
                }
            });

        });

        async function fetchPayments() {
            let route = "{{ route('provider.listpayments') }}";        
            let data = getFormParams('frmListPayments');

            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success") {
                    _dtPayments.DataTable().destroy();
                    _dtPayments.DataTable({
                        "data": result.list,
                        "responsive": true,
                        order: [[1, 'desc']],
                        "columns": [
                            {
                                "render": function(data, type, row, meta) {
                                    return row.id;
                                }
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return getOnlytHour(row.expenseDate);
                                }
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return row.expense;
                                }
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return getVoucherType(row.voucherType);
                                }
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return row.voucherNumber;
                                }
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    return row.description;
                                }
                            },
                            {
                                "render": function(data, type, row, meta) {
                                    if(row.pos != null){
                                        return getBoxType(row.boxType) + '<small class="badge badge-secondary">' + row.pos + '</small>';   
                                    }else{
                                        return getBoxType(row.boxType);
                                    }
                                }
                            }
                        ]
                    });       
                    _lExpense.html('S/ ' + result.totalExpense);
                }
            });  
        }
    </script>
@stop
