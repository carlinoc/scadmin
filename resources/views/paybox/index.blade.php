@extends('adminlte::page')

@section('title', 'Apertura y Cierre de Caja')

@section('content_header')
    <h1>Apertura y Cierre de Caja</h1>
@stop

@section('content')
    @role(['Admin', 'Maitre'])
    <div>
        <div class="row">
            <div class="form-group col-md-6">
                <a href="#" id="newBoxPay" class="btn btn-primary">Aperturar Caja</a>
            </div>    
        </div>
    </div>

    <div>
        <x-adminlte-card>
            <div class="card-body">
                <table id="dtPayBox" class="row-border" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Cierre</th>
                            <th>Saldo Inicial</th>
                            <th>Ingresos</th>
                            <th>Gastos</th>
                            <th>Saldo </th>
                            <th>Saldo Final</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </x-adminlte-card>
    </div>

    @include('paybox.new')
    @include('paybox.add-income')
    @include('paybox.add-expense')
    @endrole   
    
    @role('Mozo')
    <p style="color: red">No tiene permisos para esta sección</p>
    @endrole
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _newPayBox = $("#newBoxPay");
    let _cashBalance = $("#cashBalance");
    let _addPayBox = $("#addPayBox");
    
    let _dtPayBox = $("#dtPayBox");
    let _modal = $("#addModal");
    let _ds=null;

    let _incomeModal = $('#incomeModal');
    let _payboxId = $('#payboxId');
    let _voucherNumber = $('#voucherNumber');
    let _voucherType = $('#voucherType');
    let _incomeConceptId = $('#incomeconceptId');
    let _amount = $('#amount');
    let _description = $('#description');
    let _addIncome = $('#addIncome');

    let _expenseModal = $('#expenseModal');
    let _providerId = $('#providerId');
    let _amount1 = $('#amount1');
    let _voucherNumber1 = $('#voucherNumber1');
    let _voucherType1 = $('#voucherType1');
    let _description1 = $('#description1');
    let _payboxId1 = $('#payboxId1');
    let _addExpenseProvider = $('#addExpenseProvider');
    
    let _staffId = $('#staffId');
    let _concept = $('#concept');
    let _amount2 = $('#amount2');
    let _description2 = $('#description2');
    let _payboxId2 = $('#payboxId2');
    let _addExpenseStaff = $('#addExpenseStaff');

    let _serviceId = $('#serviceId');
    let _amount3 = $('#amount3');
    let _voucherNumber3 = $('#voucherNumber3');
    let _voucherType3 = $('#voucherType3');
    let _description3 = $('#description3');
    let _addExpenseService = $('#addExpenseService');
    let _payboxId3 = $('#payboxId3');

        
    $(document).ready(function() {

        fetchPayBox();

        _newPayBox.on('click', function(e) {
            e.preventDefault();
            clearForm();

            let route = "{{ route('paybox.verifyopen') }}";
            fetch(route, {
                method: 'get'
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    showErrorMsg(result.message);    
                }else{
                    _modal.modal('show');
                    setTimeout(function(){
                        _cashBalance.focus();
                    }, 300);
                }
            });
        });

        _addPayBox.on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['cashBalance', 'Ingrese el saldo inicial']
            ];

            if(emptyfy(elements)) {
                let route = "{{ route('paybox.add') }}";
                let data = getFormParams('frmAddPayBox');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modal.modal('hide');
                        showSuccessMsg(result.message);
                        fetchPayBox();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _dtPayBox.on('click', '.addIncome', function (e) {
            e.preventDefault();
            clearIncomeForm();
            _incomeModal.modal('show');
            let payboxId = $(this).data('id');
            _payboxId.val(payboxId);
            setTimeout(function(){
                _voucherNumber.focus();
            }, 300);
        });

        _addIncome.on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['incomeconceptId', 'Seleccione el concepto de ingreso'],
                ['amount', 'Ingrese el monto']
            ];

            if(emptyfy(elements)) {
                let route = "{{ route('income.add') }}";
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
                        fetchPayBox();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });

        _dtPayBox.on('click', '.addExpense', function (e) {
            e.preventDefault();
            clearExpenseForm();
            _expenseModal.modal('show');
            let payboxId1 = $(this).data('id');
            _payboxId1.val(payboxId1);
            _payboxId2.val(payboxId1);
            _payboxId3.val(payboxId1);
            setTimeout(function(){
                _providerId.focus();
            }, 300);
        });

        _addExpenseProvider.on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['providerId', 'Seleccione el proveedor'],
                ['amount1', 'Ingrese el monto'],
                ['voucherNumber1', 'Ingrese el numero de voucher'],
                ['voucherType1', 'Seleccione el tipo de voucher']
            ];
            if(emptyfy(elements)) {
                let route = "{{ route('expenseprovider.add') }}";
                let data = getFormParams('frmAddExpenseProvider');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _expenseModal.modal('hide');
                        showSuccessMsg(result.message);
                        fetchPayBox();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
        
        _addExpenseStaff.on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['staffId', 'Seleccione el empleado'],
                ['concept', 'Ingrese el concepto'],
                ['amount2', 'Ingrese el monto']
            ];
            if(emptyfy(elements)) {
                let route = "{{ route('expensestaff.add') }}";
                let data = getFormParams('frmAddExpenseStaff');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _expenseModal.modal('hide');
                        showSuccessMsg(result.message);
                        fetchPayBox();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });

        _addExpenseService.on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['serviceId', 'Seleccione el servicio'],
                ['amount3', 'Ingrese el monto']
            ];
            if(emptyfy(elements)) {
                let route = "{{ route('expenseservice.add') }}";
                let data = getFormParams('frmAddExpenseService');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _expenseModal.modal('hide');
                        showSuccessMsg(result.message);
                        fetchPayBox();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });
    });
    
    async function fetchPayBox() {
        const response = await fetch("/paybox/list", {method: 'GET'});
        if(!response.ok){
            throw new Error("Error fetch providers");       
        }                    
        const data = await response.json();
        _ds = data.payboxs;
        _dtPayBox.DataTable().destroy();
        _dtPayBox.DataTable({
            "data": data.payboxs,
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
                        return "<small>" + row.startDate + "</small>";
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        if(row.closingDate==null){
                            return "";
                        }else{
                            return "<small>" + row.closingDate + "</small>";
                        }
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.cashBalance;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return "<span class='text-success'>" + row.income + "</span>";
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return "<span class='text-danger'>" + row.expenses + "</span>";
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.finalBalance;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return "<span class='text-danger'>" + row.cashRegister + "</span>";
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return row.userName;
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return (row.state==1?'<small class="badge badge-success">Abierto</small>':'<small class="badge badge-danger">Cerrado</small>');
                    }
                },
                {
                    "render": function(data, type, row, meta) {
                        return '<a href="/paybox/show/'+row.id+'" class="btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    }
                }
            ]
        });    
    }

    function clearForm() {
        _cashBalance.val();
    }

    function clearIncomeForm() {
        _payboxId.val('');
        _voucherNumber.val('');
        _voucherType.val('').change();
        _incomeConceptId.val('').change();
        _amount.val('');
        _description.val('');
    }

    function clearExpenseForm(){
        _payboxId1.val('');
        _payboxId2.val('');
        _payboxId3.val('');

        _providerId.val('').change();
        _amount1.val('');
        _voucherNumber1.val('');
        _voucherType1.val('').change();
        _description1.val('');

        _staffId.val('').change();
        _concept.val('');
        _amount2.val('');
        _description2.val('');

        _serviceId.val('').change();
        _amount3.val('');
        _voucherNumber3.val('');
        _voucherType3.val('').change();
        _description3.val('');        
    }
</script>        
@stop