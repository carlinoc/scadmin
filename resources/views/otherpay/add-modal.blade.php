<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddOtherPay">    
        @csrf
        <input type="hidden" id="otherpayId" name="otherpayId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">Nuevo Proveedor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="isParent" name="isParent" checked>
                                <label class="custom-control-label" for="isParent">Es categoría principal</label>
                            </div>
                        </div>    
                        <div class="col-sm">
                        </div>
                    </div>
                    <div class="row mt-2" id="dCategories" style="display: none;">
                        <div class="col-sm">
                            <label>Categoría Principal</label>
                            <x-adminlte-select2 id="parentId1" name="parentId1" label-class="text-lightblue" data-placeholder="Seleccione una Categoría" style="width:200px">
                                <option value=""></option>
                                @foreach($categories1 as $category)
                                    <option value="{{$category->id}}">{{$category->motive}}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>    
                        <div class="col-sm">
                            <label>Sub Categoría</label>
                            <x-adminlte-select2 id="parentId2" name="parentId2" label-class="text-lightblue" data-placeholder="Seleccione Sub Categoría" style="width:200px">
                                <option value=""></option>
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label> Motivo de pago</label>
                        <input type="text" class="form-control" id="motive" name="motive" placeholder="Ingresar el motivo de pago">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addOtherPay" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>