@extends('adminlte::page')

@section('title', 'Productos')

@section('content_header')
    <h1>Mantenimiento de Productos</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="/products/create" class="btn btn-primary">Crear Producto</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body tableborder">
                <x-adminlte-datatable id="dtProducts" :heads="$heads" striped head-theme="dark">
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category }}</td>
                            <td>s/ {{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <a href="/products/{{$product->id}}/edit" class="btn btn-info"><i class="far fa-edit"></i></a>

                                <form action="{{route('products.destroy', $product)}}" method="post" class="d-inline">
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
    @if (Session::get('success'))
        showSuccessMsg("{{Session::get('success')}}");
    @endif
    @if (Session::get('error'))
        showErrorMsg("{{Session::get('error')}}");
    @endif
</script>
@stop