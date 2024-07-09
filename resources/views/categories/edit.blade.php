@extends('adminlte::page')

@section('title', 'Editar categoría')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Editar Categoría</h1>
        </div>
        <div class="col">
            <a href="{{route('categories.index')}}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form action="{{route('categories.update', $category)}}" method="POST">
                @csrf
                @method('PUT')
                    <div class="card-body">
                        <div class="for-group mt-2">
                            <label>Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$category->Name}}" placeholder="Nombre de la categoría" required>
                        </div>
                        <div class="for-group mt-2">
                            <label class="col-form-label"><i class="fas fa-check"></i> Descripción:</label>
                            <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripcion">{{$category->Description}}</textarea>
                        </div>
                    </div>    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div> 
    </div>
@stop

@section('css')