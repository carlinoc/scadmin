<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddService">    
        @csrf
        <input type="hidden" id="serviceId" name="serviceId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">Nuevo Servicio</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label> Service</label>
                        <input type="text" class="form-control" id="service" name="service" placeholder="Ingresar el servicio">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addService" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>