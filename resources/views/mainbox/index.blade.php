@extends('adminlte::page')

@section('title', 'Caja Principal')

@section('content_header')
    <h1>Caja Principal</h1>
@stop

@section('content')
    @role(['Admin'])
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="#" method="POST" id="frmListMainBox">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <select class="form-control" name="dateRange" id="dateRange" required>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text bg-gradient-info">
                                            <i class="fas fa-location-arrow"></i>
                                        </div>
                                    </x-slot>
                                    <option value="all">Todas las fechas</option>
                                    <option value="today">Solo Hoy</option>
                                    <option value="yesterday">Solo Ayer</option>
                                    <option value="this_week">Esta Semana</option>
                                    <option value="last_week">La Semana Pasada</option>
                                    <option value="this_month">Este Mes</option>
                                    <option value="last_month">El Mes Pasado</option>
                                    <option value="this_year">Este año</option>
                                    <option value="custom">Seleccionar Fechas</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select class="form-control" name="movementType" id="movementType">
                                    <option value="-1">Movimientos > 0</option>
                                    <option value="0">Todos los movimientos</option>
                                    <option value="1">Solo Ingresos</option>
                                    <option value="2">Solo Gastos</option>
                                    <option value="3">Solo Eliminados</option>
                                </select>
                            </div>
                            <div class="col-auto pt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="salesOfDay" name="salesOfDay">
                                    <label class="custom-control-label" for="salesOfDay">Incluir ventas del día</label>
                                </div>
                            </div>
                            <div class="col">
                                <button id="showReport" type="submit" class="btn btn-primary">Ver Reporte</button>
                            </div>
                            <div class="col">
                                <button id="newIncome" type="button" class="btn btn-success mr-2">+ Ingreso</button> 
                                <button id="newExpense" type="button" class="btn btn-danger">+ Gasto</button>
                            </div>
                        </div>
                        <div id="rowDates" class="row mt-2" style="display:none;">
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="endDate" name="endDate" placeholder="Fecha Fin">
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>        
            </div>
        </div> 
    </div>

    <div class="row">
        <div class="col">
            <div class="info-box bg-gradient-success">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Ingresos</span>
                    <span id="lIncome" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="info-box bg-gradient-danger">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Gastos</span>
                    <span id="lExpense" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="info-box bg-gradient-primary">
                <div class="info-box-content">
                    <span class="info-box-text text-center">Diferencia</span>
                    <span id="lTotal" class="info-box-number text-center">s/ 0.00</span>
                </div>
            </div>
        </div>
        <div class="col">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <x-adminlte-card>
                    <table id="dtMainBox" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 30px;">Id</th>
                                <th style="width: 100px;">Fecha</th>
                                <th style="width: 100px;">Concepto Ingreso</th>
                                <th style="width: 80px;">Ingreso</th>
                                <th style="width: 100px;">Concepto Salida</th>
                                <th style="width: 80px;">Salida</th>
                                <th style="width: 80px;">Descripción</th>
                                <th style="width: 80px;">Usuario</th>
                                <th style="width: 80px;">Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </x-adminlte-card>    
            </div>        
        </div>
    </div>

    @include('mainbox.income')
    @include('mainbox.expense')
@endrole   

@role(['Mozo', 'Maitre'])
    <p style="color: red">No tiene permisos para esta sección</p>
@endrole 
@stop

