@extends('adminlte::page')

@section('title', 'Empresa')

@section('content_header')
    <h1>Empresa</h1>
@stop

@section('content')
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
                                        <div class="form-group">
                                            <label>Número</label>
                                            <input type="text" class="form-control" id="number" name="number">
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
                                    <button id="saveSerial" type="button" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
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
    
    $(document).ready(function() {

        

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
</script>        
@stop