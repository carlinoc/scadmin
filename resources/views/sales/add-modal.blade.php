<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddProduct">    
    @csrf
        <input type="hidden" id="saleId" name="saleId" value="{{$sale->saleId}}">
        <input type="hidden" id="saleDetailId" name="saleDetailId">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addModalLabel">Agregar Producto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="for-group">
                    <x-adminlte-select2 name="productId" id="productId" label="Producto" data-placeholder="Seleccione" required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-location-arrow"></i>
                            </div>
                        </x-slot>
                        <option value=""></option>
                        @foreach($products as $product)
                            <option value="{{$product->id}}" data-price="{{$product->price}}" >{{$product->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="for-group">
                    <label for="price">Precio</label>
                    <input type="text" class="form-control" name="price" id="price" placeholder="0.00" required>
                </div>
                <div class="for-group mt-2">
                    <label for="quantity">Cantidad</label>
                    <select name="quantity" class="form-control" id="quantity">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="for-group mt-2">
                    <label for="total">Total S/</label>
                    <input type="text" class="form-control" name="total" id="total" placeholder="0.00" required>
                </div>
            </div>
            <div class="modal-footer">
                <button id="addNewProduct" type="button" class="btn btn-primary add_product">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </form>    
</div>