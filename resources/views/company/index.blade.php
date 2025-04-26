@extends('adminlte::page')

@section('title', 'Empresa')

@section('content_header')
    <h1>Empresa</h1>
@stop

@section('content')
@role(['Admin', 'Maitre'])
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-company-tab" data-toggle="pill"
                                href="#custom-tabs-four-company" role="tab"
                                aria-controls="custom-tabs-four-company" aria-selected="True">Datos de la Empresa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-serial-tab" data-toggle="pill"
                                href="#custom-tabs-four-serial" role="tab"
                                aria-controls="custom-tabs-four-serial" aria-selected="false">Comprobantes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-tips-tab" data-toggle="pill"
                                href="#custom-tabs-four-tips" role="tab"
                                aria-controls="custom-tabs-four-tips" aria-selected="false">Propinas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-debug-tab" data-toggle="pill"
                                href="#custom-tabs-four-debug" role="tab"
                                aria-controls="custom-tabs-four-debug" aria-selected="false">Desarrollador</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-company" role="tabpanel"
                            aria-labelledby="custom-tabs-four-company-tab">
                            <form action="" method="POST" id="frmAddCompany">
                            @csrf
                                <input type="hidden" name="companyId" id="companyId" value="{{$company->id}}">
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Nombre de la empresa</label>
                                            <input type="text" class="form-control" id="company" name="company" value="{{$company->company}}" placeholder="Nombre de la empresa">
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Slogan</label>
                                            <input type="text" class="form-control" id="slogan" name="slogan" placeholder="Slogan" value="{{$company->slogan}}">
                                        </div>            
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>RUC</label>
                                            <input type="text" class="form-control" id="ruc" name="ruc" value="{{$company->ruc}}">
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>IGV %</label>
                                            <input type="text" class="form-control" id="igv" name="igv" placeholder="0" value="{{$company->igv}}">
                                        </div>            
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{$company->phone}}">
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <input type="text" class="form-control" id="address" name="address" value="{{$company->address}}">
                                        </div>            
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Página Web:</label>
                                            <input type="text" class="form-control" id="website" name="website" placeholder="http:" value="{{$company->website}}">
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción:</label>
                                    <textarea class="form-control" rows="3" id="description" name="description" placeholder="Breve descripción">{{$company->description}}</textarea>
                                </div>
                                <div class="form-group text-center">
                                    <button id="saveCompany" type="button" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>    
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-serial" role="tabpanel"
                            aria-labelledby="custom-tabs-four-serial-tab">
                            <form action="" method="POST" id="frmAddSerial">
                                @csrf
                                <input type="hidden" name="companySerialId" id="companySerialId">
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Tipo de documento</label>
                                            <select class="form-control" name="serieType" id="serieType">
                                                <option value="1">Boleta</option>
                                                <option value="2">Factura</option>
                                            </select>    
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Serie</label>
                                            <input type="text" class="form-control" id="serie" name="serie">
                                        </div>            
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="input-group input-group-sm">
                                            <label style="width: 100%">Número</label>
                                            <input type="text" class="form-control" id="number" name="number">
                                            <span class="input-group-append">
                                                <button type="button" id="verifyNumber" class="btn btn-info btn-flat">Verificar</button>
                                            </span>
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label class="col-form-label" for="description"><i class="fas fa-check"></i> Descripción</label>
                                    <textarea class="form-control" rows="3" id="description2" name="description" placeholder="Breve descripción"></textarea>
                                </div>
                                <div class="form-group text-center">
                                    <button id="saveSerial" type="button" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-tips" role="tabpanel"
                            aria-labelledby="custom-tabs-four-tips-tab">
                            <div class="row mb-2">
                                <div class="col">
                                    {{-- <span id="totalPercent" class="text-success">Total Porcentajes: 0%</span> --}}
                                </div>
                                <div class="col">
                                    <button type="button" id="newTipsPercent" class="btn btn-success float-right">+ Nuevo</button>
                                </div>
                            </div>
                            <div class="row">
                                <table id="dtTipsPercent" style="width: 100%!important;">
                                    <thead>
                                        <tr>
                                            <td>Id</td>
                                            <td>Area</td>
                                            <td>Puesto</td>
                                            <th>Puntos</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-debug" role="tabpanel"
                            aria-labelledby="custom-tabs-four-debug-tab">
                            <form action="" method="POST" id="frmAddDebug">
                                @csrf
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="custom-control custom-switch">
                                            @if($company->debug == 1)
                                                <input type="checkbox" class="custom-control-input" id="debugMode" name="debugMode" checked>
                                            @else
                                                <input type="checkbox" class="custom-control-input" id="debugMode" name="debugMode">
                                            @endif
                                            <label class="custom-control-label" for="debugMode">Modo desarrollador</label>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Serie (Boleta)</label>
                                            <input type="text" class="form-control" id="serieBoleta" name="serieBoleta" value="{{$serialBoleta->serie}}">
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Número (Boleta)</label>
                                            <input type="text" class="form-control" id="numberBoleta" name="numberBoleta" value="{{$serialBoleta->number}}">
                                        </div>            
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Serie (Factura)</label>
                                            <input type="text" class="form-control" id="serieFactura" name="serieFactura" value="{{$serialFactura->serie}}">
                                        </div>            
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label>Número (Factura)</label>
                                            <input type="text" class="form-control" id="numberFactura" name="numberFactura" value="{{$serialFactura->number}}">
                                        </div>            
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button id="saveDebug" type="button" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    @include('company.add-tipspercent')
@endrole

