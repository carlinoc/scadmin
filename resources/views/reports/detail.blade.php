@extends('adminlte::page')

@section('title', 'Pedido')

@section('content_header')
<div class="row">
    <div class="col-md-auto">
        <h1>Pedido:</h1>    
    </div>
    <div class="col">
        @php
        $url = explode("/", $_SERVER['HTTP_REFERER']);
        $back = "/report/lastorders";
        if($url[4]=="sales"){
            $back = "/report/sales";
        }
        @endphp
        <a href="{{$back}}" class="btn btn-outline-dark" role="button">Atras</a>
    </div>
</div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" id="backUrl" name="backUrl" value="{{$back}}">
                    <input type="hidden" id="payBoxState" name="payBoxState" value="{{$sale->payboxState}}">
                    <div class="row">
                        <div class="col-sm-4">
                            <p class="h5 text-success">Numero: <b>{{$sale->saleId}}</b></p>
                        </div>
                        <div class="col-sm-4">
                            <label class="h5 text-info mr-2" id="tableName">Mesa: <b>{{$sale->table}}</b></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="saleId" id="saleId" value="{{$sale->saleId}}">
                            <button type="button" id="addProduct" class="btn btn-success " style="font-weight: bold;">+ Agregar Producto</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-4">
                            <p class="h5 text-warning"><b>{{date_format($sale->updated_at,"d-m-Y g:i A")}}</b></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="h5 text-danger" id="pTotal">Total: S/ <b>0.00</b></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted">Atendio: {{ $sale->user }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <table id="dtSalesDetail" class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Total</th>
                                <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sumTotal = 0 ?>
                                @foreach($salesDetails as $key=>$saleDetail)
                                <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <td>{{ $saleDetail->product }}</td>
                                    <td>s/ {{ $saleDetail->price }}</td>
                                    <td>{{ $saleDetail->quantity }}</td>
                                    <td>s/ {{ $saleDetail->total }}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-info edit_product" 
                                            data-id="{{ $saleDetail->id }}"
                                            data-pid="{{ $saleDetail->productId }}"
                                            data-price="{{ $saleDetail->price }}"
                                            data-quantity="{{ $saleDetail->quantity }}"
                                            data-total="{{ $saleDetail->total }}"
                                            >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="" data-id="{{ $saleDetail->id }}" class="btn btn-sm btn-danger remove_product"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php $sumTotal += $saleDetail->total ?>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="hidden" name="sumTotal" id="sumTotal" value="{{ $sumTotal }}" />
                    </div>
                    <form action="" method="POST" id="frmSendTicket">    
                    @csrf
                    <input type="hidden" id="saleId" name="saleId" value="{{$sale->saleId}}">
                    <div class="row">
                        <div class="col">
                            <x-adminlte-select2 id="clientId" name="clientId" label-class="text-lightblue" data-placeholder="Cliente">
                                <option value=""></option>
                                @foreach($clients as $client)
                                    @if($client->id == $sale->clientId)
                                        <option value="{{$client->id}}" selected data-level="{{$client->level}}" >{{$client->name}}</option>
                                    @else
                                        <option value="{{$client->id}}" data-level="{{$client->level}}" >{{$client->name}}</option>
                                    @endif
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-auto">
                            <button type="button" id="newClient" class="btn btn-outline-info btn-sm">Nuevo</button>
                        </div>
                        <div class="col text-center">
                            <label class='checkbox-inline' style="font-size: 16px">Desc. %</label>
                            <select id="discount" name="discount" style="font-size: 16px">
                                <option value="0" @selected("0"==$sale->discount)>0</option>
                                <option value="10" @selected("10"==$sale->discount)>10</option>
                                <option value="20" @selected("20"==$sale->discount)>20</option>
                                <option value="30" @selected("30"==$sale->discount)>30</option>
                                <option value="40" @selected("40"==$sale->discount)>40</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class='checkbox-inline'>Paga con:</label>
                            <select id="withCash" name="withCash" style="font-size: 16px">
                                <option value="0" @selected("0"==$sale->withCash)>Efectivo</option>
                                <option value="1" @selected("1"==$sale->withCash)>Tarjeta</option>
                                <option value="2" @selected("2"==$sale->withCash)>Yape-Plin</option>
                            </select>
                            <div id="posList" class="text-center" style="display: none">
                                <select id="companyPosId" name="companyPosId">
                                    @foreach($companyPosList as $companyPos)
                                        @if($companyPos->id == $sale->companyPosId)
                                            <option value="{{ $companyPos->id }}" selected>{{ $companyPos->pos }}</option>
                                        @else
                                            <option value="{{ $companyPos->id }}">{{ $companyPos->pos }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col">
                            <button id="updateSale" type="button" class="btn btn-secondary" style="font-weight: bold;">Actualizar Venta</button>
                        </div>
                    </div>
                    </form>
                    <div class="row pt-3 border-top">
                        <div class="col-sm-3 text-center">
                            <button id="cancelSale" type="button" class="btn btn-info" style="font-weight: bold;">Anular Venta</button>        
                        </div>
                        <div class="col-sm-3 text-center">
                            <a href="#" id="sendBoleta" class="btn btn-app bg-success" style="font-weight: bold;">
                                <i class="fas fa-ticket-alt"></i> Emitir Boleta
                            </a>
                        </div>
                        <div class="col-sm-3 text-center">
                            <a class="btn btn-app bg-danger" id="sendFactura" style="font-weight: bold;">
                                <i class="fas fa-inbox"></i> Emitir Factura
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <button id="generateTicket" type="button" class="btn btn-primary" style="font-weight: bold;">Generar Ticket</button>
                        </div>
                    </div>
                </div>    
            </div>
        </div> 
    </div> 

    @include('sales.add-modal')
    @include('sales.client')
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _backUrl = $("#backUrl");
    let _payboxState = $("#payBoxState");
    
    let _saleId = $("#saleId");
    let _saleDetailId = $("#saleDetailId");
    let _productId = $("#productId");
    let _price = $("#price");
    let _quantity = $("#quantity");
    let _total = $("#total");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _addProduct = $("#addProduct");
    let _cancelSale = $("#cancelSale");

    let _clientId = $("#clientId");
    let _modalClient = $("#addModalClient");
    let _labelClient = $("#addModalLabelClient");
    let _newClient = $("#newClient");
    let _addClient = $("#addClient");

    let _name = $("#name");
    let _clientType = $("#clientType");
    let _ruc = $("#ruc");
    let _labelRuc = $("#lruc");
    let _dni = $("#dni");
    let _address = $("#address");
    let _labelDni = $("#ldni");
    let _phone = $("#phone");
    let _withCash = $("#withCash");
    let _posList = $("#posList");
    
    $(document).ready(function(){
        let sumTotal = parseFloat($('#sumTotal').val());
        $('#pTotal').html('Total: S/ ' + formatCurrency(sumTotal));

        _withCash.on('change', function(e){
            let value = $(this).val();
            if(value==1){
                _posList.show();
            }else{
                _posList.hide();
            }
        });

        _clientType.on('change', function(e){
            let id = $(this).val();
            if(id==1){
                _labelDni.text('DNI');
                _dni.show();
                _address.hide();
                _name.attr("placeholder", "Nombre del cliente");
                _labelRuc.hide();    
                _ruc.hide();
                _ruc.val('');
                _name.focus();
            }else{
                _dni.hide();
                _address.show();
                _labelDni.text('Dirección');
                _name.attr("placeholder", "Nombre de la empresa");
                _labelRuc.show();
                _dni.val('');
                _ruc.show();
                _ruc.focus();
            }    
        });

        _addClient.on('click', function(e){
            e.preventDefault();
            const elements = [];
            if(_clientType.val()==1){
                elements.push(['name', 'Ingrese el nombre del cliente']);
            }else{
                elements.push(['name', 'Ingrese el nombre de la empresa']);
                elements.push(['ruc', 'Ingrese el RUC de la empresa']);
            }

            if(emptyfy(elements)) {
                let route = "{{ route('client.add') }}";
                let data = getFormParams('frmAddClient');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modalClient.modal('hide');
                        showSuccessMsg(result.message);

                        let name = $('#name').val();
                        let level = $('#level').val();
                        let id = result.clientId;
                        _clientId.append('<option value="' + id + '" data-level="' + level + '">' + name + '</option>');
                        _clientId.val(id).change();
                        $('#discount').val(level).change();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                });
            }
        });

        _clientId.on('change', function(e){
            e.preventDefault();
            let level = $(this).find(':selected').data('level');
            $('#discount').val(level).change();
        });

        _cancelSale.on('click', function(e){
            e.preventDefault();
            if(_payboxState.val() == 2){
                showErrorMsg("La venta se encuentra cerrada");
                return;
            }
            let saleId = _saleId.val();
            Swal.fire({
                title: "Atención",
                text: "Estas seguro de anular la venta",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    fetch("/sale/cancelsale/" + saleId, {
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
                                window.location = _backUrl.val();
                            }, 2000);
                        }
                        if(result.status=="error"){
                            showErrorMsg(result.message);
                        }
                    });
                }
            });
        });

        _addProduct.on('click', function(e) {
            e.preventDefault();
            if(_payboxState.val() == 2){
                showErrorMsg("La venta se encuentra cerrada");
                return;
            }
            clearFormProduct();
            _modalLabel.text("Agregar Producto");
            _modal.modal('show');
            setTimeout(function(){
                _productId.focus();
            }, 300);
        });

        _newClient.on('click', function(e){
            e.preventDefault();
            clearFormClient();
            _modalClient.modal('show');
            _clientType.val(1).change();
        });

        $('#updateSale').on('click', function(e) {
            e.preventDefault();
            if(_payboxState.val() == 2){
                showErrorMsg("La venta se encuentra cerrada");
                return;
            }
            let route = "{{ route('sales.update') }}";
            let data = getFormParams('frmSendTicket');
            data.append('saveHistory', 1);
            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    window.location = _backUrl.val();
                }
                if(result.status=="error"){
                    showErrorMsg(result.message);
                }
            });
        });
        
        $('#generateTicket').on('click', function(e) {
            e.preventDefault();
            let route = "{{ route('sales.sendticket') }}";
            let data = getFormParams('frmSendTicket');
            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    window.location = _backUrl.val();
                }
                if(result.status=="error"){
                    showErrorMsg(result.message);
                }
            });
        });
        
        $('#sendBoleta').on('click', function(e) {
            e.preventDefault();
            let route = "{{ route('sales.sendboleta') }}";
            let data = getFormParams('frmSendTicket');
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
            });
        });

        $('#sendFactura').on('click', function(e) {
            e.preventDefault();
            let route = "{{ route('sales.sendfactura') }}";
            let data = getFormParams('frmSendTicket');
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
            });
        });

        $('#productId').on('select2:select', function (e) {
            var price = $('#productId :selected').attr('data-price');
            var quantity = $('#quantity :selected').val();
            var total = price * quantity;
            $('#price').val(price);
            $('#total').val((Math.round(total * 100) / 100).toFixed(2));
        });

        $('#quantity').on('change', function() {
            var price = $('#productId :selected').attr('data-price');
            if(typeof price != 'undefined'){
                var quantity = this.value;
                var total = price * quantity;
                $('#total').val((Math.round(total * 100) / 100).toFixed(2));
            }
        });

        $('#addNewProduct').on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['productId', 'Seleccione un producto'],
                ['price', 'Ingrese el precio'],
                ['total', 'Ingrese el total']
            ];

            if(emptyfy(elements)) {
                let saleId = _saleId.val();
                let saleDetailId = _saleDetailId.val();
                
                let route = "{{ route('salesdetail.add') }}";
                if(saleDetailId!="") {
                    route = "{{ route('salesdetail.edit') }}";
                }

                let data = getFormParams('frmAddProduct');
                data.append('saveHistory', 1);
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modal.modal('hide');
                        window.location = "/report/detail/"+saleId;
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                });
            }
        });

        $(document).on('click', '.remove_product', function(e){
            e.preventDefault();
            if(_payboxState == 2){
                showErrorMsg("La venta se encuentra cerrada");
                return;
            }
            let saleDetailId = $(this).data('id');
            let saleId = $('#saleId').val();
            fetch("/salesdetail/remove/" + saleDetailId + "/1", {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": _token
                }
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    window.location = "/report/detail/"+saleId;
                }
            });
        });

        $(document).on('click', '.edit_product', function(e){
            e.preventDefault();
            if(_payboxState == 2){
                showErrorMsg("La venta se encuentra cerrada");
                return;
            }
            let id=$(this).data('id');
            let pId=$(this).data('pid');
            let price=$(this).data('price');
            let quantity=$(this).data('quantity');
            let total=$(this).data('total');

            _saleDetailId.val(id);
            _productId.val(pId).change();
            _productId.prop('disabled', true);
            _price.val(price);
            _quantity.val(quantity).change();
            _total.val(total);

            _modalLabel.text("Editar Producto");
            _modal.modal('show');
        });

        _withCash.val({{$sale->withCash}}).change();
    });
    
    function clearFormProduct(){
        _saleDetailId.val('');
        _productId.val('').change();
        _productId.prop('disabled', false);
        _price.val('');
        _quantity.val('1').change();
        _total.val('');
    }

    function clearFormClient(){
        _ruc.val('');
        _name.val('');
        _address.val('');
        _dni.val('');
        _phone.val('');
    }
</script>    
@stop