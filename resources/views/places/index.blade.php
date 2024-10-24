@extends('adminlte::page')

@section('title', 'Lugares')

@section('content_header')
    <h1>Mantenimiento de lugares</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="/places/create" class="btn btn-primary">Crear Lugar</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body tableborder">
                <x-adminlte-datatable id="dtPlaces" :heads="$heads" striped head-theme="dark">
                    @foreach($places as $place)
                        <tr>
                            <td>{{ $place->id }}</td>
                            <td>{{ $place->place }}</td>
                            <td>{{ $place->description }}</td>
                            <td>
                                <a href="/places/{{$place->id}}/edit" class="btn btn-info"><i class="far fa-edit"></i></a>

                                <form action="{{route('places.destroy', $place)}}" method="post" class="d-inline">
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