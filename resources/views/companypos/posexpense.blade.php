<!-- Modal -->
<div class="modal fade" id="modalExpense" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddPosExpense">    
        @csrf
        <input type="hidden" id="posexpenseId" name="posexpenseId">
        <input type="hidden" id="companyPosId" name="companyPosId" value="{{$companyPos->id}}">
        <input type="hidden" name="expenseType" id="expenseType">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalExpenseTitle">Nuevo Gasto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <label>Categoría</label>
                            <x-adminlte-select2 id="expensecategoryId" name="expensecategoryId" label-class="text-lightblue" data-placeholder="Seleccione una categoría" style="width:200px">
                                <option value=""></option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" data-expenseType="{{$category->expenseType}}" >{{$category->category}}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="col-sm">
                            <label>Sub Categoría</label>
                            <x-adminlte-select2 id="subCategoryId" name="subCategoryId" label-class="text-lightblue" data-placeholder="Seleccione Sub Categoría" style="width:200px">
                                <option value=""></option>
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm">
                            <div id="pProvider">
                                <label>Proveedor</label>
                                <x-adminlte-select2 id="providerId" name="providerId" label-class="text-lightblue" data-placeholder="Seleccione un proveedor" style="width:200px">
                                    <option value=""></option>
                                    @foreach($providers as $provider)
                                        <option value="{{$provider->id}}">{{$provider->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>
                            <div id="pService">
                                <label>Servicio</label>
                                <x-adminlte-select2 id="serviceId" name="serviceId" label-class="text-lightblue" data-placeholder="Seleccione un servicio" style="width:200px">
                                    <option value=""></option>
                                    @foreach($services as $service)
                                        <option value="{{$service->id}}">{{$service->service}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>
                            <div id="pStaff">
                                <label>Personal</label>
                                <x-adminlte-select2 id="staffId" name="staffId" label-class="text-lightblue" data-placeholder="Seleccione el personal" style="width:200px">
                                    <option value=""></option>
                                    @foreach($staffs as $staff)
                                        <option value="{{$staff->id}}">{{$staff->name}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>
                            <div id="pOtherPay">
                                <label>Otros Pagos</label>
                                <x-adminlte-select2 id="otherPayId" name="otherPayId" label-class="text-lightblue" data-placeholder="Seleccione el pago" style="width:200px">
                                    <option value=""></option>
                                    @foreach($otherpays as $otherpay)
                                        <option value="{{$otherpay->id}}">{{$otherpay->motive}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>
                        </div>
                        <div class="col-sm">
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Fecha</label>
                                <div class="input-group date">
                                    <input type="text" data-date-format="dd-mm-yyyy" id="expenseDate" name="expenseDate" class="form-control datetimepicker-input"/>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>  
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Gasto S/</label>
                                <input type="text" class="form-control" id="expense" name="expense" placeholder="0.00">
                            </div>            
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm">
                            <label>Tipo de documento</label>
                            <select class="form-control" name="voucherType" id="voucherType">
                                <option value="0">Sin documento</option>
                                <option value="1">Boleta</option>
                                <option value="2">Factura</option>
                            </select>
                        </div>
                        <div class="col-sm">
                            <label>Nro de documento</label>
                            <input type="text" class="form-control" id="voucherNumber" name="voucherNumber">
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description1" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addPosExpense" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>