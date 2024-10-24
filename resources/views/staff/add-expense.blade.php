<!-- Modal -->
<div class="modal fade" id="modalExpense" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddExpense">    
        @csrf
        <input type="hidden" id="payboxExpenseId" name="payboxExpenseId">
        <input type="hidden" id="staffId" name="staffId" value="{{$staff->id}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalExpenseTitle">Agregar Sueldo - Adelanto - Extras</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="name" value="{{$staff->name}}" disabled>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <label>Concepto</label>
                            <select class="form-control" name="staffPayType" id="staffPayType1">
                                <option value="">- Seleccione -</option>
                                <option value="1">Adelanto de Sueldo</option>
                                <option value="2">Sueldo</option>
                                <option value="3">Horas Extras</option>
                            </select>
                        </div>
                        <div class="col-sm">
                            <label>Monto S/</label>
                            <input type="text" class="form-control" id="expense" name="expense" placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description1" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addExpense" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>