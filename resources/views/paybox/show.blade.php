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
    <div class="row mt-3">
        <div class="col">
            <h5>Responsable: {{ $paybox->userName }}</h5>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-paybox-tab" data-toggle="pill"
                                href="#custom-tabs-four-paybox" role="tab"
                                aria-controls="custom-tabs-four-paybox" aria-selected="True">Cierre de Caja</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-income-tab" data-toggle="pill"
                                href="#custom-tabs-four-income" role="tab"
                                aria-controls="custom-tabs-four-income" aria-selected="false">Ingresos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-expense-tab" data-toggle="pill"
                                href="#custom-tabs-four-expense" role="tab"
                                aria-controls="custom-tabs-four-expense" aria-selected="false">Gastos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-cash-tab" data-toggle="pill"
                                href="#custom-tabs-four-cash" role="tab"
                                aria-controls="custom-tabs-four-cash" aria-selected="false">Ventas al contado</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-card-tab" data-toggle="pill"
                                href="#custom-tabs-four-card" role="tab"
                                aria-controls="custom-tabs-four-card" aria-selected="false">Ventas con tarjeta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-yape-tab" data-toggle="pill"
                                href="#custom-tabs-four-yape" role="tab"
                                aria-controls="custom-tabs-four-yape" aria-selected="false">Ventas con Yape/Plin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-porcobrar-tab" data-toggle="pill"
                                href="#custom-tabs-four-porcobrar" role="tab"
                                aria-controls="custom-tabs-four-porcobrar" aria-selected="false">Ventas por cobrar</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <input type="hidden" name="payboxId" id="payboxId" value="{{$paybox->id}}">
                    <input type="hidden" name="payboxState" id="payboxState" value="{{$paybox->state}}">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-paybox" role="tabpanel"
                            aria-labelledby="custom-tabs-four-paybox-tab">
                            
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
                                            
                                            <div class="row mt-4">
                                                <div class="col-12 mb-2"><h5>Resumen de Caja (efectivo)</h5></div>
                                                <div class="col">
                                                    <div class="info-box bg-gradient-success">
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-center small">Saldo Final</span>
                                                            <span id="rCash" class="info-box-number text-center">s/ 0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="info-box bg-gradient-info">
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-center small">Prop. Tarjet a Efectivo</span>
                                                            <span id="rTip1" class="info-box-number text-center">s/ 0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="info-box bg-gradient-primary">
                                                        <div class="info-box-content">
                                                            <span class="info-box-text text-center small">Prop. Efectivo</span>
                                                            <span id="rTip2" class="info-box-number text-center">s/ 0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-right">
                                                   <h6>Total Efectivo  <span id="rTotal">S/0.00</span></h6>
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
                                            <input type="hidden" id="saldoInit" value="{{$paybox->cashBalance}}">  
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
                                                    <tr>
                                                        <td>Ventas con Yape/Plin</td>
                                                        <td id="tdYape" style="color:#dc3545">0.00</td>
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
                                                    <tr>
                                                        <td>Otros Gastos</td>
                                                        <td id="tdExpenseOtherPay">0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Prop. Tarj. a Efectivo</td>
                                                        <td id="tdTipsToCash">0.00</td>
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
                                            <input type="hidden" value="{{$totalCard}}" id="totalTipsCard" />
                                            <input type="hidden" value="{{$totalCash}}" id="totalTipsCash" />
                                            <strong><span class="text-secondary" style="display: block"><i class="fas fa-coins mr-1"></i> Propinas S/ {{$totalTips}}</span></strong>
                                            <span class="text-secondary">Efectivo: {{$totalCash}}</span> --- <span class="text-secondary">Tarjeta: {{$totalCard}}</span>
                                            <table class="table table-sm mt-2">
                                                <tbody id="tbTips">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-income" role="tabpanel"
                            aria-labelledby="custom-tabs-four-income-tab">
                            
                            <div class="row">
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">Ingresos</h3>
                                            <div class="card-tools">
                                                @if($paybox->state==1)
                                                    <button id="newIncome" type="button" class="btn btn-success">+ Nuevo</button>
                                                @endif    
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table id="dtIncome" class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width:120px">Fecha</th>
                                                        <th style="width:100px">Concepto</th>
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
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-expense" role="tabpanel"
                            aria-labelledby="custom-tabs-four-expense-tab">
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">Gastos</h3>
                                            <div class="card-tools">
                                                @if($paybox->state==1)
                                                    <button id="newExpense" type="button" class="btn btn-danger">+ Nuevo</button>
                                                @endif    
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table id="dtExpense" class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width:120px">Fecha</th>
                                                        <th style="width:100px">Tipo</th>
                                                        <th style="width:100px">Se Pago</th>
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

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-cash" role="tabpanel"
                            aria-labelledby="custom-tabs-four-cash-tab">

                            <div class="row">
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">Ventas al contado</h3>
                                            <div class="card-tools">
                                                <label id="ltotalCash">Total S/ 0.00</label>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table id="dtCash" class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width:80px">Nro</th>
                                                        <th style="width:120px">Fecha</th>
                                                        <th style="width:80px">Mesa</th>
                                                        <th style="width:80px">S/</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbCash">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-card" role="tabpanel"
                            aria-labelledby="custom-tabs-four-card-tab">

                            <div class="row">
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">Ventas con tarjeta</h3>
                                            <div class="card-tools">
                                                <label id="ltotalCard">Total S/ 0.00</label>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table id="dtCard" class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width:80px">Nro</th>
                                                        <th style="width:120px">Fecha</th>
                                                        <th style="width:80px">Mesa</th>
                                                        <th style="width:80px">S/</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbCard">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-yape" role="tabpanel"
                            aria-labelledby="custom-tabs-four-yape-tab">

                            <div class="row">
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">Ventas con Yape/Plin</h3>
                                            <div class="card-tools">
                                                <label id="ltotalYape">Total S/ 0.00</label>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table id="dtYape" class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width:80px">Nro</th>
                                                        <th style="width:120px">Fecha</th>
                                                        <th style="width:80px">Mesa</th>
                                                        <th style="width:80px">S/</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbYape">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-porcobrar" role="tabpanel"
                            aria-labelledby="custom-tabs-four-porcobrar-tab">

                            <div class="row">
                                <div class="col-6">
                                    <div class="card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">Ventas por cobrar</h3>
                                            <div class="card-tools">
                                                <label id="ltotalPorCobrar">Total S/ 0.00</label>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0">
                                            <table id="dtPorCobrar" class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th style="width:80px">Nro</th>
                                                        <th style="width:120px">Fecha</th>
                                                        <th style="width:80px">Mesa</th>
                                                        <th style="width:100px">Cliente</th>
                                                        <th style="width:80px">S/</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbPorCobrar">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>

    @include('paybox.income')
    @include('paybox.expense')
