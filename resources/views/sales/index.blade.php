@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1>Nuevo Pedido</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="{{route('sales.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="total" value="0">
                    <input type="hidden" name="status" value="0">
                    <input type="hidden" name="payboxId" value="{{$payboxId}}">
                    <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <x-adminlte-select2 name="tableId" data-placeholder="Seleccione Mesa" required>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text bg-gradient-info">
                                            <i class="fas fa-location-arrow"></i>
                                        </div>
                                    </x-slot>
                                    <option value=""></option>
                                    @foreach($tables as $table)
                                        <option value="{{$table->id}}">{{$table->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>    
                            <div class="col-sm">
                                <button type="submit" class="btn btn-primary">Realizar Pedido</button>
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
                <x-adminlte-datatable  id="dtSales" :heads="$heads" striped head-theme="dark">
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->place }}</td>
                            <td>{{ $sale->table }}</td>
                            <td>{{date_format($sale->updated_at,"d-m-Y g:i A")}}</td>
                            <td>
                                <a href="/sale/{{$sale->id}}" class="btn btn-info"><i class="far fa-edit"></i></a>
                                <form action="{{route('sales.destroy', $sale)}}" method="post" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
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
    let $role = "Admin";
    @role('Mozo')
        $role = "Mozo";
    @endrole

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

    $.extend($.fn.dataTable.defaults, {
        "order": [[ 1, "desc" ]]
    });
</script>
@stop