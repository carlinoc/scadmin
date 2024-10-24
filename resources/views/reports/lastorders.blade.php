@extends('adminlte::page')

@section('title', 'Ultimas Mesas Atendidas') 

@section('content_header')
    <h1>Ultimas Mesas Atendidas</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="#" method="POST" id="frmLastOrders">
                    @csrf
                    <input type="hidden" name="total" value="0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <x-adminlte-select2 name="userId" id="userId" data-placeholder="Seleccione" required>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text bg-gradient-info">
                                            <i class="fas fa-location-arrow"></i>
                                        </div>
                                    </x-slot>
                                    <option value="0"> - Todos los mozos -</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
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
                                </x-adminlte-select>
                            </div>
                            <div class="col-sm">
                                <button id="viewReport" type="button" class="btn btn-primary">Ver Reporte</button>
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
                            <th>Mesa</th>
                            <th>Sub. Total</th>
                            <th>Descuento</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Atendido</th>
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
        _userId.val(0).change();

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
                                return "<small>" +  row.createdDate + "</small>";
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.splitNumber > 0) {
                                    return "<span class='text-primary'>" +  row.table + "-" + row.splitNumber + "</span>";
                                }else{
                                    return "<span class='text-success'>" +  row.table + "</span>";
                                }
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.subtotal;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.discount > 0) {
                                    return "<span class='text-danger'>-" + row.discount + "%</span>";
                                }else{
                                    return `${row.discount}%`;
                                }
                                
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.total;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return getPayType(row.withCash);
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.userName;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return '<a href="/report/detail/' + row.id + '" class="btn btn-sm btn-info"><i class="far fa-eye"></i></a>';
                                //return '<a href="/report/detail/' + row.id + '" class="btn btn-sm btn-info"><i class="far fa-eye"></i></a> <a href="/report/split/' + row.id + '" class="btn btn-sm btn-warning"><i class="far fa-edit"></i></a>';
                            }
                        }
                    ]
                });
            }
        });
    }
</script>
@stop