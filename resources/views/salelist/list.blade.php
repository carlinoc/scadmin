@extends('adminlte::page')

@section('title', 'Listado de Ventas')

@section('content_header')
    <h1>Listado de Ventas</h1>
@stop

@section('content')
    <div>
        @if (Session::get('success'))
            <div>
                <div class="alert alert-warning mt-2">
                    <strong>{{Session::get('success')}}</strong>
                </div>
            </div>
        @endif
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <x-adminlte-datatable id="dtSalesList" :heads="$heads" striped head-theme="dark">
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->place }}</td>
                            <td>{{ $sale->table }}</td>
                            <td>{{date_format($sale->created_at,"d-m-Y g:i A")}}</td>
                            @if ($sale->status==1)
                            <td>Emitido</td>
                            @else
                            <td>Anulado</td>    
                            @endif
                            <td>
                                <a href="/detail/{{$sale->id}}" class="btn btn-sm btn-warning edit_product">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </div>
        </x-adminlte-card>
    </div>
@stop