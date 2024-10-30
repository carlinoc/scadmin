@extends('adminlte::page')

@section('title', 'Pedido')

@section('content_header')
<div class="row">
    <div class="col-md-auto">
        <h1>Pedido:</h1>    
    </div>
    <div class="col">
        <a href="{{route('sales.available')}}" class="btn btn-outline-dark" role="button">Atras</a>
    </div>
</div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <p class="h5 text-success">Numero: <b>{{$sale->saleId}}</b></p>
                        </div>
                        <div class="col-sm-4">
                            <label class="h5 text-info mr-2" id="tableName">Mesa: <b>{{$sale->table}}</b></label>
                            <button type="button" id="changeTable" class="btn btn-outline-info btn-sm">Cambiar</button>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="saleId" id="saleId" value="{{$sale->saleId}}">
                            <button type="button" id="addProduct" class="btn btn-success " style="font-weight: bold;">+ Agregar Producto</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-4">
                            <p class="h5 text-warning"><b>{{date_format($sale->updated_at, "d M Y g:i A")}}</b></p>
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
                                        @if($saleDetail->printOrder == 0 && $sale->printOrder == 1)
                                            {{-- <a href="" data-id="{{ $saleDetail->id }}" class="btn btn-sm btn-secondary print_item"><i class="fas fa-print"></i></a> --}}
                                            <input type="checkbox" data-id="{{ $saleDetail->id }}" class="ml-2 printmore">     
                                        @endif
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
                            <x-adminlte-select2 id="clientId" name="clientId" label-class="text-lightblue" data-placeholder="Seleccione un Cliente">
                                <option value=""></option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}" data-discount="{{$client->discount}}" >{{$client->name}}</option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-auto">
                            <button type="button" id="newClient" class="btn btn-outline-info btn-sm">Nuevo</button>
                        </div>
                        <div class="col text-center">
                            <label class='checkbox-inline' style="font-size: 16px">Desc. %</label>
                            <select id="discount" name="discount" style="font-size: 16px">
                                <option value="0">0</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class='checkbox-inline' style="font-size: 16px">Paga con:</label>
                            <select id="withCash" name="withCash" style="font-size: 16px">
                                <option value="0">Efectivo</option>
                                <option value="1">Tarjeta</option>
                                <option value="2">Yape - Plin</option>
                                <option value="3">Por Pagar</option>
                            </select>
                            <select id="companyPosId" name="companyPosId" style="display: none">
                                @foreach($companyPosList as $companyPos)
                                    <option data-main="{{ $companyPos->mainPos }}" value="{{ $companyPos->id }}">{{ $companyPos->pos }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    </form>
                    <form action="" method="POST" id="frmRePrint">
                        @csrf
                        <input type="hidden" id="saleId" name="saleId" value="{{$sale->saleId}}">
                        <input type="hidden" id="ids" name="ids">
                    </form>
                    <div class="row pt-3 border-top">
                        <div class="col-sm-4">
                            <button id="clearTable" type="button" class="btn btn-info" style="font-weight: bold;">Desocupar Mesa</button>
                        </div>
                        <div class="col text-center">
                            <button id="generateOrder" type="button" class="btn btn-secondary" style="font-weight: bold;">Imprimir Comanda</button>
                        </div>
                        <div class="col text-center">
                            @if($sale->printOrder == 0)
                                <button id="generateTicket" type="button" class="btn btn-primary" disabled style="font-weight: bold;">Imprimir Ticket</button>
                            @else
                                <button id="generateTicket" type="button" class="btn btn-primary" style="font-weight: bold;">Imprimir Ticket</button>    
                            @endif    
                        </div>
                    </div>    
                </div>    
            </div>
        </div> 
    </div> 

    @include('sales.add-modal')
    @include('sales.change-table')
    @include('sales.client')
@stop

@section('css')
<link rel="stylesheet" href="/vendor/admin/main.css">
@stop

@section('js')
<script src="/vendor/admin/main.js"></script>
<script>
    const _token = document.head.querySelector("[name~=csrf-token][content]").content;

    let _changeTable = $('#changeTable');
    let _modalTable = $("#addModalTable");
    let _btnChangeTable = $("#btnChangeTable");
    let _tableName = $("#tableName");
    let _tableId = $("#tableId");

    let _saleId = $("#saleId");
    let _saleDetailId = $("#saleDetailId");
    let _productId = $("#productId");
    let _price = $("#price");
    let _quantity = $("#quantity");
    let _total = $("#total");
    let _modal = $("#addModal");
    let _modalLabel = $("#addModalLabel");
    let _addProduct = $("#addProduct");
    let _clientId = $("#clientId");

    let _modalClient = $("#addModalClient");
    let _labelClient = $("#addModalLabelClient");
    let _newClient = $("#newClient");
    let _addClient = $("#addClient");

    let _clientType = $("#clientType");
    let _lruc = $("#lruc");
    let _ruc = $("#ruc");
    let _name = $("#name");
    let _ldni = $("#ldni");
    let _dni = $("#dni");
    let _address = $("#address");
    let _phone = $("#phone");
    let _discount = $("#discount1");
    let _items = [];
    let _url = '{{ $urllocal }}';

    $(document).ready(function(){
        let sumTotal = parseFloat($('#sumTotal').val());
        $('#pTotal').html('Total: S/ ' + formatCurrency(sumTotal));

        $('#withCash').on('change', function(e){
            e.preventDefault();
            let type = $(this).val();
            if(type == 1){
                $('#companyPosId').show();
            }else{
                $('#companyPosId').hide();
            }
        });

        _clientType.on('change', function(e){
            e.preventDefault();
            let type = _clientType.val();
            if(type == 1){
                _lruc.hide();
                _ruc.hide();
                _ldni.html('DNI');
                _dni.show();
                _address.hide();
                setTimeout(function(){
                    $('#name').focus();
                }, 300);
            }else{
                _lruc.show();
                _ruc.show();
                _dni.hide();    
                _ldni.html('Dirección');
                _address.show();
                setTimeout(function(){
                    $('#ruc').focus();
                }, 300);
            }
        })

        _addClient.on('click', function(e){
            e.preventDefault();
            let _t = this;
            let type = _clientType.val();
            if(type==1 && _dni.val().length == 0){
                showWarningMsg('Ingrese el DNI del cliente');
                return;
            }
            if(type==2 && _ruc.val().length == 0){
                showWarningMsg('Ingrese el RUC del cliente');
                return;
            }
            let elements = [
                ['name', 'Ingrese el nombre del cliente']
            ];

            if(emptyfy(elements)) {
                _t.disabled = true;
                let route = "{{ route('client.add') }}";
                let data = getFormParams('frmAddClient');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _t.disabled = false;
                        _modalClient.modal('hide');
                        showSuccessMsg(result.message);

                        let name = $('#name').val();
                        let id = result.clientId;
                        let newOption = new Option(name, id, false, false);
                        _clientId.append(newOption);
                        _clientId.val(id).change();

                        let discount = $('#discount1').val();
                        $('#discount').val(discount).change();
                    }
                    if(result.status=="error"){
                        _t.disabled = false;
                        showErrorMsg(result.message);
                    }
                });
            }
        });

        _newClient.on('click', function(e){
            e.preventDefault();
            _modalClient.modal('show');
            clearFormClient();
            
        });

        _clientId.on('change', function(e){
            e.preventDefault();
            let discount = $(this).find(':selected').data('discount');
            $('#discount').val(discount).change();
        });

        _changeTable.on('click', function(e) {
            e.preventDefault();
            _tableId.val('').change();
            _modalTable.modal('show');
        });

        _btnChangeTable.on('click', function(e) {
            e.preventDefault();
            let elements = [
                ['tableId', 'Seleccione una mesa']
            ];

            if(emptyfy(elements)) {
                let route = "{{ route('sales.changetable') }}";
                let data = getFormParams('frmChangeTable');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modalTable.modal('hide');
                        showSuccessMsg(result.message);
                        _tableName.html("Mesa: <b>" + result.table + "</b>");
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }
        });

        _addProduct.on('click', function(e) {
            e.preventDefault();
            clearFormProduct();
            _modalLabel.text("Agregar Producto");
            _modal.modal('show');
            setTimeout(function(){
                _productId.focus();
            }, 300);
        });
        
        $('#generateTicket').on('click', function(e) {
            e.preventDefault();
            let _t = this;
            _t.disabled = true;
            let route = "{{ route('sales.sendticket') }}";
            let data = getFormParams('frmSendTicket');
            fetch(route, {
                method: 'post',
                body: data,
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    _t.disabled = false;
                    let _data = result.data;
                    fetch(_url + '/sale/localprint', {
                        method: 'post',
                        body: _data,
                        headers: { 'X-CSRF-TOKEN': _token },
                    })
                    .then(response => response.json()) 
                    .then(res => {
                        if(res.status=="success"){
                            window.location = "/sale/available";
                        }
                        if(res.status=="error"){
                            showErrorMsg(res.message);
                        }
                    })
                    .catch(err => console.log(err));
                }
                if(result.status=="error"){
                    _t.disabled = false;
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
            let _t = this;
            _t.disabled = true;
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
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        _modal.modal('hide');
                        _t.disabled = false;
                        window.location = "/sale/"+saleId;
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                });
            }
        });

        $(document).on('click', '.printmore', function(e){
            let saleDetailId = $(this).data('id');
            if(this.checked== true){
                _items.push(saleDetailId);
            }else{
                _items.splice(_items.indexOf(saleDetailId), 1);
            } 
        });

        $(document).on('click', '.remove_product', function(e){
            e.preventDefault();
            let saleDetailId = $(this).data('id');
            let saleId = $('#saleId').val();
            fetch("/salesdetail/remove/" + saleDetailId, {
                method: 'post',
                body: {saleDetailId : saleDetailId},
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": _token
                }
            })
            .then(response => response.json())
            .then(result => {
                if(result.status=="success"){
                    window.location = "/sale/"+saleId;
                }
            });
        });

        $(document).on('click', '.edit_product', function(e){
            e.preventDefault();
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

        $(document).on('click', '.print_item', function(e){
            e.preventDefault();
            let _t = this;
            let saleDetailId = $(this).data('id');
            let saleId = $('#saleId').val();            
            fetch("/salesdetail/print/" + saleDetailId).then((response) => {
                return response.json();
            })
            .then((data) => {
                _t.disabled = false;
                if(data.status=="success"){
                    showSuccessMsg(data.message);
                    setTimeout(function(){
                        window.location = "/sale/" + saleId;
                    }, 2000);
                }
                if(data.status=="error"){
                    showErrorMsg(data.message);
                }       
            })
            .catch(function(error) {
                _t.disabled = false;
                console.log(error);
            });
        })

        $('#clearTable').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Atención",
                text: "Desea desocupar la mesa ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
                }).then((result) => {
                if (result.isConfirmed) {
                    let _t = this;
                    _t.disabled = true;
                    let saleId = $('#saleId').val();                    
                    fetch("/table/clear/" + saleId).then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        _t.disabled = false;
                        if(data.status=="success"){
                            showSuccessMsg(data.message);
                        }
                        if(data.status=="error"){
                            showErrorMsg(data.message);
                        }       
                    })
                    .catch(function(error) {
                        _t.disabled = false;
                        console.log(error);
                    });
                }
            });
        });    

        $('#generateOrder').on('click', function(e) {
            e.preventDefault();
            let _t = this;
            if(_items.length == 0){
                _t.disabled = true;
                let saleId = $('#saleId').val();            
                fetch("/sale/order/" + saleId).then((response) => {
                    return response.json();
                })
                .then((result) => {
                    _t.disabled = false;
                    if(result.status=="success"){
                        let resdata = result.data;
                        let _data = JSON.parse(result.data);
                        if(_data['detail'].length > 0){
                            let find1 = searchIncharge(_data['detail'], "Cocina");
                            if(find1 > 0){
                                //print order cocina
                                fetch(_url + '/sale/orderprint/Cocina', {
                                    method: 'post', body: resdata,
                                    headers: { 'X-CSRF-TOKEN': _token },
                                })
                                .then(response => response.json()) 
                                .then(res => {
                                    if(res.status=="success"){
                                        console.log(res.message);
                                    }
                                    if(res.status=="error"){
                                        showErrorMsg(res.message);
                                        console.log(res.error);
                                    }
                                });        
                            }
                            
                            let find2 = searchIncharge(_data['detail'], "Barra");
                            if(find2 > 0){
                                //print order barra
                                fetch(_url + '/sale/orderprint/Barra', {
                                    method: 'post', body: resdata,
                                    headers: { 'X-CSRF-TOKEN': _token },
                                })
                                .then(response => response.json()) 
                                .then(res => {
                                    if(res.status=="success"){
                                        console.log(res.message);
                                        showSuccessMsg(res.message);
                                        // setTimeout(function(){
                                        //     //window.location = "/sale/" + saleId;
                                        // }, 2000);
                                    }
                                    if(res.status=="error"){
                                        showErrorMsg(res.message);
                                        console.log(res.error);
                                    }
                                });    
                            }
                        }
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }       
                })
                .catch(function(error) {
                    _t.disabled = false;
                    console.log(error);
                });
            }else{
                showErrorMsg("en mantenimiento");
                // $('#ids').val(_items);
                // let route = "{{ route('sales.reprint') }}";
                // let data = getFormParams('frmRePrint');
                // fetch(route, {
                //     method: 'post',
                //     body: data,
                // })
                // .then(response => response.json())
                // .then(result => {
                //     if(result.status=="success"){
                //         _t.disabled = false;
                //         showSuccessMsg(result.message);
                //         setTimeout(function(){
                //             window.location = "/sale/available";
                //         }, 2000);
                //     }
                //     if(result.status=="error"){
                //         _t.disabled = false;
                //         showErrorMsg(result.message);
                //     }
                // });
            }
        });
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
        _clientType.val(1).change();
        _ruc.val('');
        _name.val('');
        _address.val('');
        _dni.val('');
        _phone.val('');
        _discount.val(0).change();

    }

    function searchIncharge(items, incharge){
        let find = 0;
        for(let i = 0; i < items.length; i++){
            if(items[i]['inCharge'] == incharge){
                return 1;
            }
        }
        return 0;
    }
</script>    
@stop