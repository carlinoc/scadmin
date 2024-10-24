@extends('adminlte::page')

@section('title', 'Mesas')

@section('content_header')
    <h1>Mantenimiento de Mesas</h1>
@stop

@section('content')
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="/tables/create" class="btn btn-primary">Crear Mesa</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body tableborder">
                <x-adminlte-datatable id="dtTables" :heads="$heads" striped head-theme="dark">
                    @foreach($tables as $table)
                        <tr>
                            <td>{{ $table->id }}</td>
                            <td>{{ $table->place }}</td>
                            <td>{{ $table->name }}</td>
                            <td>{{ $table->ability }}</td>
                            <td>
                                @if ($table->active==1)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif    
                            </td>
                            <td>
                                <a href="/tables/{{$table->id}}/edit" class="btn btn-info"><i class="far fa-edit"></i></a>

                                <form action="{{route('tables.destroy', $table)}}" method="post" class="d-inline">
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
    @if (Session::get('success'))
        showSuccessMsg("{{Session::get('success')}}");
    @endif
    @if (Session::get('error'))
        showErrorMsg("{{Session::get('error')}}");
    @endif
</script>
@stop