@section('css')
<link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="/vendor/admin/main.css">
<style>
    .info-box{
        min-height: 60px!important;
        padding: .2rem!important;
    }
    .info-box-text{
        padding: 0px!important;
        margin: 0px!important;
        line-height: 18px!important;
    }
    .info-box-number{
        margin-top: 0px!important;
    }
    .info-box-number2{
        margin-top: 0px!important;
        display: block!important;
        font-weight: 500!important;
    }
    .col-vercent{
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
</style>
@stop


@section('js')
<script src="/vendor/admin/main.js"></script>
<script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _mainBoxId = $('#mainBoxId');
    let _incomeModal = $('#incomeModal');
    let _incomeModalTitle = $('#incomeModalTitle');
    let _incomeconceptId = $('#incomeconceptId');
    let _income = $('#income');
    let _description = $('#description');
    let _dtMainBox = $("#dtMainBox");

    let _mainBoxId2 = $('#mainBoxId2');
    let _modalExpense = $('#modalExpense');
    let _modalExpenseTitle = $('#modalExpenseTitle');
    let _expenseType = $('#expenseType');
    let _staffPayType = $('#staffPayType');
    let _providerId = $('#providerId');
    let _serviceId = $('#serviceId');
    let _staffId = $('#staffId');
    let _otherPayId = $('#otherPayId');
    let _expense = $('#expense');
    let _voucherType = $('#voucherType');
    let _voucherNumber = $('#voucherNumber');
    let _description1 = $('#description1');

    let _pProvider = $("#pProvider");
    let _pService = $("#pService");
    let _pStaff = $("#pStaff");
    let _dStaff = $("#dStaff");
    let _pOtherPay = $("#pOtherPay");
    let _dVoucher = $("#dVoucher");

    let _lIncome = $("#lIncome");
    let _lExpense = $("#lExpense");
    let _lTotal = $("#lTotal");
    let _ds = null;
        
    $(function() {
        $("#startDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#endDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#expenseDate").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });
        
    $(document).ready(function() {

        _pProvider.hide();
        _pService.hide();
        _pStaff.hide();
        _pOtherPay.hide();
        _dVoucher.hide();

        fetchMainBox();

        $("#startDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $("#endDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $("#expenseDate").on('changeDate', function(ev){
            $(this).datepicker('hide');
        });

        $('#dateRange').on('change', function(e) {
            e.preventDefault();
            var range = this.value;
            if(range=="custom"){
                $("#rowDates").show();    
            }else{
                $("#rowDates").hide();    
            }
        });
        
        $('#showReport').on('click', function(e) {
            e.preventDefault();
            var range = $("#dateRange").val();
            if(range=="custom"){
                var start_date = $("#startDate").val();
                var end_date = $("#endDate").val();
                if (start_date == "" || end_date == "") {
                    alert("Las fechas son requeridas");
                } else {
                    fetchMainBox();
                }    
            }else{
                fetchMainBox();
            }
        });
        
        $("#newIncome").on("click", function(e) {
            e.preventDefault();
            clearFormIncome();
            _incomeModalTitle.text("Agregar Ingreso");
            _incomeModal.modal("show");
        })

        $("#addIncome").on("click", function(e) {
            e.preventDefault();
            let elements = [
                ['incomeconceptId', 'Seleccione el concepto'],
                ['income', 'Ingrese el monto']
            ];

            if(emptyfy(elements)) {
                let mainBoxId = _mainBoxId.val();
                
                let route = "{{ route('mainbox.add') }}";
                if(mainBoxId!="") {
                    route = "{{ route('mainbox.edit') }}";
                }

                let data = getFormParams('frmAddIncome');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _incomeModal.modal('hide');
                        showSuccessMsg(result.message);
                        fetchMainBox();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                });
            }
        })

        $("#newExpense").on("click", function(e) {
            e.preventDefault();
            clearFormExpense();
            _modalExpenseTitle.text("Agregar Gasto");
            _modalExpense.modal("show");
        })

        _expenseType.on("change", function() {
            let expenseType = _expenseType.val();
            switch (expenseType) {
                case '1':
                    _pProvider.show();  
                    _pService.hide();
                    _pStaff.hide();
                    _dStaff.hide();
                    _pOtherPay.hide();
                    _dVoucher.show();
                    break;
                case '2':
                    _pService.show();  
                    _pProvider.hide();
                    _pStaff.hide();
                    _dStaff.hide();
                    _pOtherPay.hide();    
                    _dVoucher.show();
                    break;    
                case '3':
                    _pStaff.show();
                    _dStaff.show();  
                    _pProvider.hide();
                    _pService.hide();
                    _pOtherPay.hide();   
                    _dVoucher.hide();
                    break; 
                case '4':
                    _pOtherPay.show();
                    _pProvider.hide();
                    _pService.hide();
                    _pStaff.hide();
                    _dStaff.hide();
                    _dVoucher.hide();
                    _dVoucher.show();
                    break;
            }
        })

        $("#addExpense").on("click", function(e) {
            e.preventDefault();
            if (_expenseType.val()== 1 && _providerId.val()==null ){
                showWarningMsg('Seleccione un proveedor');                            
                return;
            }

            if (_expenseType.val()== 2 && _serviceId.val()=="" ){
                showWarningMsg('Seleccione un servicio');                            
                return;
            }

            if (_expenseType.val()== 3 && _staffId.val()=="" ){
                showWarningMsg('Seleccione un personal');                            
                return;
            }

            if (_expenseType.val()== 4 && _otherPayId.val()=="" ){
                showWarningMsg('Seleccione un concepto de pago');                            
                return;
            }

            let elements = [
                ['expense', 'Ingrese el monto del gasto'],
                ['expenseDate', 'Ingrese la fecha'],
            ];

            if(emptyfy(elements)) {
                let mainBoxId = _mainBoxId2.val();
                
                let route = "{{ route('mainbox.addexpense') }}";
                if(mainBoxId!="") {
                    route = "{{ route('mainbox.editexpense') }}";
                }

                let data = getFormParams('frmAddExpense');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modalExpense.modal('hide');
                        showSuccessMsg(result.message);
                        fetchMainBox();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        })
        
        _dtMainBox.on('click', '.itemEdit', function (e) {
            e.preventDefault();
            let index = $(this).data('index');
            let rw = _ds[index];
            with (rw) {
                if(movementType == 1) {
                    clearFormIncome();

                    _mainBoxId.val(id);
                    _incomeconceptId.val(incomeconceptId).change();
                    _income.val(income);
                    _description.val(description);      
                    
                    _incomeModalTitle.text("Editar Ingreso");
                    _incomeModal.modal("show");
                }
                if(movementType == 2) {
                    clearFormExpense();

                    _mainBoxId2.val(id);
                    _staffPayType.val(staffPayType).change();
                    _providerId.val(providerId).change();
                    _serviceId.val(serviceId).change();
                    _staffId.val(staffId).change();
                    _otherPayId.val(otherPayId).change();
                    _expense.val(expense);
                    _voucherType.val(voucherType).change();
                    _voucherNumber.val(voucherNumber);
                    _description1.val(description);

                    _expenseType.val(expenseType).change();
                    $("#expenseDate").val(expenseDate);
                    
                    _modalExpenseTitle.text("Editar Gasto");
                    _modalExpense.modal("show");
                }
            }
            
        });

        _dtMainBox.on('click', '.itemRemove', function (e) {
            e.preventDefault();
            let mainboxId = $(this).data('id');
            Swal.fire({
                title: "Atención",
                text: "Deseas eliminar el Registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/mainbox/remove/" + mainboxId, {
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
                            fetchMainBox();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });
    });

    function fetchMainBox() {
        let route = "{{ route('mainbox.list') }}";        
        let data = getFormParams('frmListMainBox');

        fetch(route, {
            method: 'post',
            body: data,
        })
        .then(response => response.json())
        .then(result => {
            if(result.status=="success") {
                _ds = result.list;
                _dtMainBox.DataTable().destroy();    
                _dtMainBox.DataTable({
                    "data": result.list,
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
                                return row.createdDate;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.incomeConcept != null){
                                    return "<small class='badge badge-success'>" + row.incomeConcept + "</small>";    
                                }else{
                                    return "";
                                }
                                
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.income > 0){
                                    return "<span class='text-success'>" + row.income + "</span>";
                                }else{  
                                    return row.income;
                                }
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                let concept = "";   
                                if(row.expenseType == 3){
                                    concept = getStaffPayType(row.staffPayType);
                                }
                                if(row.expenseType == 4){
                                    concept = " <small class='badge badge-secondary'>" + row.otherPayMotive + "</small>";
                                    return concept;
                                }
                                if(row.expenseType == 5){
                                    return "<small class='badge badge-danger'>Saldo Inicial</small>";  
                                }
                                return getExpenseType(row.expenseType) + concept;

                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.expense > 0){
                                    return "<span class='text-danger'>" + row.expense + "</span>";
                                }else{  
                                    return row.expense;
                                }
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.description != null && row.description.length > 24){
                                    return row.description.substring(0,24) + "...";      
                                }else{
                                    if(row.payboxId != null){
                                        return "<a href='/paybox/show/" + row.payboxId + "' target='_blank'>" + row.description + "</a>";
                                    }
                                    return row.description;
                                }
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                return row.userName;
                            }
                        },
                        {
                            "render": function(data, type, row, meta) {
                                if(row.history_count > 0){
                                    return '<a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info itemEdit"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger itemRemove"><i class="far fa-trash-alt"></i></a> <a href="/report/history/'+row.id+'" class="btn btn-sm btn-success historysale"><i class="fas fa-history"></i></a>';    
                                }else{
                                    return '<a href="#" data-index="'+meta.row+'" class="btn btn-sm btn-info itemEdit"><i class="far fa-edit"></i></a> <a href="#" data-id="'+row.id+'" class="btn btn-sm btn-danger itemRemove"><i class="far fa-trash-alt"></i></a>';
                                }
                                
                            }
                        }
                    ]
                });

                //$('#lYape').html('S/ ' + formatMoney(result.withYape));

                _lIncome.text("S/ " + formatMoney(result.totalIncome));
                _lExpense.text("S/ " + formatMoney(result.totalExpense));
                let total = 0.00;
                total = parseFloat(result.totalIncome) - parseFloat(result.totalExpense);
                _lTotal.text("S/ " + formatMoney(total.toFixed(2)));
            }
        });    
    }

    function clearFormIncome(){
        _mainBoxId.val("");
        _incomeconceptId.val("").change();
        _income.val("");
        _description.val("");  
    }

    function clearFormExpense(){
        _mainBoxId2.val("");
        _staffPayType.val("0").change();
        _providerId.val("0").change();
        _serviceId.val("").change();
        _staffId.val("").change();
        _otherPayId.val("").change();
        _expense.val("");
        _voucherType.val(0).change();
        _voucherNumber.val("");
        _description1.val("");

        _expenseType.val(1).change();
        $("#expenseDate").val("");
    }

    
</script>    
@stop    
