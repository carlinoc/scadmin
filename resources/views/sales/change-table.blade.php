<!-- Modal -->
<div class="modal fade" id="addModalTable" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmChangeTable">    
    @csrf
        <input type="hidden" id="saleId" name="saleId" value="{{$sale->saleId}}">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addModalLabelTable">Cambiar al cliente de mesa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="for-group">
                    <label>Asignar nueva mesa</label>
                    <x-adminlte-select2 id="tableId" name="tableId" label-class="text-lightblue" data-placeholder="Seleccione">
                        <option value=""></option>
                        @foreach($tables as $tables)
                            <option value="{{$tables->id}}">{{$tables->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnChangeTable" type="button" class="btn btn-primary add_product">Aceptar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
            </div>
        </div>
    </form>    
</div>