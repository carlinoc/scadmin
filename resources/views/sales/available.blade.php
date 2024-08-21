@extends('adminlte::page')

@section('title', 'Mesas Disponibles')

@section('content_header')
    <h1>Mesas Disponibles</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="dtables">
                    </div>
                </div>    
            </div>
        </div> 
    </div>
@stop

@section('css')
<link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<style>
    .btn-app{
        height: 70px!important;
        min-width: 90px!important;
    }
</style>
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;
    let _dtables = $("#dtables");
    
    $(document).ready(function() {
        fetchTables();
    });

    async function fetchTables() {
        const response = await fetch("/sale/tablelist", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch tables list");       
        }                    
        let _ds = null;
        const data = await response.json();
        if(data.status=="success") {
            _ds = data.list;
            _dtables.empty();
            for($i = 0; $i < _ds.length; $i++) {
                dr = _ds[$i];
                if(dr.salesCount == 0){
                    _dtables.append('<div><a href="#" class="btn btn-app bg-success"><i class="fas fa-couch"></i><h5>' + dr.name + '</h5></a></div>');
                } else {
                    _dtables.append('<div><a href="#" class="btn btn-app bg-danger"><i class="fas fa-cocktail"></i><h5>' + dr.name + '</h5></a></div>');
                }
                
            }
        }
    }
</script>    
@stop    