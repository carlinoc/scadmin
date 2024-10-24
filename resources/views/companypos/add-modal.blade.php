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
                                <input type="text" class="form-control" id="commission" name="commission" placeholder="0">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Titular:</label>
                                <x-adminlte-select2 name="staffId" id="staffId" label-class="text-lightblue" data-placeholder="Seleccione" required>
                                    <option value=""></option>
                                    @foreach($list as $staff)
                                        <option value="{{$staff->id}}">{{$staff->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>RUC</label>
                                <input type="text" class="form-control" id="ruc" name="ruc">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Banco</label>
                                <select class="form-control" name="bank" id="bank">
                                    <option value="BCP">BCP</option>
                                    <option value="Interbank">Interbank</option>
                                    <option value="BBVA">BBVA</option>
                                    <option value="ScotiaBank">ScotiaBank</option>
                                </select>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nro. de Cuenta</label>
                                <input type="text" class="form-control" id="accountNumber" name="accountNumber">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nombre del contacto</label>
                                <input type="text" class="form-control" id="contactName" name="contactName">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Telefono</label>
                                <input type="text" class="form-control" id="contactPhone" name="contactPhone">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mainPos" name="mainPos">
                                    <label for="mainPos" class="custom-control-label">POS Principal</label>
                                </div>
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
                    <button id="addContactPos" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>