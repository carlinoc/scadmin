@extends('adminlte::page')

@section('title', 'Mantenimiento de Proveedores')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Ingresos de Caja - ID: {{$payBox->id}}</h1>    
        </div>
        <div class="col">
            <a href="/paybox" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>    
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <p class="h6 text-success">Fecha de Apertura: <b>{{ $payBox->startDate }}</b></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="h6 text-danger mr-2" id="tableName">Total de ingresos: <b>{{ $payBox->income }}</b></p>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" id="newIncome" class="btn btn-primary">Agregar nuevo ingreso</a>            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtIncome" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Fecha</th>
                            <th>Nro. Documento</th>
                            <th>Tipo Documento</th>
                            <th>Importe</th>
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
</script>        
@stop