@stop

@section('css')
    <link rel="stylesheet" href="/vendor/admin/main.css">
    <style>
        div.dataTables_wrapper {width: 100%;} 

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
    <script>
        const _token = document.head.querySelector("[name~=csrf-token][content]").content;

        let _incomeModal = $("#incomeModal");
        let _incomeModalTitle = $("#incomeModalTitle"); 
        let _payboxIncomeId = $("#payboxIncomeId");
        let _incomeconceptId = $("#incomeconceptId");
        let _income = $("#income");
        let _description1 = $("#description1");
        let _addIncome = $("#addIncome");
        let _newIncome = $("#newIncome");
        let _tbIncome = $("#tbIncome");
        let _dtIncome = $("#dtIncome");
        let _dsIncome = null;

        let _modalExpense = $("#modalExpense");
        let _payboxExpenseId = $("#payboxExpenseId");
        let _modalExpenseTitle = $("#modalExpenseTitle");
        let _expenseType = $("#expenseType");
        let _staffPayType = $("#staffPayType");
        let _providerId = $("#providerId");
        let _serviceId = $("#serviceId");
        let _staffId = $("#staffId");
        let _otherPayId = $("#otherPayId");
        let _expense = $("#expense");
        let _voucherType = $("#voucherType");
        let _voucherNumber = $("#voucherNumber");
        let _description2 = $("#description2");
        let _addPayBoxExpense = $("#addPayBoxExpense");

        let _pProvider = $("#pProvider");
        let _pService = $("#pService");
        let _pStaff = $("#pStaff");
        let _dStaff = $("#dStaff");
        let _pOtherPay = $("#pOtherPay");
        let _dVoucher = $("#dVoucher");
        let _newExpense = $("#newExpense");
        let _tbExpense = $("#tbExpense");
        let _dtExpense = $("#dtExpense");
        let _dsExpense = null;

        let _tdIncome = $("#tdIncome");
        let _totalExpenses = $("#totalExpenses");

        let _zcashSales = $("#zcashSales");
        let _zcardSales = $("#zcardSales");
        let _zincome = $("#zincome");
        let _zexpenses = $("#zexpenses");
        let _finalBalance = $("#finalBalance");
        let _cashRegister = $("#cashRegister");
        let _missingBalance = $("#missingBalance");
        let _leftoverBalance = $("#leftoverBalance");

        let totalIncome = 0.00;
        let totalExpense = 0.00;
        let totalTip1 = 0.00;
        
        $(document).ready(function() {

            _finalBalance.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");
            _cashRegister.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");
            _missingBalance.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");
            _leftoverBalance.inputFilter(function(value) {return /^-?\d*[.,]?\d*$/.test(value);}, "Ingrese el monto");

            _pProvider.hide();
            _pService.hide();
            _pStaff.hide();
            _pOtherPay.hide();
            _dVoucher.hide();

            fetchPayBoxIncome();

            fetchPayBoxExpense();

            fetchSalesCash();

            fetchSalesCard();

            fetchSalesYape();

            fetchSalesPorCobrar();

            fetchTips();

            _cashRegister.on("change", function(e){
                _zincome.val("");
                _zexpenses.val("");
            });

            $("#verifyBalance").on("click", function(e){
                e.preventDefault();
                _missingBalance.val("");
                _leftoverBalance.val("");
                setFinalBalance();
                if(_cashRegister.val()!="") {
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

            _newIncome.on("click", function() {
                clearFormIncome();
                _incomeModalTitle.text("Agregar Ingreso");
                _incomeModal.modal("show");
            });

            _addIncome.on("click", function(e) {
                e.preventDefault();
                let elements = [
                    ['incomeconceptId', 'Seleccione el concepto'],
                    ['income', 'Ingrese el monto']
                ];

                if(emptyfy(elements)) {
                    let payboxIncomeId = _payboxIncomeId.val();
                    
                    let route = "{{ route('payboxincome.add') }}";
                    if(payboxIncomeId!="") {
                        route = "{{ route('payboxincome.edit') }}";
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
                            setTimeout(function(){
                                window.location.reload();
                            }, 700);
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });

            _dtIncome.on('click', '.editItem', function (e) {
                e.preventDefault();
                let index = $(this).data('index');
                let rw = _dsIncome[index];
                with (rw) {
                    _payboxIncomeId.val(id);
                    _incomeconceptId.val(incomeconceptId).change();
                    _income.val(income);
                    _description1.val(description);
                }
                _incomeModalTitle.text("Editar Ingreso");
                _incomeModal.modal("show");
            });

            _dtIncome.on('click', '.removeItem', function (e) {
                e.preventDefault();
                let payboxState = $("#payboxState").val();
                if(payboxState==2){
                    showWarningMsg('No puedes eliminar un ingreso cuando la caja se encuentra cerrada');
                    return;
                }

                let payboxIncomeId = $(this).data('id');
                Swal.fire({
                    title: "Atención",
                    text: "Deseas eliminar el Ingreso?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("/payboxincome/remove/" + payboxIncomeId, {
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
                                setTimeout(function(){
                                    window.location.reload();
                                }, 700);
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });
            });

            _newExpense.on("click", function() {
                clearFormExpense();
                _modalExpenseTitle.text("Agregar Gasto");
                _modalExpense.modal("show");
            })

            _addPayBoxExpense.on("click", function(e) {
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
                ];

                if(emptyfy(elements)) {
                    let payboxExpenseId = _payboxExpenseId.val();
                    
                    let route = "{{ route('payboxexpense.add') }}";
                    if(payboxExpenseId!="") {
                        route = "{{ route('payboxexpense.edit') }}";
                    }

                    let data = getFormParams('frmAddPosExpense');
                    fetch(route, {
                        method: 'post',
                        body: data,
                    })
                    .then(response => response.json())
                    .then(result => {
                        if(result.status=="success"){
                            _modalExpense.modal('hide');
                            showSuccessMsg(result.message);
                            setTimeout(function(){
                                window.location.reload();
                            }, 700);
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    })
                }
            })

            _dtExpense.on('click', '.editItem', function (e) {
                e.preventDefault();
                let index = $(this).data('index');
                let rw = _dsExpense[index];
                with (rw) {
                    _payboxExpenseId.val(id);
                    _expense.val(expense);
                    _description2.val(description);
                    
                    _expenseType.val(expenseType).change();
                    _providerId.val(providerId).change();
                    _voucherType.val(voucherType).change();
                    _voucherNumber.val(voucherNumber);
                    _serviceId.val(serviceId).change();
                    _otherPayId.val(otherPayId).change();
                    _staffId.val(staffId).change();
                    _staffPayType.val(staffPayType).change();
                    
                }
                _modalExpenseTitle.text("Editar Gasto");
                _modalExpense.modal('show');
            });

            _dtExpense.on('click', '.removeItem', function (e) {
                e.preventDefault();
                let payboxState = $("#payboxState").val();
                if(payboxState==2){
                    showWarningMsg('No puedes eliminar un gasto cuando la caja se encuentra cerrada');
                    return;
                }
                let payboxExpenseId = $(this).data('id');
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
                        fetch("/payboxexpense/remove/" + payboxExpenseId, {
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
                                setTimeout(function(){
                                    window.location.reload();
                                }, 700);
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        });
                    }
                });
            });
        });

        async function fetchPayBoxIncome() {
            let payBoxId = $("#payboxId").val();
            const response = await fetch("/payboxincome/list/" + payBoxId, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch payboxincome");       
            }                    
            const data = await response.json();
            _dsIncome = data.list;
            let _ds = data.list;
            let totalIncome = 0.00;
            _tbIncome.empty();
            for($i = 0; $i < _ds.length; $i++) {
                totalIncome += parseFloat(_ds[$i].income);
                dr = _ds[$i]; 
                addRowIncome(dr.incomeDate, dr.income, dr.id, $i, dr.incomeConcept);
            }

            $('#tdIncome').html(totalIncome.toFixed(2));
            setTotalIncome(totalIncome);            
        }

        function addRowIncome(vdate, vincome, vid, vindex, vincomeConcept) {
            let table = document.getElementById("tbIncome");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            
            c1.innerText = getOnlytHour(vdate);
            c2.innerText = vincomeConcept;
            c3.innerText = vincome;
            c4.innerHTML = '<a href="#" data-index="'+vindex+'" class="btn btn-xs btn-info editItem"><i class="far fa-edit"></i></a> <a href="#" data-id="'+vid+'" class="btn btn-xs btn-danger removeItem"><i class="far fa-trash-alt"></i></a>';
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
            
            table.appendChild(row);
        }

        async function fetchPayBoxExpense() {
            let payBoxId = $("#payboxId").val();
            const response = await fetch("/payboxexpense/list/" + payBoxId, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch payboxexpense");       
            }                    
            const data = await response.json();
            _dsExpense = data.list;
            let _ds = data.list;
            let totalProvider = 0.00;
            let totalService = 0.00;
            let totalStaff = 0.00;
            let totalOtherPay = 0.00;
            let totalExpenses = 0.00;
            _tbExpense.empty();
            for($i = 0; $i < _ds.length; $i++) {
                dr = _ds[$i]; 
                who = "";
                totalExpenses += parseFloat(dr.expense);
                if(dr.expenseType==1){
                    who = dr.provider;
                    totalProvider += parseFloat(dr.expense);
                }
                if(dr.expenseType==2){
                    who = dr.service;
                    totalService += parseFloat(dr.expense);
                }
                if(dr.expenseType==3){
                    who = dr.staff;
                    totalStaff += parseFloat(dr.expense);
                }
                if(dr.expenseType==4){
                    who = dr.motive;
                    totalOtherPay += parseFloat(dr.expense);    
                }
                addRowExpense(dr.expenseDate, dr.expense, dr.id, $i, dr.expenseType, who, dr.staffPayType);
            }

            $("#tdExpenseProvider").html(totalProvider.toFixed(2));
            $("#tdExpenseService").html(totalService.toFixed(2));
            $("#tdExpenseStaff").html(totalStaff.toFixed(2));
            $("#tdExpenseOtherPay").html(totalOtherPay.toFixed(2));

            setTotalExpense(totalExpenses);
        }

        function addRowExpense(vdate, vexpense, vid, vindex, vexpenseType, vwho, vstaffPayType) {
            let table = document.getElementById("tbExpense");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            let c5 = document.createElement("td");
            
            c1.innerText = getOnlytHour(vdate);
            c2.innerHTML = getExpenseType(vexpenseType) + getStaffPayType(vstaffPayType);
            c3.innerText = vwho;
            c4.innerText = vexpense;
            c5.innerHTML = '<a href="#" data-index="'+vindex+'" class="btn btn-xs btn-info editItem"><i class="far fa-edit"></i></a> <a href="#" data-id="'+vid+'" class="btn btn-xs btn-danger removeItem"><i class="far fa-trash-alt"></i></a>';
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
            row.appendChild(c5);
            
            table.appendChild(row);
        }

        function clearFormIncome(){
            _payboxIncomeId.val("");
            _incomeconceptId.val("").change();
            _income.val("");
            _description1.val("");
        }

        function clearFormExpense() {
            _payboxExpenseId.val("");
            _expense.val("");
            _description2.val("");

            _providerId.val("0").change();
            _serviceId.val("").change();
            _staffId.val("").change();
            _otherPayId.val("").change();
            
            _expenseType.val(1).change();
            _voucherType.val(0).change();
            _voucherNumber.val("");

            _staffPayType.val(0).change();
        }

        async function fetchSalesCash() {
            let payBoxId = $("#payboxId").val();
            const response = await fetch("/report/payboxsales?payboxid=" + payBoxId + "&withcash=0", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch sales cash");       
            }                    
            const data = await response.json();
            $('#tdCash').html(data.totalSales);
            $('#ltotalCash').html('S/ ' + data.totalSales);
            let _ds = data.sales;
            $("#tbCash tbody").empty();
            let totalIncome = 0.00;
            for($i = 0; $i < _ds.length; $i++) {
                totalIncome += parseFloat(_ds[$i].total);
                dr = _ds[$i]; 
                addRowCash(dr.dateUpdate, dr.total, dr.id, $i, dr.table);
            }
            _zcashSales.val(data.totalSales);
            setTotalIncome(totalIncome);            
        }

        function addRowCash(vdate, vamount, vid, vindex, vtable) {
            let table = document.getElementById("tbCash");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            
            c1.innerText = vid;
            c2.innerText = getOnlytHour(vdate);
            c3.innerText = vtable;
            c4.innerHTML = vamount;
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
                        
            table.appendChild(row);
        }

        async function fetchSalesCard() {
            let payBoxId = $("#payboxId").val();
            const response = await fetch("/report/payboxsales?payboxid=" + payBoxId + "&withcash=1", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch sales card");       
            }                    
            const data = await response.json();
            $('#tdCard').html(data.totalSales);
            $('#ltotalCard').html('S/ ' + data.totalSales);
            let _ds = data.sales;
            $("#tbCard tbody").empty();
            for($i = 0; $i < _ds.length; $i++) {
                dr = _ds[$i]; 
                addRowCard(dr.dateUpdate, dr.total, dr.id, $i, dr.table);
            }

            let saldoInit = $('#saldoInit').val();
            setTotalIncome(saldoInit);
            _zcardSales.val(data.totalSales);   
        }

        function addRowCard(vdate, vamount, vid, vindex, vtable) {
            let table = document.getElementById("tbCard");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            
            c1.innerText = vid;
            c2.innerText = getOnlytHour(vdate);
            c3.innerText = vtable;
            c4.innerHTML = vamount;
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
                        
            table.appendChild(row);
        }

        async function fetchTips(){
            const response = await fetch("/tipspercent/list", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch tips");       
            }                    
            const data = await response.json();
            _ds = data.list;
            
            totalTips = $('#totalTips').val();

            for($i = 0; $i < _ds.length; $i++) {
                percent = (totalTips * _ds[$i].percent) / 100
                addRowTips(_ds[$i].employ, percent);
            }

            let totalTipsCard = $('#totalTipsCard').val();
            $('#tdTipsToCash').html(totalTipsCard);

            totalTip1 = totalTipsCard;
            
            setTotalExpense(totalTipsCard);
        }

        function addRowTips(employ, percent) {
            let table = document.getElementById("tbTips");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            
            c1.innerText = employ;
            c2.innerText = percent.toFixed(2);
                        
            row.appendChild(c1);
            row.appendChild(c2);
            
            table.appendChild(row);
        }

        async function fetchSalesYape() {
            let payBoxId = $("#payboxId").val();
            const response = await fetch("/report/payboxsales?payboxid=" + payBoxId + "&withcash=2", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch sales yape");       
            }                    
            const data = await response.json();
            $('#tdYape').html(data.totalSales);
            $('#ltotalYape').html('S/ ' + data.totalSales);
            let _ds = data.sales;
            $("#tbYape tbody").empty();
            for($i = 0; $i < _ds.length; $i++) {
                dr = _ds[$i]; 
                addRowYape(dr.dateUpdate, dr.total, dr.id, $i, dr.table);
            }
        }

        function addRowYape(vdate, vamount, vid, vindex, vtable) {
            let table = document.getElementById("tbYape");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            
            c1.innerText = vid;
            c2.innerText = getOnlytHour(vdate);
            c3.innerText = vtable;
            c4.innerHTML = vamount;
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
                        
            table.appendChild(row);
        }

        async function fetchSalesPorCobrar() {
            let payBoxId = $("#payboxId").val();
            const response = await fetch("/report/salesporcobrar?payboxid=" + payBoxId + "&withcash=3", {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch sales por cobrar");       
            }                    
            const data = await response.json();
            $('#ltotalPorCobrar').html('S/ ' + data.totalSales);
            let _ds = data.sales;
            $("#tbPorCobrar tbody").empty();
            for($i = 0; $i < _ds.length; $i++) {
                dr = _ds[$i]; 
                addRowPorCobrar(dr.dateUpdate, dr.total, dr.id, $i, dr.table, dr.client);
            }
        }

        function addRowPorCobrar(vdate, vamount, vid, vindex, vtable, vclient) {
            let table = document.getElementById("tbPorCobrar");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            let c5 = document.createElement("td");
            
            c1.innerText = vid;
            c2.innerText = getOnlytHour(vdate);
            c3.innerText = vtable;
            c4.innerText = vclient;
            c5.innerHTML = vamount;
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
            row.appendChild(c4);
            row.appendChild(c5);
                        
            table.appendChild(row);
        }

        function setTotalIncome(data) {
            totalIncome = totalIncome + parseFloat(data);
            $('#totalIncome').html(totalIncome.toFixed(2));
        }

        function setTotalExpense(data) {
            totalExpense = totalExpense + parseFloat(data);
            $('#totalExpenses').html(totalExpense.toFixed(2));
        }

        function setFinalBalance(){
            let finalBalance = totalIncome - totalExpense; 
            _finalBalance.val(finalBalance.toFixed(2));

            $("#rCash").html("S/" + finalBalance.toFixed(2));

            $('#rTip1').html("S/" + totalTip1);

            let tcash = $("#totalTipsCash").val();
            $("#rTip2").html("S/" + tcash);
            
            let totalresumen = 0.00;
            totalresumen = parseFloat(finalBalance) + parseFloat(tcash) + parseFloat(totalTip1);
            $("#rTotal").html("S/" + totalresumen.toFixed(2));
        }

        function closePayBox(){
            let payBoxId = $("#payboxId").val();
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
                        window.location = "/paybox/show/" + payBoxId;
                    }, 1500);
                }
                if(result.status=="error"){
                    showErrorMsg(result.message);
                }
            });
        }
    </script>
@stop
