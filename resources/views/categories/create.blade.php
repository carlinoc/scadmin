@extends('adminlte::page')

@section('title', 'Nueva categoría')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Nueva Categoría</h1>
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
                <form action="{{route('categories.store')}}" method="POST">
                @csrf
                    <div class="card-body">
                        <div class="for-group mt-2">
                            <label>Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre de la categoría" required>
                        </div>
                        <div class="for-group mt-2">
                            <label class="col-form-label"><i class="fas fa-check"></i> Descripción:</label>
                            <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripcion"></textarea>
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