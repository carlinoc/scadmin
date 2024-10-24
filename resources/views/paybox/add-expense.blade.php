<!-- Modal -->
<div class="modal fade" id="expenseModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agregar Gastos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                        href="#custom-tabs-four-home" role="tab"
                                        aria-controls="custom-tabs-four-home" aria-selected="True">PROVEEDOR</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-four-profile" role="tab"
                                        aria-controls="custom-tabs-four-profile" aria-selected="false">PERSONAL</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-service-tab" data-toggle="pill"
                                        href="#custom-tabs-four-service" role="tab"
                                        aria-controls="custom-tabs-four-service" aria-selected="false">SERVICIOS</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-home-tab">
                                    <form action="" method="POST" id="frmAddExpenseProvider">
                                    @csrf
                                        <input type="hidden" name="payboxId" id="payboxId1">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Proveedor</label>
                                                    <x-adminlte-select2 id="providerId" name="providerId" label-class="text-lightblue" data-placeholder="Seleccione un proveedor" style="width:200px">
                                                        <option value=""></option>
                                                        @foreach($providers as $provider)
                                                            <option value="{{$provider->id}}">{{$provider->name}}</option>
                                                        @endforeach
                                                    </x-adminlte-select2>
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Importe S/</label>
                                                    <input type="text" class="form-control" id="amount1" name="amount" placeholder="0.00">
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Nro. de documento</label>
                                                    <input type="text" class="form-control" id="voucherNumber1" name="voucherNumber">
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Tipo de documento</label>
                                                    <select class="form-control" name="voucherType" id="voucherType1">
                                                        <option value=""> - Seleccione - </option>
                                                        <option value="0">Sin Documento</option>
                                                        <option value="1">Boleta</option>
                                                        <option value="2">Factura</option>
                                                        <option value="3">Nota de Pedido</option>
                                                    </select>
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                                            <textarea class="form-control" rows="3" id="description1" name="description" placeholder="Breve descripción"></textarea>
                                        </div>
                                        <div class="form-group text-center">
                                            <button id="addExpenseProvider" type="button" class="btn btn-primary">Agregar Gasto</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>    
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-profile-tab">
                                    <form action="" method="POST" id="frmAddExpenseStaff">
                                        @csrf
                                        <input type="hidden" name="payboxId" id="payboxId2">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Personal</label>
                                                    <x-adminlte-select2 id="staffId" name="staffId" label-class="text-lightblue" data-placeholder="Seleccione un personal" style="width:200px">
                                                        <option value=""></option>
                                                        @foreach($staffs as $staff)
                                                            <option value="{{$staff->id}}">{{$staff->name}}</option>
                                                        @endforeach
                                                    </x-adminlte-select2>
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Concepto</label>
                                                    <select class="form-control" name="concept" id="concept">
                                                        <option value=""> - Seleccione - </option>
                                                        <option value="1">Adelanto de Sueldo</option>
                                                        <option value="2">Sueldo</option>
                                                        <option value="3">Horas Extras</option>
                                                    </select>
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Importe S/</label>
                                                    <input type="text" class="form-control" id="amount2" name="amount" placeholder="0.00">
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                                            <textarea class="form-control" rows="3" id="description2" name="description" placeholder="Breve descripción"></textarea>
                                        </div>
                                        <div class="form-group text-center">
                                            <button id="addExpenseStaff" type="button" class="btn btn-primary">Agregar Gasto</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-service" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-profile-tab">
                                    <form action="" method="POST" id="frmAddExpenseService">
                                        @csrf
                                        <input type="hidden" name="payboxId" id="payboxId3">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Servicio</label>
                                                    <x-adminlte-select2 id="serviceId" name="serviceId" label-class="text-lightblue" data-placeholder="Seleccione un Servicio" style="width:200px">
                                                        <option value=""></option>
                                                        @foreach($services as $service)
                                                            <option value="{{$service->id}}">{{$service->service}}</option>
                                                        @endforeach
                                                    </x-adminlte-select2>
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Importe S/</label>
                                                    <input type="text" class="form-control" id="amount3" name="amount" placeholder="0.00">
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Nro. de documento</label>
                                                    <input type="text" class="form-control" id="voucherNumber3" name="voucherNumber">
                                                </div>            
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Tipo de documento</label>
                                                    <select class="form-control" name="voucherType" id="voucherType3">
                                                        <option value=""> - Seleccione - </option>
                                                        <option value="0">Sin Documento</option>
                                                        <option value="1">Boleta</option>
                                                        <option value="2">Factura</option>
                                                        <option value="3">Nota de Pedido</option>
                                                    </select>
                                                </div>            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                                            <textarea class="form-control" rows="3" id="description3" name="description" placeholder="Breve descripción"></textarea>
                                        </div>
                                        <div class="form-group text-center">
                                            <button id="addExpenseService" type="button" class="btn btn-primary">Agregar Gasto</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>