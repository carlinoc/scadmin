<!-- Modal -->
<div class="modal fade" id="addModalClient" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddClient">    
    @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabelClient">Nuevo Cliente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del Cliente">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" placeholder="Documento de Indentidad">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Teléfono del Cliente">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Descuento %</label>
                                <select class="form-control" name="level" id="level">
                                    <option value="0">0</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                            </div>            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addClient" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>