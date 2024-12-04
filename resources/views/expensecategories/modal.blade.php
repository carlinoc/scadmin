<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmExpenseCategory">    
        @csrf
        <input type="hidden" id="expenseCategoryId" name="expenseCategoryId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">Nueva Categoria</h4>
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
                            <x-adminlte-select2 id="parentId" name="parentId" label-class="text-lightblue" data-placeholder="Seleccione una Categoría" style="width:200px">
                                <option value=""></option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category}}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>    
                        <div class="col-sm">
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label> Categoría</label>
                        <input type="text" class="form-control" id="category" name="category" placeholder="Ingrese la categoría">
                    </div>
                    <div class="row" id="dExpenseType">
                        <div class="col-sm">
                            <label> Grupo</label>
                            <select class="form-control" name="expenseType" id="expenseType">
                                <option value="1">Pago a Proveedores</option>
                                <option value="2">Pago de Servicios</option>
                                <option value="3">Pago a Personal</option>
                                <option value="4">Otros Pagos</option>
                            </select>    
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addCategory" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>