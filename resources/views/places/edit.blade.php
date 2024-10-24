@extends('adminlte::page')

@section('title', 'Editar Lugar')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Editar Lugar</h1>
        </div>
        <div class="col">
            <a href="{{route('places.index')}}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form action="{{route('places.update', $place)}}" method="POST">
                @csrf
                @method('PUT')
                    <div class="card-body">
                        <div class="for-group mt-2">
                            <label>Lugar:</label>
                            <input type="text" class="form-control" id="place" name="place" placeholder="Nombre del lugar" value="{{$place->place}}" required>
                        </div>
                        <div class="for-group mt-2">
                            <label class="col-form-label"><i class="fas fa-check"></i> Descripción:</label>
                            <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripcion">{{$place->description}}</textarea>
                        </div>
                    </div>    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div> 
    </div>
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
@stop