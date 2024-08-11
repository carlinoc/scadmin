<!-- Modal -->
<div class="modal fade" id="addModal" aria-labelledby="addModalLabel" aria-hidden="true">
    <form action="" method="POST" id="frmAddStaff">    
        @csrf
        <input type="hidden" id="staffId" name="staffId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addModalLabel">Nuevo Personal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del personal">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingresar DNI">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nro. Telefono 1</label>
                                <input type="text" class="form-control" id="phone1" name="phone1" placeholder="Número de celular">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Nro. Telefono 2</label>
                                <input type="text" class="form-control" id="phone2" name="phone2" placeholder="Número de celular o teléfono">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Ingresar dirección">
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Correo Electrónico">
                            </div>            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-group">
                                <label>Area de trabajo</label>
                                <x-adminlte-select2 name="areaId" id="areaId" label-class="text-lightblue" data-placeholder="Seleccione un area" required>
                                    <option value=""></option>
                                    @foreach($areas as $area)
                                        <option value="{{$area->id}}">{{$area->area}}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>            
                        </div>
                        <div class="col-sm">
                            <div class="form-group">
                                
                            </div>            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                        <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="addStaff" type="button" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </form>    
</div>