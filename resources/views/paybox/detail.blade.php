@extends('adminlte::page')

@section('title', 'Detalle del contrato')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Detalle de Caja:</h1>
        </div>
        <div class="col">
            <a href="{{ route('paybox.index') }}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <form action="" method="POST" id="frmClosePayBox">
                    @csrf
                    <input type="hidden" name="payboxId" value="{{$paybox->id}}">
                    <input type="hidden" id="zcashSales" name="cashSales">
                    <input type="hidden" id="zcardSales" name="cardSales">
                    <div class="row">
                        <div class="col">
                            <strong><i class="fas fa-calendar mr-1"></i> Fecha de Apertura</strong>
                            <p class="text-muted">
                                {{ $paybox->startDate }}
                            </p>
                        </div>
                        <div class="col">
                            <strong><i class="fas fa-calendar mr-1"></i> Fecha de Cierre</strong>
                            <p class="text-muted">
                                {{ $paybox->closingDate }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <strong><i class="fas fa-money-check-alt mr-1"></i> Total Ingresos S/</strong>
                            <input type="hidden" id="zincome" name="income">
                            <p id="totalIncome" class="text-muted">0.00</p>
                        </div>
                        <div class="col">
                            <strong><i class="fas fa-money-check-alt mr-1"></i> Total Gastos S/</strong>
                            <input type="hidden" id="zexpenses" name="expenses">
                            <p id="totalExpenses" class="text-muted">0.00</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <strong style="color:#dc3545;"><i class="fas fa-coins mr-1"></i> Saldo Faltante</strong>
                            <input type="text" class="form-control" id="missingBalance" name="missingBalance" value="{{$paybox->missingBalance}}" placeholder="0.00">
                        </div>
                        <div class="col">
                            <strong style="color:#007bff;"><i class="fas fa-coins mr-1"></i> Saldo Sobrante</strong>
                            <input type="text" class="form-control" id="leftoverBalance" name="leftoverBalance" value="{{$paybox->leftoverBalance}}" placeholder="0.00">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <strong><i class="fas fa-money-bill-alt mr-1"></i> Saldo final en Caja</strong>
                            <input type="text" class="form-control" id="finalBalance" name="finalBalance" value="{{$paybox->finalBalance}}" placeholder="0.00">
                        </div>
                        <div class="col">
                            <strong><i class="fas fa-cash-register mr-1"></i> Arqueo de Caja</strong>
                            <input type="text" class="form-control" id="cashRegister" name="cashRegister" value="{{$paybox->cashRegister}}" placeholder="0.00">
                        </div>
                    </div>
                    @if($paybox->state==1)
                    <div class="row mt-4">
                        <div class="col text-center">
                            <button type="button" id="verifyBalance" class="btn btn-outline-info">Actualizar Saldos</button>
                        </div>
                        <div class="col text-center">
                            <button id="closePayBox" type="button" class="btn btn-danger">Cerrar Caja</button>
                        </div>
                    </div>
                    @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-success card-outline">
                <div class="card-body">
                    <strong style="color:#28a745;"><i class="fas fa-cart-arrow-down mr-1"></i> Ingresos S/</strong>
                    <table class="table table-sm mt-2">
                        <tbody>
                            <tr>
                                <td>Saldo Inicial</td>
                                <td><b>{{ $paybox->cashBalance }}</b></td>
                            </tr>
                            <tr>
                                <td>Ingresos Varios</td>
                                <td id="tdIncome">0.00</td>
                            </tr>
                            <tr>
                                <td>Ventas al Contado</td>
                                <td id="tdCash">0.00</td>
                            </tr>
                            <tr>
                                <td>Ventas con Tarjeta</td>
                                <td id="tdCard" style="color:#dc3545">0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-danger card-outline">
                <div class="card-body">
                    <strong style="color:#dc3545;"><i class="fas fa-arrow-circle-up mr-1"></i> Gastos S/</strong>
                    <table class="table table-sm mt-2">
                        <tbody>
                            <tr>
                                <td>Pago Proveedores</td>
                                <td id="tdExpenseProvider">0.00</td>
                            </tr>
                            <tr>
                                <td>Sueldos y Adelantos</td>
                                <td id="tdExpenseStaff">0.00</td>
                            </tr>
                            <tr>
                                <td>Pago de Servicios</td>
                                <td id="tdExpenseService">0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-warning card-outline">
                <div class="card-body">
                    <input type="hidden" value="{{$totalTips}}" id="totalTips" />
                    <strong><span class="text-secondary" style="display: block"><i class="fas fa-coins mr-1"></i> Propinas S/ {{$totalTips}}</span></strong>
                    <span class="text-secondary">Efectivo: {{$totalCash}}</span> --- <span class="text-secondary">Tarjeta: {{$totalCard}}</span>
                    <table class="table table-sm mt-2">
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-4">
            <div id="cardIncome" class="card card-success">
                <div class="card-header">
                    <h3 id="titleIncome" class="card-title">Ingresos Varios</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="dtIncome" class="table-sm" style="width: 100%!important;">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Monto</th>
                                <th>Concepto</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="cardSalesCash" class="card card-success">
                <div class="card-header">
                    <h3 id="titleSalesCash" class="card-title">Ventas al contado</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="dtSalesCash" class="table-sm" style="width: 100%!important;">
                        <thead>
                            <tr>
                                <th style="width:12px!important;">Id</th>
                                <th>Fecha y Hora</th>
                                <th>Mesa</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="cardSalesCard" class="card card-success">
                <div class="card-header">
                    <h3 id="titleSalesCard" class="card-title">Ventas con tarjeta</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="dtSalesCard" class="table-sm" style="width: 100%!important;">
                        <thead>
                            <tr>
                                <th style="width:10px">Id</th>
                                <th>Fecha y Hora</th>
                                <th>Mesa</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="cardExpProvider" class="card card-danger">
                <div class="card-header">
                    <h3 id="titleExpProvider" class="card-title">Pago a Proveedores</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="dtExpenseProvider" class="table-sm" style="width: 100%!important;">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Monto</th>
                                <th>Proveedor</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="cardExpStaff" class="card card-danger">
                <div class="card-header">
                    <h3 id="titleExpStaff" class="card-title">Sueldos y Adelantos:</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="dtExpenseStaff" class="table-sm" style="width: 100%!important;">
                        <thead>
                            <tr>
                                <td>Fecha y Hora</td>
                                <td>Monto</td>
                                <td>Concepto</td>
                                <td>Personal</td>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="cardExpService" class="card card-danger" style="width: 100%!important;">
                <div class="card-header">
                    <h3 id="titleExpService" class="card-title">Pago de Servicios:</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="dtExpenseService" class="table-sm" style="width: 100%!important;">
                        <thead>
                            <tr>
                                <td>Fecha y Hora</td>
                                <td>Monto</td>
                                <td>Servicio</td>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
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
        let _token = document.head.querySelector("[name~=csrf-token][content]").content;

        let totalIncome = 0;
        let totalExpense = 0;

        let _payBoxId = {{ $paybox->id }};

        let _titleExpProvider = $("#titleExpProvider");
        let _dtExpenseProvider = $("#dtExpenseProvider");
        let _titleExpStaff = $("#titleExpStaff");
        let _dtExpenseStaff = $("#dtExpenseStaff");
        let _titleExpService = $("#titleExpService");
        let _dtExpenseService = $("#dtExpenseService");
        let _cardExpProvider = $("#cardExpProvider");
        let _cardExpStaff = $("#cardExpStaff");
        let _cardExpService = $("#cardExpService");

        let _cardIncome = $("#cardIncome");
        let _titleIncome = $("#titleIncome");
        let _dtIncome = $("#dtIncome");

        let _salesCash = $("#salesCash");
        let _titleSalesCash = $("#titleSalesCash");
        let _dtSalesCash = $("#dtSalesCash");
        let _cardSalesCash = $("#cardSalesCash");
        
        let _salesCard = $("#salesCard");
        let _titleSalesCard = $("#titleSalesCard");
        let _dtSalesCard = $("#dtSalesCard");
        let _cardSalesCard = $("#cardSalesCard");

        let _tdIncome = $("#tdIncome");
        let _tdExpenseProvider = $("#tdExpenseProvider");
        let _tdExpenseStaff = $("#tdExpenseStaff");
        let _tdExpenseService = $("#tdExpenseService");
        let _tdCash = $("#tdCash");
        let _tdCard = $("#tdCard");

        let _totalIncome = $("#totalIncome");
        let _totalExpense = $("#totalExpenses");
        let _verifyBalance = $("#verifyBalance");

        let _zcashSales = $("#zcashSales");
        let _zcardSales = $("#zcardSales");
        let _zincome = $("#zincome");
        let _zexpenses = $("#zexpenses");
        let _finalBalance = $("#finalBalance");
        let _cashRegister = $("#cashRegister");
        let _missingBalance = $("#missingBalance");
        let _leftoverBalance = $("#leftoverBalance");
        let _ds=null;
        let _totalTips = $("#totalTips");
        
        $(document).ready(function(e){

            _finalBalance.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");
            _cashRegister.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");
            _missingBalance.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");
            _leftoverBalance.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");
            
            setTotalIncome({{$paybox->cashBalance}});

            _verifyBalance.on("click", function(e){
                e.preventDefault();
                _missingBalance.val("");
                _leftoverBalance.val("");
                setFinalBalance();
                if(_cashRegister.val()!=""){
                    let cashRegister = _cashRegister.val();
                    let finalBalance = _finalBalance.val();
                    let balance = parseFloat(finalBalance) - parseFloat(cashRegister);
                    if(balance < 0){
                        balance = Math.abs(balance);
                        _leftoverBalance.val(balance.toFixed(2));
                        _missingBalance.val(0);
                    }else{
                        _leftoverBalance.val(0);
                        _missingBalance.val(balance.toFixed(2));
                    }
                }
                _zincome.val(totalIncome);
                _zexpenses.val(totalExpense);
            });

            _cashRegister.on("change", function(e){
                _zincome.val("");
                _zexpenses.val("");
            });

            $("#closePayBox").on('click', function(e){
                e.preventDefault();
                if(_zincome.val()=="" || _zexpenses.val()==""){
                    showErrorMsg("Debes ACTUALIZAR LOS SALDOS, antes de cerrar la caja");
                    return;
                }
                Swal.fire({
                    title: "Atención",
                    text: "Deseas Cerrar la Caja",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        if(_cashRegister.val()=="" || _cashRegister.val()==0){
                            Swal.fire({
                            title: "Atención",
                            text: "El arqueo de caja es igual a CERO, deseas continuar con el cierre de caja?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Aceptar"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    closePayBox();     
                                }
                            });
                        }else{
                            closePayBox();
                        }
                    }
                });
            });

            fetchIncome();

            _dtIncome.on('click', '.removeIncome', function (e) {
                e.preventDefault();
                let incomeId = $(this).data('id');
                Swal.fire({
                    title: "Atención",
                    text: "Deseas eliminar el ingreso?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/income/remove/" + incomeId, {
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
                                _tdIncome.html(result.income);    
                                fetchIncome();
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });
            });

            fetchSalesCash();

            fetchSalesCard();
            
            fetchExpenseProvider();

            _dtExpenseProvider.on('click', '.removeExpenseProvider', function (e) {
                e.preventDefault();
                let expenseProviderId = $(this).data('id');
                Swal.fire({
                    title: "Atención",
                    text: "Deseas eliminar el pago a proveedor?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/expenseprovider/remove/" + expenseProviderId, {
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
                                _tdExpenseProvider.html(result.expenses);
                                fetchExpenseProvider();
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });
            });

            fetchExpenseStaff();

            _dtExpenseStaff.on('click', '.removeExpenseStaff', function (e) {
                e.preventDefault();
                let expenseStaffId = $(this).data('id');
                Swal.fire({
                    title: "Atención",
                    text: "Deseas eliminar el pago a personal?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/expensestaff/remove/" + expenseStaffId, {
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
                                _tdExpenseStaff.html(result.expenses);
                                fetchExpenseStaff();
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });    
            });

            fetchExpenseService();

            _dtExpenseService.on('click', '.removeExpenseService', function (e) {
                e.preventDefault();
                let expenseServiceId = $(this).data('id');
                Swal.fire({
                    title: "Atención",
                    text: "Deseas eliminar el pago de servicio?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/expenseservice/remove/" + expenseServiceId, {
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
                                _tdExpenseService.html(result.expenses);
                                fetchExpenseService();
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });
            });
            
            _cardExpService.CardWidget('toggle');
            _cardExpProvider.CardWidget('toggle');
            _cardExpStaff.CardWidget('toggle');
            
            _cardIncome.CardWidget('toggle');
            _cardSalesCash.CardWidget('toggle');
            _cardSalesCard.CardWidget('toggle');

            fetchTips();
        });

        async function fetchExpenseStaff(){
            const response = await fetch("/expensestaff/list/" + _payBoxId, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch expensestaff");       
            }                    
            const data = await response.json();
            _titleExpStaff.text("Sueldos y Adelantos: S/ " + data.totalAmount);
            setTotalExpense(data.totalAmount); 
            _tdExpenseStaff.html(data.totalAmount);
            _dtExpenseStaff.DataTable().destroy();
            _dtExpenseStaff.DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
                "data": data.expenseStaffs,
                "responsive": true,
                order: [[0, 'desc']],
                "columns": [
                    {
                        "render": function(data, type, row, meta) {
                            return row.expenseDate;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.amount;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            if(row.concept==1){
                                return "Adelanto de sueldo";     
                            }
                            if(row.concept==2){
                                return "Sueldo";     
                            }
                            if(row.concept==3){
                                return "Completar sueldo";     
                            }
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.staff;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return '<a href="#" data-id="'+row.id+'" class="btn btn-xs btn-danger removeExpenseStaff"><i class="fas fa-trash"></i></a>';
                        }
                    }
                ]
            });   
        }

        async function fetchExpenseProvider(){
            const response = await fetch("/expenseprovider/list/" + _payBoxId, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch expenseprovider");       
            }                    
            const data = await response.json();
            _titleExpProvider.text("Pago a Proveedores: S/ " + data.totalAmount);
            setTotalExpense(data.totalAmount); 
            _tdExpenseProvider.html(data.totalAmount);
            _dtExpenseProvider.DataTable().destroy();
            _dtExpenseProvider.DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
                "data": data.expenseProviders,
                "responsive": true,
                order: [[0, 'desc']],
                "columns": [
                    {
                        "render": function(data, type, row, meta) {
                            return row.expenseDate;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.amount;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.provider;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return '<a href="#" data-id="'+row.id+'" class="btn btn-xs btn-danger removeExpenseProvider"><i class="fas fa-trash"></i></a>';
                        }
                    }
                ]
            });   
        }

        async function fetchExpenseService(){
            const response = await fetch("/expenseservice/list/" + _payBoxId, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch expenseservice");       
            }                    
            const data = await response.json();
            _titleExpService.text("Pago de Servicios: S/ " + data.totalAmount);
            setTotalExpense(data.totalAmount); 
            _tdExpenseService.html(data.totalAmount);
            _dtExpenseService.DataTable().destroy();
            _dtExpenseService.DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false,
                "data": data.expenseServices,
                "responsive": true,
                order: [[0, 'desc']],
                "columns": [
                    {
                        "render": function(data, type, row, meta) {
                            return row.expenseDate;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.amount;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.service;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return '<a href="#" data-id="'+row.id+'" class="btn btn-xs btn-danger removeExpenseService"><i class="fas fa-trash"></i></a>';
                        }
                    }
                ]
            });   
        }

        async function fetchIncome(){
            const response = await fetch("/income/list/" + _payBoxId, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch income");       
            }                    
            const data = await response.json();
            _titleIncome.text("Ingresos Varios: S/ " + data.totalAmount);
            setTotalIncome(data.totalAmount);
            _tdIncome.html(data.totalAmount);
            _dtIncome.DataTable().destroy();
            _dtIncome.DataTable({
                "paging": false,
                "info": false,
                "searching": false,
                "data": data.incomes,
                "responsive": true,
                order: [[0, 'desc']],
                "columns": [
                    {
                        "render": function(data, type, row, meta) {
                            return row.incomeDate;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.amount;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.concept;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return '<a href="#" data-id="'+row.id+'" class="btn btn-xs btn-danger removeIncome"><i class="fas fa-trash"></i></a>';
                        }
                    }
                ]
            });       
        }

        async function fetchSalesCash() {
            const response = await fetch("/report/payboxsales?payboxid=" + _payBoxId + "&withcash=0", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch sales cash");       
            }                    
            const data = await response.json();
            _titleSalesCash.text("Ventas al contado: S/ " + data.totalSales);
            setTotalIncome(data.totalSales);
            _zcashSales.val(data.totalSales);
            _tdCash.html(data.totalSales);
            _dtSalesCash.DataTable().destroy();
            _dtSalesCash.DataTable({
                "paging": false,
                "info": false,
                "searching": false,
                "data": data.sales,
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
                            return row.dateUpdate;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.table;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.total;
                        }
                    }
                ]
            });  
        }

        async function fetchSalesCard() {
            const response = await fetch("/report/payboxsales?payboxid=" + _payBoxId + "&withcash=1", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch sales card: ");       
            }                    
            const data = await response.json();
            _titleSalesCard.text("Ventas con tarjeta: S/ " + data.totalSales);
            _zcardSales.val(data.totalSales);
            _tdCard.html(data.totalSales);
            _dtSalesCard.DataTable().destroy();
            _dtSalesCard.DataTable({
                "paging": false,
                "info": false,
                "searching": false,
                "data": data.sales,
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
                            return row.dateUpdate;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.table;
                        }
                    },
                    {
                        "render": function(data, type, row, meta) {
                            return row.total;
                        }
                    }
                ]
            });  
        }

        async function fetchTips(){
            const response = await fetch("/tipspercent/list", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch tips");       
            }                    
            const data = await response.json();
            _ds = data.list;
            
            totalTips = _totalTips.val();

            for($i = 0; $i < _ds.length; $i++) {
                percent = (totalTips * _ds[$i].percent) / 100
                addRow(_ds[$i].employ, percent);
            }

        }

        function addRow(employ, percent) {
            let table = document.getElementById("tableBody");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            
            c1.innerText = employ;
            c2.innerText = percent.toFixed(2);
                        
            row.appendChild(c1);
            row.appendChild(c2);
            
            table.appendChild(row);
        }
        
        function setTotalIncome(data) {
            totalIncome = totalIncome + parseFloat(data);
            _totalIncome.text(totalIncome.toFixed(2));
        }
        
        function setTotalExpense(data) {
            totalExpense = totalExpense + parseFloat(data);
            _totalExpense.text(totalExpense.toFixed(2));
        }

        function setFinalBalance(){
            let finalBalance = totalIncome - totalExpense; 
            _finalBalance.val(finalBalance.toFixed(2));
        }

        function closePayBox(){
            let route = "{{ route('paybox.close') }}";
            let data = getFormParams('frmClosePayBox');
            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    showSuccessMsg(result.message);
                    setTimeout(function(){
                        window.location = "/paybox/detail/" + _payBoxId;
                    }, 1500);
                }
                if(result.status=="error"){
                    showErrorMsg(result.message);
                }
            });
        }
    </script>
@stop
