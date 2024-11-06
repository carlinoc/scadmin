@extends('adminlte::page')

@section('title', 'Pedido')

@section('content_header')
    <div class="row">
        <div class="col-md-auto">
            <h1>Pedido:</h1>
        </div>
        <div class="col">
            <a href="/report/detail/{{ $sale->saleId }}" class="btn btn-outline-dark" role="button">Atras</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" id="payBoxState" name="payBoxState" value="0">
                        {{-- <input type="hidden" id="payBoxState" name="payBoxState" value="{{$sale->payboxState}}"> --}}
                        <form action="#" method="POST" id="frmSale">
                            @csrf
                            <input type="hidden" name="saleId" id="saleId" value="{{ $sale->saleId }}">
                            <input type="hidden" name="tableId" id="tableId" value="{{ $sale->tableId }}">
                            <input type="hidden" name="payboxId" id="payboxId" value="{{ $sale->payboxId }}">
                            <input type="hidden" name="clientId" id="clientId" value="{{ $sale->clientId }}">
                        </form>
                        <div class="col">
                            <p class="h5 text-success">Numero: <b>{{ $sale->saleId }}</b></p>
                        </div>
                        <div class="col">
                            <label class="h5 text-info mr-2" id="tableName">Mesa: <b>{{ $sale->table }}</b></label>
                        </div>
                        <div class="col">
                            <p class="h5 text-danger" id="sTotal">Total: S/ <b>0.00</b></p>
                        </div>
                        <div class="col-8">
                            <button id="newSplit" type="submit" class="btn btn-danger">Nueva Cuenta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-0">
                    <form action="#" method="POST" id="frmAddDetail">
                        @csrf
                        <input type="hidden" name="oldsaleId" id="oldsaleId">
                        <input type="hidden" name="saleId" id="ssaleId">
                        <input type="hidden" name="saledetailId" id="ssaledetailId">
                    </form>
                    <form action="#" method="POST" id="frmPrintDetail">
                        @csrf
                        <input type="hidden" name="saleId" id="psaleId">
                        <input type="hidden" name="discount" id="pdiscount">
                    </form>
                    <table id="dtDetail" class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th style="width:100px">Producto</th>
                                <th style="width:100px">S/ Precio</th>
                                <th style="width:80px">Opc.</th>
                            </tr>
                        </thead>
                        <tbody id="tbDtail">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td>
                                    <label class='checkbox-inline' style="font-size: 16px">Desc. %</label>
                                    <select id="mdiscount" name="mdiscount" style="font-size: 16px">
                                        <option value="0">0</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                    </select>
                                </td>
                                <td>
                                    <button id="generateTicket" type="button" class="btn btn-primary btn-xs" style="font-weight: bold;">Imprimir Ticket</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <form action="#" method="POST" id="frmRemoveDetail">
            @csrf
            <input type="hidden" name="oldsaleId" id="xoldsaleId">
            <input type="hidden" name="saledetailId" id="xsaledetailId">
        </form>
        <div class="col-md-6" id="divSplit">
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

        let _saleId = $("#saleId");
        let _ds=null;
        let _divSplit = $("#divSplit");
        let _dsList=null;
        let _dtDetail = $("#dtDetail");
        let _table = "{{ $sale->table }}";
        let _payboxState = $("#payBoxState");
        let _url = '{{ $urllocal }}';
                
        $(document).ready(function(){

            fetchSalesDetail();

            fetchSplitList();

            $("#generateTicket").click(function(e) {
                e.preventDefault();
                let id = _saleId.val();
                let discount = $("#mdiscount").val();
                
                $("#psaleId").val(id);
                $("#pdiscount").val(discount);
                
                let route = "{{ route('salesdetail.sendticket') }}";
                let data = getFormParams('frmPrintDetail');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    let _data = result.data;
                    fetch(_url + '/sale/localprint?data=' + _data)
                    .then(response => response.json()) 
                    .then(res => {
                        if(res.status=="success"){
                            showSuccessMsg(result.message);
                            //window.location = _backUrl.val();
                        }
                        if(res.status=="error"){
                            showErrorMsg(res.message);
                        }
                    })
                    .catch(err => console.log(err));
                })
            });

            $("#newSplit").click(function() {
                if(_payboxState.val() == 2){
                    showErrorMsg("La venta se encuentra cerrada");
                    return;
                }
                Swal.fire({
                    title: "Atención",
                    text: "Desea agregar nueva cuenta?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let route = "{{ route('salesdetail.addsale') }}";        
                        let data = getFormParams('frmSale');
                        fetch(route, {
                            method: 'post',
                            body: data,
                        })
                        .then(response => response.json())
                        .then(result => {
                            if(result.status=="success") {
                                showSuccessMsg(result.message);
                                fetchSplitList();
                            }
                        });
                    }
                });    
            });

            _dtDetail.on("click", ".moveItem", function(e) {
                e.preventDefault();
                if(_payboxState.val() == 2){
                    showErrorMsg("La venta se encuentra cerrada");
                    return;
                }
                let id = $(this).data("id");
                $("#ssaledetailId").val(id);
                $("#oldsaleId").val(_saleId.val());
                let saleId = $('input[name="cr"]:checked').val();
                if(!saleId){
                    showErrorMsg("Debe seleccionar una cuenta");
                    return;
                }
                $("#ssaleId").val(saleId);              

                let route = "{{ route('salesdetail.adddetail') }}";

                let data = getFormParams('frmAddDetail');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        showSuccessMsg(result.message);
                        fetchSalesDetail();
                        fetchSplitList();
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                })
            }); 
        });

        function setEvents() {
            $(".btnremove").on('click', function(e) {
                e.preventDefault();
                if(_payboxState.val() == 2){
                    showErrorMsg("La venta se encuentra cerrada");
                    return;
                }

                Swal.fire({
                    title: "Atención",
                    text: "Desea eliminar la cuenta y regresar los productos a la cuenta principal?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                        let mainId = _saleId.val();
                        let saleId = $(this).data("id");
                        let index = $(this).data("index");

                        fetch("/sale/removesale/" + saleId + "/" + mainId + "/")
                        .then((response) => {
                            return response.json();
                        })
                        .then((result) => {
                            if(result.status=="success"){
                                showSuccessMsg(result.message);
                                fetchSalesDetail();
                                fetchSplitList();
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });        
                    }
                });    
            })

            $(".btnprint").on('click', function(e) {
                e.preventDefault();
                let id = $(this).data("id");
                let index = $(this).data("index");
                let discount = $("#discount_"+index+"").val();
                
                $("#psaleId").val(id);
                $("#pdiscount").val(discount);
                
                let route = "{{ route('salesdetail.sendticket') }}";
                let data = getFormParams('frmPrintDetail');
                fetch(route, {
                    method: 'post',
                    body: data,
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status=="success"){
                        let _data = result.data;
                        fetch(_url + '/sale/localprint?data=' + _data)
                        .then(response => response.json()) 
                        .then(res => {
                            if(res.status=="success"){
                                showSuccessMsg(result.message);
                            }
                            if(res.status=="error"){
                                showErrorMsg(res.message);
                            }
                        })
                        .catch(err => console.log(err));
                    }
                    if(result.status=="error"){
                        showErrorMsg(result.message);
                    }
                    // if(result.status=="success"){
                    //     showSuccessMsg(result.message);
                    // }
                    // if(result.status=="error"){
                    //     showErrorMsg(result.message);
                    // }
                })
            })

            $(".rselect").on('click', function(e) {
                let id = $(this).attr("id");
                localStorage.setItem("cSplit", id);
            })

            $(".deleteItem").on('click', function(e) {
                e.preventDefault();
                if(_payboxState.val() == 2){
                    showErrorMsg("La venta se encuentra cerrada");
                    return;
                }
                Swal.fire({
                    title: "Atención",
                    text: "Desea quitar el producto ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).data("id");
                        $("#xsaledetailId").val(id);
                        $("#xoldsaleId").val(_saleId.val());
                        
                        let route = "{{ route('salesdetail.removedetail') }}";
                        let data = getFormParams('frmRemoveDetail');
                        fetch(route, {
                            method: 'post',
                            body: data,
                        })
                        .then(response => response.json())
                        .then(result => {
                            if(result.status=="success"){
                                showSuccessMsg(result.message);
                                fetchSalesDetail();
                                fetchSplitList();
                            }
                            if(result.status=="error"){
                                showErrorMsg(result.message);
                            }
                        })        
                    }
                });                
            })
        }

        async function fetchSalesDetail() {
            let saleid = _saleId.val();
            const response = await fetch("/salesdetail/list/" + saleid, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch sales detail");       
            }                    
            const data = await response.json();
            if(data.status=="success") {
                _ds = data.list;
                let total = 0.00;
                $("#tbDtail").empty();
                for($i = 0; $i < _ds.length; $i++) {
                    dr = _ds[$i];
                    for($j = 0; $j < dr.quantity; $j++) {
                        total += parseFloat(dr.price);
                        addRow($i, dr.product, dr.price, dr.id);    
                    }
                }
                $("#sTotal").html("Total S/ " + total.toFixed(2));
            }
        }

        function addRow(vindex, vproduct, vprice, vid) {
            let table = document.getElementById("tbDtail");
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
                        
            c1.innerText = vproduct;
            c2.innerText = vprice;
            c3.innerHTML = '<a href="#" data-id="' + vid + '" class="btn-sm bg-success moveItem"><i class="fas fa-angle-double-right"></i></a>';
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
                        
            table.appendChild(row);
        }

        async function fetchSplitList() {
            let saleid = _saleId.val();
            const response = await fetch("/salesdetail/splitlist/" + saleid, {method: 'GET'});
            if(!response.ok){
                throw new Error("Error fetch splitlist");       
            }                    
            const data = await response.json();
            if(data.status=="success") {
                _dsList = data.list;
                _divSplit.empty();
                for($i = 0; $i < _dsList.length; $i++) {
                    let index = $i + 1;
                    dr = _dsList[$i];
                    _divSplit.append(html_template(dr.splitNumber, dr.id));
                    let total = 0.00;
                    if(dr.detail.length > 0) {
                        for($j = 0; $j < dr.detail.length; $j++) {
                            total = total + parseFloat(dr.detail[$j].price);
                            addRowItem($j, dr.detail[$j].product, dr.detail[$j].price, dr.detail[$j].id, dr.splitNumber);        
                        }
                    }

                    $("#titleSplit_" + dr.splitNumber).text("Id: " + dr.id + " / Cuenta " + _table + " - " + dr.splitNumber);
                    $("#totalSplit_" + dr.splitNumber).text("S/ " + total);
                    if(dr.discount > 0) {
                        $("#discount_" + dr.splitNumber).val(dr.discount).change();
                        let desc = total * (dr.discount / 100);
                        let total2 = total - desc;
                        $("#totalSplit_" + dr.splitNumber).text("S/ " + total2.toFixed(2)); 
                    }
                }
                setEvents();

                const cSplit = localStorage.getItem("cSplit");
                $('#'+cSplit).prop('checked', true);
            }
        }

        function addRowItem(vindex, vproduct, vprice, vid, vconte) {
            let table = document.getElementById("tbSplit_" + vconte);
            let row = document.createElement("tr");
            
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
                        
            c1.innerText = vproduct;
            c2.innerText = vprice;
            c3.innerHTML = '<a href="#" data-id="' + vid + '" class="btn-sm bg-danger deleteItem"><i class="fas fa-trash"></i></a>';
                        
            row.appendChild(c1);
            row.appendChild(c2);
            row.appendChild(c3);
                        
            table.appendChild(row);
        }

        function html_template(index, id) {
            return '<div class="card card-primary">\
                <div class="card-header">\
                    <h3 class="card-title" id="titleSplit_' + index + '">Id: ' + id + '  / Cuenta A1 - ' + index + ' </h3>\
                    <div class="card-tools"><span id="totalSplit_' + index + '">S/ 0.00</span> <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button></div>\
                </div>\
                <div class="card-body" style="display: block;">\
                    <table id="dtSplit_' + index +'" class="table table-striped table-valign-middle">\
                        <thead>\
                            <tr>\
                                <th style="width:100px">Producto</th>\
                                <th style="width:100px">S/ Precio</th>\
                                <th style="width:80px">Opc.</th>\
                            </tr>\
                        </thead>\
                        <tbody id="tbSplit_' + index +'">\
                        </tbody>\
                    </table>\
                </div>\
                <div class="card-footer">\
                    <div class="row">\
                        <div class="col">\
                            <div class="custom-control custom-radio">\
                                <input class="custom-control-input rselect" type="radio" id="cr_' + index +'" name="cr" value="' + id +'">\
                                <label for="cr_' + index +'" class="custom-control-label">Agregar a esta cuenta</label>\
                            </div>\
                        </div>\
                        <div class="col text-right">\
                            <label class="checkbox-inline">Desc. %</label>\
                            <select id="discount_' + index +'" name="discount">\
                                <option value="0">0</option>\
                                <option value="10">10</option>\
                                <option value="20">20</option>\
                                <option value="30">30</option>\
                                <option value="40">40</option>\
                            </select>\
                        </div>\
                        <div class="col text-right">\
                            <button type="submit" data-id="' + id +'" data-index="' + index +'" class="btn btn-primary btn-xs btnprint">Imprimir Ticket</button>\
                            <button type="submit" data-id="' + id +'" data-index="' + index +'" class="btn btn-danger btn-xs btnremove">Eliminar</button>\
                        </div>\
                    </div>\
                </div>\
            </div>';
        } 
    </script>
@stop
