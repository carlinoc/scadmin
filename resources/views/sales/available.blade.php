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
                    <div class="row mb-3">
                        <div class="col text-center">
                            <span>
                                <i class="fas fa-square text-success"></i> Disponible
                            </span>
                            <span class="ml-3">
                                <i class="fas fa-square text-danger"></i> Ocupado
                            </span>
                            <span class="ml-3">
                                <i class="fas fa-square text-secondary"></i> Falta Limpiar
                            </span>
                        </div>
                    </div>
                    <form method="POST" action="{{route('sales.takeorder')}}" id="frmAddSale">
                        @csrf
                        <input type="hidden" name="payboxId" value="{{$payboxId}}">
                        <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="tableId" id="tableId" >
                    </form>
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

    @if (Session::get('success'))
        showSuccessMsg("{{Session::get('success')}}");
    @endif
    @if (Session::get('error'))
        showErrorMsg("{{Session::get('error')}}");
    @endif
    @if (Session::get('warning'))
        Swal.fire({
            title: "Atención",
            text: "{{Session::get('warning')}}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Aperturar Caja"
            }).then((result) => {
            if (result.isConfirmed) {
                window.location = "/paybox";
            }
        });
    @endif

    let _dtables = $("#dtables");
    
    $(document).ready(function() {
        fetchTables();

        _dtables.on("click", ".btnOrder", function(e) {
            e.preventDefault();
            let tableId = $(this).data("id");
            $("#tableId").val(tableId);
            $("#frmAddSale").submit();
        })

        _dtables.on("click", ".btnClean", function(e) {
            e.preventDefault();
            let tableId = $(this).data("id");
            Swal.fire({
                title: "Atención",
                text: "Se realizo la limpieza de la mesa?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/table/clean/" + tableId, {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-Token": _token
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            fetchTables();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
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
                    if(dr.state == 1) {
                        _dtables.append('<div><a href="#" data-id="' + dr.id + '" class="btn btn-app bg-secondary btnClean"><i class="fas fa-couch"></i><h5>' + dr.name + '</h5></a></div>');
                    }else{
                        _dtables.append('<div><a href="#" data-id="' + dr.id + '" class="btn btn-app bg-success btnOrder"><i class="fas fa-couch"></i><h5>' + dr.name + '</h5></a></div>');
                    }
                } else {
                    _dtables.append('<div><a href="#" data-id="' + dr.id + '" class="btn btn-app bg-danger btnOrder"><i class="fas fa-cocktail"></i><h5>' + dr.name + '</h5></a></div>');
                }
                
            }
        }
    }
</script>    
@stop    