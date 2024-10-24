<!-- Modal -->
<div class="modal fade" id="modalIncome" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddPosIncome">    
        @csrf
        <input type="hidden" id="posincomeId" name="posincomeId">
        <input type="hidden" id="companyPosId" name="companyPosId" value="{{$companyPos->id}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title" id="modalIncomeTitle">Nuevo Depósito</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Fecha</label>
                                <div class="input-group date">
                                    <input type="text" data-date-format="dd-mm-yyyy" id="incomeDate" name="incomeDate" class="form-control datetimepicker-input"/>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>  
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Depósito S/</label>
                                <input type="text" class="form-control" id="income" name="income" placeholder="0.00">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nro de operación</label>
                                <input type="text" class="form-control" id="operationNumber" name="operationNumber">
                            </div>            
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addPosIncome" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>