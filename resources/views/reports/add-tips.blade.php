<!-- Modal -->
<div class="modal fade" id="addModalTips" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddTips">    
    @csrf
        <input type="hidden" id="saleId" name="saleId" value="{{$sale->saleId}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabelClient">Agregar Propina</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Propina S/</label>
                                <input type="text" class="form-control" id="tips" name="tips" placeholder="0.00">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control" name="tipsType" id="tipsType">
                                    <option value="1">Efectivo</option>
                                    <option value="2">Tarjeta</option>
                                </select>
                            </div>            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addTips" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>