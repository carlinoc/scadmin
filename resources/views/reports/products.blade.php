@extends('adminlte::page')

@section('title', 'Reporte de produtos') 

@section('content_header')
    <h1>Reporte de produtos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="#" method="POST" id="frmListProducts">
                    @csrf
                    <input type="hidden" name="total" value="0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <x-adminlte-select2 name="productId" id="productId" data-placeholder="Seleccione" required>
                                    <option value=""></option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>    
                            <div class="col-sm">
                                <x-adminlte-select name="dateRange" id="dateRange" required>
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
                                </x-adminlte-select>
                            </div>
                            <div class="col-sm">
                                <button id="viewReport" type="button" class="btn btn-primary">Ver Reporte</button>
                            </div>
                        </div>
                        <div id="rowDates" class="row">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="start_date" placeholder="Fecha Inicio" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="end_date" placeholder="Fecha Fin" readonly>
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>        
            </div>
        </div> 
    </div> 

    <div>
        <x-adminlte-card>
            <div class="card-body tableborder">
                <table id="dtSales" class="row-border table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nro</th>
                            <th>Fecha y Hora</th>
                            <th>Lugar</th>
                            <th>Mesa</th>
                            <th>Sub. Total</th>
                            <th>Descuento</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _dtSales = $("#dtSales");    
    let _userId = $("#userId");

    $(document).ready(function(){
        _userId.val({{ Auth::user()->id }}).change();

        fetchReport();

        $('#viewReport').on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['userId', 'Seleccione el usuario']
            ];

            if(emptyfy(elements)) {
                fetchReport();    
            }
        });  
    });

    function fetchReport(){
        let route = "{{ route('report.lastorderslist') }}";        
        let data = getFormParams('frmLastOrders');

        fetch(route, {
            method: 'post',
            body: data,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success"){
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
                                return row.dateUpdate;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.place;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.table;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.subtotal;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return `${row.discount}%`;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.total;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return (row.withCash==0?'<small class="badge badge-success">Efectivo</small>':'<small class="badge badge-secondary">Tarjeta</small>');
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return '<a href="/report/detail/' + row.id + '" class="btn btn-sm btn-info"><i class="far fa-eye"></i></a> <a href="/report/split/' + row.id + '" class="btn btn-sm btn-warning"><i class="far fa-edit"></i></a>';
                            }
                        }
                    ]
                });
            }
            if(result.status=="error"){
                
            }
        });
    }
</script>
@stop