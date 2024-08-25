@extends('adminlte::page')

@section('title', 'Nueva Mesa')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Nuevo Mesa</h1>
        </div>
        <div class="col">
            <a href="{{route('tables.index')}}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form action="{{route('tables.store')}}" method="POST">
                @csrf
                    <div class="card-body">
                        <div class="for-group">
                            <label>Lugar:</label>
                            <x-adminlte-select2 name="placeId" label-class="text-lightblue" data-placeholder="Seleccione" required>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-gradient-info">
                                        <i class="fas fa-location-arrow"></i>
                                    </div>
                                </x-slot>
                                <option value=""></option>
                                @foreach($places as $place)
                                    <option value="{{$place->id}}">{{$place->place}}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="for-group mt-2">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre de la mesa" required>
                        </div>
                        <div class="for-group mt-2">
                            <label for="model">Capacidad:</label>
                            <select class="form-control" name="ability" id="ability" required>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="active" name="active">
                                    <label class="custom-control-label" for="active">Activo</label>
                                </div>    
                            </div>
                            <div class="col-sm">
                            </div>
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