@role('Mozo')
    <p style="color: red">No tiene permisos para esta sección</p>
@endrole    
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
<style>
    div.dataTables_wrapper {width: 100%;} 
</style>    
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;
    
    let _saveCompany = $("#saveCompany");
    let _serieType = $("#serieType");
    let _companySerialId = $("#companySerialId");
    let _serie = $("#serie");
    let _number = $("#number");
    let _description = $("#description2");
    let _saveSerial = $("#saveSerial");

    let _dtTipsPercent = $("#dtTipsPercent");
    let _newTipsPercent = $("#newTipsPercent");
    let _modalTipsPercent = $("#tipsPercentModal");
    let _tipsPercentId = $("#tipsPercentId");
    let _employ = $("#employ");
    let _area = $("#area");
    let _points = $("#points");
    
    let _addTipsPercent = $("#addTipsPercent");
    let _ds=null;
    let _totalPercent = $("#totalPercent");
    let _titlemodal = $("#titlemodal");
    
    $(document).ready(function() {

        fetchTipsPercent();

        $("#verifyNumber").on("click", function(e){
            e.preventDefault();
            verifyNumber();    
        });

        $("#saveDebug").on("click", function(e){
            e.preventDefault();
            let elements = [
                ['serieBoleta', 'Ingrese la serie de la boleta'],
                ['numberBoleta', 'Ingrese el número de la boleta'],
                ['serieFactura', 'Ingrese la serie de la factura'],
                ['numberFactura', 'Ingrese el número de la factura']
            ];

            if(emptyfy(elements)) {
                let route = "{{ route('companyserial.adddebug') }}";
                let data = getFormParams('frmAddDebug');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        showSuccessMsg(result.message);
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        })

        _newTipsPercent.on("click", function(e){
            e.preventDefault();
            _tipsPercentId.val("");
            _employ.val("");
            _area.val(0).change();
            _points.val(1).change();
            _titlemodal.text("Agregar Porcentaje");
            _modalTipsPercent.modal("show");
        });

        _addTipsPercent.on("click", function(e){
            e.preventDefault();
            let elements = [
                ['employ', 'Ingrese el puesto o cargo']
            ];

            if(emptyfy(elements)) {
                let tipsPercentId = _tipsPercentId.val();
                
                let route = "{{ route('tipspercent.add') }}";
                if(tipsPercentId!="") {
                    route = "{{ route('tipspercent.edit') }}";
                }

                let data = getFormParams('frmAddTipsPercent');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modalTipsPercent.modal('hide');
                        showSuccessMsg(result.message);
                        fetchTipsPercent();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                });
            }

        });   

        _serieType.on("change", function(e){
            e.preventDefault();
            let id = $(this).val();
            fetchSeriales(id);
        });

        _saveCompany.on("click", function(e){
            e.preventDefault();
            let elements = [
                ['company', 'Ingrese el nombre de la empresa'],
                ['ruc', 'Ingrese el ruc de la empresa'],
                ['igv', 'Ingrese el IGV'],
                ['address', 'Ingrese la dirección de la empresa']
            ];

            if(emptyfy(elements)) {
                route = "{{ route('company.store') }}";

                let data = getFormParams('frmAddCompany');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        showSuccessMsg(result.message);
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });

        _saveSerial.on("click", function(e){
            e.preventDefault();
            let elements = [
                ['serieType', 'Seleccione el tipo de documento'],
                ['serie', 'Ingrese la serie'],
                ['number', 'Ingrese el número']
            ];
            
            if(emptyfy(elements)) {
                route = "{{ route('companyserial.store') }}";
                
                let data = getFormParams('frmAddSerial');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        showSuccessMsg(result.message);
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });

        _serieType.val(1).change();

        _dtTipsPercent.on('click', '.editTipsPercent', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                _tipsPercentId.val(id);
                _employ.val(employ);
                _area.val(area).change();
                _points.val(points).change();
            }
            _titlemodal.text("Editar Porcentaje");
            _modalTipsPercent.modal('show');
        });

        _dtTipsPercent.on('click', '.removeTipsPercent', function (e) {
            e.preventDefault();
            let tipsPercentId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el porcentaje?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/tipspercent/remove/" + tipsPercentId, {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-Token": _token
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            showSuccessMsg(result.message);
                            fetchTipsPercent();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });

    async function fetchSeriales(companySerialId) {
        const response = await fetch("/companyserial/list/" + companySerialId, {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch companySerial");       
        }                    
        const data = await response.json();
        with(data.companySerial){
            _companySerialId.val(id);
            _serie.val(serie);
            _number.val(number);
            _description.val(description);

            _serieType.val(serieType);
        }        
    }

    async function fetchTipsPercent() {
        const response = await fetch("/tipspercent/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch tipsPercent");       
        }                    
        const data = await response.json();
        _ds = data.list;
        _dtTipsPercent.DataTable().destroy();
        _dtTipsPercent.DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false,
            "data": data.list,
            "responsive": true,
            order: [[0, 'desc']],
            "columns": [
                {
                    "render": function(data, type, row, meta) {
                        return row.id;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        let area = 'Salón';
                        if(row.area == 1) {
                            area = 'Producción';
                        }
                        if(row.area == 2) {
                            area = 'Otros';
                        }
                        return area;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.employ;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.points;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="#" data-index="'+meta.row+'" class="btn btn-xs btn-info editTipsPercent"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-xs btn-danger removeTipsPercent"><i class="fas fa-trash"></i></a>';
                    }
                }
            ]
        });
        
        // let _total = 0.0;
        // for($i = 0; $i < _ds.length; $i++) {
        //     _total += parseFloat(_ds[$i].percent);
        // }
        // _totalPercent.html('Total Procentaje: ' + _total + '%');
    }

    async function verifyNumber() {
        let serieType = _serieType.val();
        let serie = _serie.val();
        const response = await fetch("/companyserial/verify/" + serieType + "/" + serie, {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch verifyNumber");       
        }                    
        const data = await response.json();
        if(data.status=="success"){
            showSuccessMsg("Serie sugerida: " + data.serie + " - Número sugerido:" + data.suggestedNumber);
            _serie.val(data.serie);
            _number.val(data.suggestedNumber);
        }
        if(data.status=="error"){
            showErrorMsg(data.message);
        }
    }
</script>        
@stop