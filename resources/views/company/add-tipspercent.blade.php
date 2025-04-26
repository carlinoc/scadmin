<!-- Modal -->
<div class="modal fade" id="tipsPercentModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddTipsPercent">    
        @csrf
        <input type="hidden" name="tipsPercentId" id="tipsPercentId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titlemodal">Agregar Porcentaje</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label> Area</label>
                                <select class="form-control" name="area" id="area">
                                    <option value="0">Salón</option>
                                    <option value="1">Producción</option>
                                    <option value="2">Otros</option>
                                </select>    
                            </div>                        
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Puesto o cargo</label>
                                <input type="text" class="form-control" id="employ" name="employ">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label> Puntos</label>
                                <select class="form-control" name="points" id="points">
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor  
                                </select>
                            </div>                        
                        </div>
                        <div class="col-sm">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addTipsPercent" type="button" class="btn btn-primary">Agregar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>