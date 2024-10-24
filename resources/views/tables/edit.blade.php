@extends('adminlte::page')

@section('title', 'Editar Mesa')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Editar Mesa</h1>
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
                <form action="{{route('tables.update', $table)}}" method="POST">
                @csrf
                @method('PUT')
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
                                    @if ($place->id==$table->placeId)
                                        <option selected value="{{$place->id}}">{{$place->place}}</option>
                                    @else
                                        <option value="{{$place->id}}">{{$place->place}}</option>
                                    @endif
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="for-group mt-2">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$table->name}}" placeholder="Nombre de la mesa" required>
                        </div>
                        <div class="for-group mt-2">
                            <label for="model">Capacidad:</label>
                            <select class="form-control" name="ability" id="ability" required>
                                @for ($i = 1; $i <= 10; $i++)
                                    @if ($table->ability==$i)
                                        <option selected value="{{ $i }}">{{ $i }}</option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif    
                                @endfor
                            </select>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm">
                                <div class="custom-control custom-switch">
                                    @if ($table->active==1)
                                        <input type="checkbox" class="custom-control-input" id="active" name="active" checked>
                                    @else
                                        <input type="checkbox" class="custom-control-input" id="active" name="active">
                                    @endif        
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