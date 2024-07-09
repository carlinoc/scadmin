<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddCompanyPos">    
        @csrf
        <input type="hidden" id="companyPosId" name="companyPosId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">Nuevo POS</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>POS</label>
                                <input type="text" class="form-control" id="pos" name="pos" placeholder="Nombre del POS">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Comisión %</label>
                                <input type="text" class="form-control" id="commission" name="commission" placeholder="Comisión del POS">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nombre del contacto</label>
                                <input type="text" class="form-control" id="contactName" name="contactName" placeholder="Nombre del contacto">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Telefono</label>
                                <input type="text" class="form-control" id="contactPhone" name="contactPhone" placeholder="Teléfono del contacto">
                            </div>            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addContactPos" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>