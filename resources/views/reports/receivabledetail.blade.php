@extends('adminlte::page')

@section('title', 'Ventas por cobrar - Detalle')

@section('content_header')
    <h1>{{ $clientName }}</h1>
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-danger">
            <span class="text-success">Venta: {{ $saleId }}</span>
            <span class="ml-2">Fecha: {{ $saleDate }}</span>
        </h5>
    </div>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
    <div class="row justify-content-center">
        <div class="col-2">
            <div class="info-box bg-gradient-success">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Total Sin Descuento</span>
                    <span id="lSubtotal" class="info-box-number text-center">s/ {{ number_format($totalAmount, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="info-box bg-gradient-info">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Descuento</span>
                    <span id="lDiscount" class="info-box-number text-center">{{ $clientDiscount }} %</span>
                </div>
            </div>
        </div>
        <div class="col-2">
            <div class="info-box bg-gradient-danger">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Total con descuento</span>
                    <span id="lTotal" class="info-box-number text-center">s/ {{ number_format($totalAmount - ($totalAmount * $clientDiscount / 100), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <x-adminlte-card>
                    <table id="dtSales" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($saleDetails as $detail)
                            <tr>
                                <td>{{ $detail->id }}</td>
                                <td>{{ $detail->name }}</td>
                                <td>s/ {{ number_format($detail->price, 2) }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>s/ {{ number_format($detail->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-adminlte-card>
            </div>
        </div>
    </div>
    @endrole

    @role('Mozo')
    <p style="color: red">No tiene permisos para esta sección</p>
    @endrole
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop


@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    //let _dtSales = $("#dtSales");
    let _ds = null;

    $(document).ready(function(){


    });
</script>
@stop
