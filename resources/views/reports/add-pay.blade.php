<!-- Modal -->
<div class="modal fade" id="addModalPay" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddPay">    
    @csrf
        <input type="hidden" id="saleId" name="saleId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Realizar Pago</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label style="width: 100%">Cliente:</label>
                                <span id="sClient"></span>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label style="width: 100%">Monto a pagar S/:</label>
                                <span id="sAmount"></span>
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Paga con</label>
                                <select class="form-control" id="withCash" name="withCash">
                                    <option value="0">Efectivo</option>
                                    <option value="1">Tarjeta</option>
                                    <option value="2">Yape-Plin</option>
                                </select>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label id="lPOS">POS</label>
                                <select class="form-control" id="companyPosId" name="companyPosId">
                                    @foreach($companyPosList as $companyPos)
                                        <option value="{{ $companyPos->id }}">{{ $companyPos->pos }}</option>
                                    @endforeach
                                </select>
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Monto S/</label>
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="0.00">
                            </div>            
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addPay" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>