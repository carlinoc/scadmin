<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddClient">    
        @csrf
        <input type="hidden" id="clientId" name="clientId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">Nuevo Cliente</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Tipo de cliente</label>
                                <select class="form-control" name="clientType" id="clientType">
                                    <option value="1">Persona Natural</option>
                                    <option value="2">Persona Juridica</option>
                                </select>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del Cliente">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label id="ldni">DNI</label>
                                <input type="text" class="form-control" id="ruc" name="ruc" placeholder="RUC">
                                <input type="text" class="form-control" id="dni" name="dni" placeholder="Documento de Indentidad">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Telefono</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Teléfono del Cliente">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Ingresar dirección">                    
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Correo</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Correo del Cliente">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Descuento %</label>
                                <select class="form-control" name="discount" id="discount">
                                    <option value="0">0</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                            </div>            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
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