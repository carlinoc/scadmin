@extends('adminlte::page')

@section('title', 'Yape')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Yape - Plin</h1>
        </div>
    </div>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form action="#" method="POST" id="frmYapeExpense">
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
                                <div class="col">
                                    <button id="showReport" type="submit" class="btn btn-primary">Ver Detalle</button>
                                </div>
                            </div>
                            <div id="rowDates" class="row mt-2" style="display:none;">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="startDate" name="startDate" data-date-format="dd-mm-yyyy"
                                            placeholder="Fecha Inicio">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text btn btn-primary text-white" id="basic-addon1"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="endDate" name="endDate" data-date-format="dd-mm-yyyy"
                                            placeholder="Fecha Fin">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2">
                <div class="info-box bg-gradient-success">
                    <div class="info-box-content">
                        <span class="info-box-text text-center">Ingresos</span>
                        <span id="lIncome" class="info-box-number text-center">s/ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="info-box bg-gradient-danger">
                    <div class="info-box-content">
                        <span class="info-box-text text-center">Gastos</span>
                        <span id="lExpense" class="info-box-number text-center">s/ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="info-box bg-gradient-info">
                    <div class="info-box-content">
                        <span class="info-box-text text-center">Diferencia</span>
                        <span id="lTotal" class="info-box-number text-center">s/ 0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Ingresos</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="dtPosIncome" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width:80px">Id</th>
                                    <th style="width:100px">Fecha</th>
                                    <th style="width:100px">Monto S/</th>
                                    <th style="width:80px">Opc.</th>
                                </tr>
                            </thead>
                            <tbody id="tbIncome">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Gastos</h3>
                        <div class="card-tools">
                            <a href="#" id="newExpense" class="btn btn-tool btn-sm bg-danger">
                                <i class="fas fa-plus"></i> Nuevo
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table id="dtPosExpense" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th style="width:80px">Id</th>
                                    <th style="width:100px">Fecha</th>
                                    <th style="width:100px">Categoria</th>
                                    <th style="width:120px">Descripción</th>
                                    <th style="width:100px">Monto S/</th>
                                    <th style="width:80px">Opc.</th>
                                </tr>
                            </thead>
                            <tbody id="tbExpense">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('yapeexpense.expense')
    @endrole

    @role('Mozo')
        <p style="color: red">No tiene permisos para esta sección</p>
    @endrole
@stop

