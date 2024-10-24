@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Editar Producto</h1>
        </div>
        <div class="col">
            <a href="{{route('products.index')}}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form action="{{route('products.update', $product)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Categoria</label>
                                    <x-adminlte-select2 id="categoryId" name="categoryId" label-class="text-lightblue" data-placeholder="Seleccione una categoria">
                                        <x-slot name="prependSlot">
                                            <div class="input-group-text bg-gradient-info">
                                                <i class="fas fa-location-arrow"></i>
                                            </div>
                                        </x-slot>
                                        <option value=""></option>
                                        @foreach($categories as $category)
                                            @if ($category->id==$product->categoryId)
                                                <option selected value="{{$category->id}}">{{$category->Name}}</option>
                                            @else
                                                <option value="{{$category->id}}">{{$category->Name}}</option>
                                            @endif
                                        @endforeach
                                    </x-adminlte-select2>
                                </div>            
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="mac">Código</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{$product->code}}" placeholder="Codigo del producto">
                                </div>            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="mac">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$product->name}}" placeholder="Nombre del producto" required>
                                </div>                
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Encargado</label>
                                    <x-adminlte-select name="inCharge" required>
                                        <option value="">-- Selecciona --</option>
                                        <option value="Cocina" @selected("Cocina"==$product->inCharge)>Cocina</option>
                                        <option value="Barra" @selected("Barra"==$product->inCharge)>Barra</option>
                                        <option value="Otro" @selected("Otro"==$product->inCharge)>Otro</option>
                                    </x-adminlte-select>
                                </div>            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Costo S/</label>
                                    <input type="text" class="form-control" id="cost" name="cost" value="{{$product->cost}}" placeholder="0.00">
                                </div>            
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Precio Venta S/</label>
                                    <input type="text" class="form-control" id="price" name="price" value="{{$product->price}}" placeholder="0.00">
                                </div>            
                            </div>
                        </div>
                        <div class="for-group mt-2 text-center">
                            @php
                                $image = App\Http\Controllers\ProductController::getImage($product->image);
                            @endphp
                            <img src="/{{ $image }}" class="img-fluid border" style="width: 150px" />
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    @php
                                        $dueDate = date_create($product->dueDate);
                                        $fdueDate = date_format($dueDate, 'd-m-Y');
                                    @endphp
                                    <label>Fecha de Vencimiento</label>
                                    <div class="input-group date">
                                        <input type="text" data-date-format="dd-mm-yyyy" id="dueDate" name="dueDate" value="{{$fdueDate}}" class="form-control datetimepicker-input"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>            
                            </div>
                            <div class="col-sm">
                                <label>Imagen</label>
                                <input class="form-control" name="image" type="file" id="image">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="custom-control custom-switch pt-4">
                                    @php
                                        $useInventory = ($product->useInventory==1? "checked" : "");
                                    @endphp
                                    <input type="checkbox" class="custom-control-input" id="useInventory" name="useInventory" {{$useInventory}}>
                                    <label class="custom-control-label" for="useInventory">Inventario Habilitado</label>
                                </div>            
                            </div>
                            @php
                                $disabled = ($product->useInventory==1? "" : "disabled");
                            @endphp
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="text" class="form-control" id="stock" name="stock" value="{{$product->stock}}" {{$disabled}} placeholder="0">
                                </div>            
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label>Stock Minimo</label>
                                    <input type="text" class="form-control" id="minStock" name="minStock" value="{{$product->minStock}}" {{$disabled}} placeholder="0">
                                </div>            
                            </div>
                        </div>
                    </div>    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Guardar</button>
                    </div>
                </form>
            </div>
        </div> 
    </div>
@stop

@section('css')
<link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/vendor/admin/main.js"></script>
<script>
    $(function() {
        $("#dueDate").datepicker({});
    });

    $(document).ready(function() {
        $("#dueDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $('#useInventory').change(function() {
            if(this.checked) {
                $("#stock").prop('disabled', false);
                $("#minStock").prop('disabled', false);
            }else{
                $("#stock").prop('disabled', true);
                $("#minStock").prop('disabled', true);
            }
        });
    });

    @if (Session::get('success'))
        showSuccessMsg("{{Session::get('success')}}");
    @endif
    @if (Session::get('error'))
        showErrorMsg("{{Session::get('error')}}");
    @endif
</script>
@stop