@extends('adminlte::page')

@section('title', 'Pedido')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Pedido:</h1>
        </div>
        <div class="col">
            <a href="{{ route('report.lastorders') }}" class="btn btn-outline-dark" role="button">Atras</a>
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
                            <p class="h5 text-success">Numero: <b>{{ $sale->saleId }}</b></p>
                        </div>
                        <div class="col-sm-4">
                            <label class="h5 text-info mr-2" id="tableName">Mesa: <b>{{ $sale->table }}</b></label>
                        </div>
                        <div class="col-sm-4">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 10px"></th>
                                <th>Productos</th>
                            </tr>
                        </thead>
                        <tbody>                           
                            @php
                                $j = 0;
                                foreach ($salesDetails as $saleDetail) {
                                    $quantity = $saleDetail->quantity;
                                    $product = $saleDetail->product;
                                    $id = $saleDetail->id;
                                    for($i = 0; $i < $quantity; $i++){
                                        $j++;    
                                        echo('<tr>
                                            <td>'.$j.'.</td>
                                            <td><input type="checkbox" id="" name="" value="'.$id.'"></td>
                                            <td>'.$product.'</td>
                                        </tr>');
                                    }
                                }                                
                            @endphp
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-auto">
            <div class="row">
                <input type="hidden" id="saleId" name="saleId" value="{{ $sale->saleId }}">
                <a class="btn btn-app bg-info" id="newSplit" style="font-weight: bold;">
                    <i class="fas fa-plus"></i> CREAR
                </a>
            </div>
            <div class="row">
                <a class="btn btn-app bg-success" style="font-weight: bold;">
                    <i class="fas fa-chevron-right"></i> MOVER
                </a>
            </div>
        </div>
        <div class="col-md-4" id="mainlist">
            
        </div>    
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
    <script src="/vendor/admin/main.js"></script>
    <script>
        const _token = document.head.querySelector("[name~=csrf-token][content]").content;

        let _newSplit = $("#newSplit");
        let _saleId = $("#saleId").val();
        let _mainList = $("#mainlist");

        $(document).ready(function(){

            _newSplit.on('click', function(e){
                e.preventDefault();

                fetch("/split/add/" + _saleId, {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": _token
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        console.log(result.splitId);
                        _mainList.html('<div>@include('reports.detailsplit')</div>');
                        _mainList.load("/split/detail/" + result.splitId);
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                });

            });

        });
    </script>
@stop
