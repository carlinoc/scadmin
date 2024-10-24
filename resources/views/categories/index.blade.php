@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <h1>Mantenimiento de categorías</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="/categories/create" class="btn btn-primary">Crear Categoría</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body tableborder">
                <x-adminlte-datatable id="dtCategories" :heads="$heads" striped head-theme="dark">
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->Name }}</td>
                            <td>{{ $category->Description }}</td>
                            <td>
                                <a href="/categories/{{$category->id}}/edit" class="btn btn-info"><i class="far fa-edit"></i></a>

                                <form action="{{route('categories.destroy', $category)}}" method="post" data-id="{{$category->id}}" class="d-inline">
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