<!-- Modal -->
<div class="modal fade" id="incomeModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddIncome">    
        @csrf
        <input type="hidden" name="payboxId" id="payboxId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Ingreso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nro. de documento</label>
                                <input type="text" class="form-control" id="voucherNumber" name="voucherNumber">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <select class="form-control" name="voucherType" id="voucherType">
                                    <option value=""> - Seleccione - </option>
                                    <option value="0">Sin Documento</option>
                                    <option value="1">Boleta</option>
                                    <option value="2">Factura</option>
                                    <option value="3">Nota de Pedido</option>
                                </select>
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Concepto</label>
                                <x-adminlte-select2 id="incomeconceptId" name="incomeconceptId" label-class="text-lightblue" data-placeholder="Seleccione un concepto">
                                    <option value=""></option>
                                    @foreach($incomeConcepts as $incomeConcept)
                                        <option value="{{$incomeConcept->id}}">{{$incomeConcept->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Importe S/</label>
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="0.00">
                            </div>            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addIncome" type="button" class="btn btn-primary">Agregar Ingreso</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>