@extends('adminlte::page')

@section('title', 'Historial de Venta') 

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Historial de Venta</h1>
        </div>
        <div class="col">
            <a href="/report/sales" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div>
        <x-adminlte-card>
            <div class="card-body tableborder">
                <table id="dtHistory" class="row-border no-footer dataTable table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Fecha y Hora</th>
                            <th>Acción</th>
                            <th>Total Ant</th>
                            <th>Total Act</th>
                            <th>Desc Ant</th>
                            <th>Desc Act</th>
                            <th>Cant Ant</th>
                            <th>Cant Act</th>
                            <th>Producto</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historys as $history)
                            <tr>
                                <td>{{ $history->id }}</td>
                                <td>{{ substr($history->created_at, 0, 16) }}</td>
                                <td>{{ $history->action }}</td>
                                <td>{{ $history->lasttotal }}</td>
                                <td>{{ $history->newtotal }}</td>
                                <td>{{ $history->discount }} %</td>
                                <td>{{ $history->newdiscount }} %</td>
                                <td>{{ $history->quantity }}</td>
                                <td>{{ $history->newquantity }}</td>
                                <td>{{ $history->product }}</td>
                                <td>{{ $history->user }}</td>
                            </tr>
                        @endforeach    
                    </tbody>
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
@stop