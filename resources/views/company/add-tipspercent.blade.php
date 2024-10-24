<!-- Modal -->
<div class="modal fade" id="tipsPercentModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddTipsPercent">    
        @csrf
        <input type="hidden" name="tipsPercentId" id="tipsPercentId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titlemodal">Agregar Porcentaje</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Puesto o cargo</label>
                                <input type="text" class="form-control" id="employ" name="employ">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Porcentaje %</label>
                                <input type="text" class="form-control" id="percent" name="percent">
                            </div>                        
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addTipsPercent" type="button" class="btn btn-primary">Agregar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>