<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddProvider">    
        @csrf
        <input type="hidden" id="providerId" name="providerId">
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
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del proveedor">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Telefono</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Teléfono del proveedor">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Contacto</label>
                                <input type="text" class="form-control" id="contactName" name="contactName" placeholder="Nombre de contacto">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Teléfono del Contacto</label>
                                <input type="text" class="form-control" id="contactPhone" name="contactPhone" placeholder="Teléfono">
                            </div>            
                        </div>
                    </div>
                    <div class="form-group">
                        <label> Dirección</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Ingresar dirección">
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Método de pago</label>
                                <select class="form-control" name="paymentMethod" id="paymentMethod">
                                    <option value=""> - Seleccione - </option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Yape">Yape</option>
                                    <option value="Plin">Plin</option>
                                    <option value="Transferencia">Transferencia Bco</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label id="titleNumber">Numero de Cta.</label>
                                <input type="text" class="form-control" id="idNumber" name="accountNumber">
                            </div>            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addProvider" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>