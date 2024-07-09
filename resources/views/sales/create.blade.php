@extends('adminlte::page')

@section('title', 'Nueva Venta')

@section('content_header')
    <h1>Nueva Venta</h1>
@stop

@section('content')
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="{{route('sales.index')}}" class="btn btn-secondary" role="button">Volver</a>
            </div>
        </div>

        <form action="{{route('sales.store')}}" method="POST">
        @csrf
            <div class="row">
                <x-adminlte-select2 name="categoryId" label="Categoría"
                                    data-placeholder="Seleccione" fgroup-class="col-md-6" required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-info">
                            <i class="fas fa-location-arrow"></i>
                        </div>
                    </x-slot>
                    <option value=""></option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->Name}}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>

            <div class="row">
                <x-adminlte-input name="name" label="Nombre del producto" placeholder="Ingrese el nombre"
                    fgroup-class="col-md-6" required autofocus />
            </div>

            <div class="row">
                <x-adminlte-input name="cost" label="Costo S/" placeholder="0.00"
                    fgroup-class="col-md-6" required />
            </div>

            <div class="row">
                <x-adminlte-input name="price" label="Precio S/" placeholder="0.00"
                    fgroup-class="col-md-6" required />
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <x-adminlte-button type="submit" label="Crear" theme="primary" icon="fas fa-save"/>
                </div>
            </div>
        </form>
    </div> 
@stop