@section('css')
    <link href="/vendor/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>        
    <link rel="stylesheet" href="/vendor/admin/main.css">
    <style>
        div.dataTables_wrapper {
            width: 100%;
        }

        .info-box {
            min-height: 60px !important;
            padding: .2rem !important;
        }

        .info-box-text {
            padding: 0px !important;
            margin: 0px !important;
            line-height: 18px !important;
        }

        .info-box-number {
            margin-top: 0px !important;
        }

        .info-box-number2 {
            margin-top: 0px !important;
            display: block !important;
            font-weight: 500 !important;
        }

        .col-vercent {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
    </style>
@stop

@section('js')
    <script src="/vendor/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/vendor/admin/main.js"></script>
    <script>
        let _token = document.head.querySelector("[name~=csrf-token][content]").content;
        let _lIncome = $("#lIncome");
        let _lExpense = $("#lExpense");
        let _lTotal = $("#lTotal");
        let _modalExpense = $("#modalExpense");
        let _modalExpenseTitle = $("#modalExpenseTitle"); 

        let _yapeExpenseId = $("#yapeexpenseId");
        let _expenseDate = $("#expenseDate");
        let _expense = $("#expense");
        let _description = $("#description");

        let _providerId = $("#providerId");
        let _serviceId = $("#serviceId");
        let _staffId = $("#staffId");
        let _otherPayId = $("#otherPayId");
        let _voucherType = $("#voucherType");
        let _voucherNumber = $("#voucherNumber");

        let _pProvider = $("#pProvider");
        let _pService = $("#pService");
        let _pStaff = $("#pStaff");
        let _pOtherPay = $("#pOtherPay");

        let _expenseType = $("#expenseType");
        let _subCategoryId = "";
        let _totalIncome = 0.00;
        let _totalExpense = 0.00;
        let _dsExpense=null;
        
        $(function() {
            $("#startDate").datepicker({
                
            });
            $("#endDate").datepicker({
                
            });
            _expenseDate.datepicker({});
        });

        $(document).ready(function() {

            fetchIncome();
            fetchExpense();
                        
            _pProvider.hide();
            _pService.hide();
            _pStaff.hide();
            _pOtherPay.hide();

            $("#expensecategoryId").on('change', function(e) {            
                e.preventDefault();
                let parentId = $(this).val();
                if(parentId != ""){
                    fetchSubCategories(parentId);
                }
            });

            $("#subCategoryId").on('change', function(e) {            
            e.preventDefault();
            let expenseType = $('#expensecategoryId option:selected').attr('data-expensetype');
                if(expenseType != ""){
                    switch (expenseType) {
                        case '1':
                            _pProvider.show();  
                            _pService.hide();
                            _pStaff.hide();
                            _pOtherPay.hide();
                            break;
                        case '2':
                            _pService.show();  
                            _pProvider.hide();
                            _pStaff.hide();
                            _pOtherPay.hide();    
                            break;    
                        case '3':
                            _pStaff.show();
                            _pProvider.hide();
                            _pService.hide();
                            _pOtherPay.hide();   
                            break; 
                        case '4':
                            _pOtherPay.show();
                            _pProvider.hide();
                            _pService.hide();
                            _pStaff.hide();
                            break;
                        default:
                            _pProvider.hide();
                            _pService.hide();  
                            _pStaff.hide();
                            _pOtherPay.hide();
                            break;     
                    }
                    $("#expenseType").val(expenseType);
                }
            });

            $("#newExpense").on('click', function(e){
                e.preventDefault();
                clearFormExpense();
                _modalExpenseTitle.text("Nuevo Gasto");
                _modalExpense.modal('show');
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

            $("#startDate").on('changeDate', function(ev){
                $(this).datepicker('hide');
            });

            $("#endDate").on('changeDate', function(ev){
                $(this).datepicker('hide');
            });

            _expenseDate.on('changeDate', function(ev){
                $(this).datepicker('hide');
            });

            $('#showReport').on('click', function(e) {
                e.preventDefault();
                var range = $("#dateRange").val();
                if(range=="custom"){
                    var start_date = $("#startDate").val();
                    var end_date = $("#endDate").val();
                    if (start_date == "" || end_date == "") {                        
                        showWarningMsg("Las fechas son requeridas");
                    } else {
                        fetchIncome();
                        fetchExpense();
                    }    
                }else{
                    fetchIncome();
                    fetchExpense();
                }
            });

            $("#addYapeExpense").on('click', function(e) {
                e.preventDefault();

                if($("#expensecategoryId").val() == "") {
                    showWarningMsg("Debes seleccionar la categoría");
                    return;
                }

                if($("#subCategoryId").val() == "") {
                    showWarningMsg("Debes seleccionar la subcategoría");
                    return;
                }

                let elements = [
                    ['expense', 'Ingrese el monto del gasto'],
                    ['expenseDate', 'Ingrese la fecha del gasto'],
                ];

                if(emptyfy(elements)) {
                    let yapeExpenseId = _yapeExpenseId.val();
                    
                    let route = "{{ route('yapeexpense.add') }}";
                    if(yapeExpenseId!="") {
                        route = "{{ route('yapeexpense.edit') }}";
                    }

                    let data = getFormParams('frmAddYapeExpense');
                    fetch(route, {
                        method: 'post',
                        body: data,
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            _modalExpense.modal('hide');
                            showSuccessMsg(result.message);
                            fetchExpense();
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    })
                }
            });

            $("#dtPosExpense").on('click', '.editItem', function (e) {
                e.preventDefault();
                let index = $(this).data('index');
                let rw = _dsExpense[index];
                with (rw) {
                    _yapeExpenseId.val(id);
                    $("#expensecategoryId").val(parentId).change();
                    _subCategoryId = expensecategoryId;
                    $("#expenseType").val(expenseType);
                    _expenseDate.val(expenseDate);
                    _expense.val(expense);
                    _description.val(description);

                    _providerId.val(providerId).change();
                    _voucherType.val(voucherType).change();
                    _voucherNumber.val(voucherNumber);
                    _serviceId.val(serviceId).change();
                    _otherPayId.val(otherPayId).change();
                    _staffId.val(staffId).change();
                    
                }
                _modalExpenseTitle.text("Editar Gasto");
                _modalExpense.modal('show');
            });

            $("#dtPosExpense").on('click', '.removeItem', function (e) {
                e.preventDefault();
                let yapeexpenseId = $(this).data('id');
                Swal.fire({
                    title: "Atención",
                    text: "Deseas eliminar el Gasto?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/yapeexpense/remove/" + yapeexpenseId, {
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
                                fetchExpense();
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });
            });
        });

        async function fetchExpense() {
            let route = "{{ route('yapeexpense.expenselist') }}";        
            let data = getFormParams('frmYapeExpense');

            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success") {
                    _dsExpense = result.list;
                    $("#tbExpense").empty();
                    _lExpense.html('S/ 0.00');
                    let totalExpense = 0.00;
                    for($i = 0; $i < _dsExpense.length; $i++) {
                        rw = _dsExpense[$i];
                        totalExpense += parseFloat(rw.expense);
                        addRowExpense(rw.expenseTime, rw.expense, rw.id, $i, rw.expenseType, rw.category, rw.description, rw.providerName, rw.serviceName, rw.staffName, rw.otherpayName);
                    }
                    _totalExpense = totalExpense;
                    _lExpense.html('S/ ' + formatMoney(totalExpense));

                    let _totalDif = 0.00;
                    setTimeout(function(){
                        _totalDif = _totalIncome - totalExpense;
                        _lTotal.html('S/ ' + formatMoney(_totalDif));
                    }, 300);
                }
            });   
        }

        function addRowExpense(vdate, vexpense, vid, vindex, vexpenseType, vcategory, vdescription, vprovider, vservice, vstaff, vother) {
            let table = document.getElementById("tbExpense");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            let c5 = document.createElement("td");
            let c6 = document.createElement("td");

            c1.innerText = vid;
            c2.innerText = getOnlytDate(vdate);
            let concept = "";
            if(vexpenseType == 1){
                concept = " <small class='badge badge-warning'>" + vprovider + "</small>";
            }   
            if(vexpenseType == 2){
                concept = " <small class='badge badge-warning'>" + vservice + "</small>";
            }
            if(vexpenseType == 3){
                concept = " <small class='badge badge-warning'>" + vstaff + "</small>";
            }
            if(vexpenseType == 4){
                concept = " <small class='badge badge-warning'>" + vother + "</small>";
            }
            if(vcategory != null){
                concept = " <small class='badge badge-light'>" + vcategory + "</small>";
            }
            c3.innerHTML = getExpenseType(vexpenseType) + concept;
            let description = "";
            if(vdescription != null && vdescription.length > 26){
                description = vdescription.substring(0,26) + "...";      
            }else{
                description = vdescription;
            }
            c4.innerHTML = description;
            c5.innerText = vexpense;
            c6.innerHTML = '<a href="#" data-index="'+vindex+'" class="btn btn-xs btn-info editItem"><i class="far fa-edit"></i></a> <a href="#" data-id="'+vid+'" class="btn btn-xs btn-danger removeItem"><i class="far fa-trash-alt"></i></a>';
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
            row.appendChild(c5);
            row.appendChild(c6);
            
            table.appendChild(row);
        }

        async function fetchIncome() {
            let route = "{{ route('yapeexpense.incomelist') }}";        
            let data = getFormParams('frmYapeExpense');

            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success") {                    
                    let ds = result.list;
                    $("#tbIncome").empty();
                    _lIncome.html('S/ 0.00');
                    let totalIncome = 0.00;
                    for($i = 0; $i < ds.length; $i++) {
                        totalIncome += parseFloat(ds[$i].total);
                        dr = ds[$i]; 
                        addRow(dr.created_at, dr.total, dr.id, $i);
                    }
                    _totalIncome = totalIncome;
                    _lIncome.html('S/ ' + formatMoney(totalIncome));
                }
            });   
        }

        function addRow(vdate, vincome, vid, vindex) {
            let table = document.getElementById("tbIncome");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            
            c1.innerText = vid;
            c2.innerText = getOnlytDate(vdate);
            c3.innerText = vincome;
            c4.innerHTML = '<a href="/report/detail/'+vid+'" target="_blank" class="btn btn-xs btn-warning showItem"><i class="far fa-eye"></i></a>';
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
            
            table.appendChild(row);
        }

        async function fetchSubCategories(parentId) {
            const response = await fetch("/expensecategories/subcategories/" + parentId, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch subcategories");       
            }                    
            const data = await response.json();
            $("#subCategoryId").empty();
            $("#subCategoryId").append('<option value=""></option>');
            for(let i = 0; i < data.list.length; i++) {
                $("#subCategoryId").append('<option value="' + data.list[i].id + '">' + data.list[i].category + '</option>');
            }
            if(_subCategoryId != "") {
                $("#subCategoryId").val(_subCategoryId).change();
            }
        }
        
        function clearFormExpense() {
            _yapeExpenseId.val("");
            _expenseDate.datepicker().datepicker("setDate", "today");
            _expense.val("");
            _description.val("");

            _providerId.val("0").change();
            _serviceId.val("").change();
            _staffId.val("").change();
            _otherPayId.val("").change();
                        
            _voucherType.val(0).change();
            _voucherNumber.val("");

            _subCategoryId = "";
            $("#expensecategoryId").val("").change();
            $("#subCategoryId").val("").change();
            $("#expenseType").val("");
        }
    </script>
@stop
