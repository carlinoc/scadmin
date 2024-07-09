@extends('adminlte::page')

@section('title', 'Venta')

@section('content_header')
<div class="row">
    <div class="col">
        <h1>Detalle de Venta</h1>
    </div>
    <div class="col">
        <a href="{{route('report.sales')}}" class="btn btn-secondary" role="button">Volver</a>
    </div>
</div>
    
@stop

@section('content')
    <div>
        @if (Session::get('error'))
            <div>
                <div class="alert alert-danger mt-2">
                    <strong>{{Session::get('error')}}</strong>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="bd-example">
                    <div class="row">
                        <div class="col">
                            <label for="" class="col-form-label" style="color:#17a2b8!important;"><h5>{{date_format($sale->created_at,"d-m-Y g:i A")}}</h5></label>  
                        </div>
                        <div class="col">
                            <label for="" class="col-form-label" style="color:#dc3545!important;"><h5>Mesa: {{$sale->table}}</h5></label>          
                        </div>
                        <div class="col">
                            <input type="hidden" name="saleId" id="saleId" value="{{$sale->saleId}}">
                            <a id="addProducts" href="#" class="btn btn-success" style="font-weight: bold;" data-toggle="modal" data-target="#addModal">+ Agregar Producto</a>        
                        </div>
                    </div>
                    <div class="row" style="background:#f8f9fa; margin:1px;">
                        <div class="col">
                            <label class="col-form-label" style="color:#28a745!important;"><h5>SubTotal S/: {{$sale->subtotal}}</h5></label>        
                        </div>
                        <div class="col">
                            <label class='checkbox-inline' style="font-size: 18px">Descuento %</label>
                            <select id="discount" style="font-size: 18px">
                                <option value="0">0</option>
                                <option value="20" @selected("20"==$sale->discount)>20</option>
                                <option value="25" @selected("25"==$sale->discount)>25</option>
                                <option value="30" @selected("30"==$sale->discount)>30</option>
                                <option value="35" @selected("35"==$sale->discount)>35</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="col-form-label" style="color:#dc3545!important;"><h5>Total S/: {{$sale->total}}</h5></label>        
                        </div>
                        <div class="col"></div>
                    </div>
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
                            @foreach($salesDetails as $key=>$saleDetail)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{ $saleDetail->product }}</td>
                                <td>s/ {{ $saleDetail->price }}</td>
                                <td>{{ $saleDetail->quantity }}</td>
                                <td>s/ {{ $saleDetail->total }}</td>
                                <td>
                                    <a href="" class="btn btn-sm btn-warning edit_product" 
                                        data-toggle="modal" data-target="#editModal"
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
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col">
                            <a id="preview" href="{{route('sales.pdf', [$sale->saleId, 'discount'=>0])}}" target="_blank" class="btn btn-info" style="font-weight: bold;" >Descargar</a>        
                        </div>
                        <div class="col">
                            <input type="checkbox" value="" @checked("1"==$sale->withCash) id="withCash"> Con Tarjeta
                            <a id="updateSale" href="{{route('sales.change', [$sale->saleId, 'discount'=>-1, 'withcash'=>-2])}}" class="btn btn-success" style="font-weight: bold;" >Actualizar Venta</a>                            
                        </div>
                        <div class="col">
                            <a id="generateTicket" href="{{route('sales.print', [$sale->saleId, 'discount'=>-1, 'withcash'=>-2])}}" class="btn btn-primary" style="font-weight: bold;" >Generar Ticket</a>        
                        </div>
                    </div>
                    <div class="row" style="background:#f8f9fa; margin:4px;">
                        <div class="col">
                            <label class="col-form-label col-md-6"><h5>Nro Ticket: {{$sale->saleId}}</h5></label>
                        </div>
                        <div class="col">
                            <a id="nullifySale" href="{{route('sales.nullify', $sale->saleId)}}" class="btn btn-danger" style="font-weight: bold;" >Anular Venta</a>
                        </div>
                        <div class="col">
                            <label class="col-form-label col-md-6" style="color:#dc3545!important;">
                                <h5>Estado:
                                @if ($sale->status==1)
                                    Emitido
                                @else
                                    Anulado
                                @endif
                                </h5>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    @include('sales.add-modal')
    
@stop

@section('css')
<style>
     .bd-example {
        padding:1.5rem;
        margin-right: 0;
        margin-left: 0;
        border-width: 1px;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
        background-color:#ffffff;
    }
</style> 
@stop

@section('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        $('#generateTicket').on('click', function(e) {
            e.preventDefault();
            var url = $('#generateTicket').attr('href');
            var discount = $('#discount').val();
            var withCash = 0;
            if ($('#withCash').is(":checked")) {
                withCash = 1;    
            }
            url = url.replace(-1, discount).replace(-2, withCash);
            window.location.replace(url);
        });    

        $('#updateSale').on('click', function(e) {
            e.preventDefault();
            var url = $('#updateSale').attr('href');
            var discount = $('#discount').val();
            var withCash = 0;
            if ($('#withCash').is(":checked")) {
                withCash = 1;    
            }
            url = url.replace(-1, discount).replace(-2, withCash);
            window.location.replace(url);
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
            let price = $('#productId :selected').attr('data-price');
            let quantity = $('#quantity :selected').val();
            let total = $('#total').val();
            let saleId = $('#saleId').val();
            let productId = $('#productId :selected').val();
            if(typeof price != 'undefined') {
                $.ajax({
                    url:"{{ route('salesdetail.add') }}",
                    method:'post',
                    data:{price:price,quantity:quantity,total:total,saleId:saleId,productId:productId},
                    success:function(res){
                        if(res.status=="success"){
                            $('#addModal').modal('hide');
                            $('#frmAddProduct')[0].reset();
                            window.location = "/detail/"+saleId;
                        }
                    },error:function(err){

                    }
                });        
            }
        });

        $(document).on('click', '.remove_product', function(e){
            e.preventDefault();
            // let saleDetailId = $(this).data('id');
            // let saleId = $('#saleId').val();
            // console.log(saleDetailId);
            // $.ajax({
            //     url:"",
            //     method:'post',
            //     data:{id:saleDetailId},
            //     success:function(res){
            //         if(res.status=="success"){
            //             window.location = "/detail/"+saleId;
            //         }
            //     },error:function(err){

            //     }
            // });    
        });

        $(document).on('click', '.edit_product', function(e){
            let id=$(this).data('id');
            let pId=$(this).data('pid');
            let price=$(this).data('price');
            let quantity=$(this).data('quantity');
            let total=$(this).data('total');
            $('#_saleDetailId').val(id);
            $('#_productId').val(pId).change();
            $('#_price').val(price);
            $('#_quantity').val(quantity).change();
            $('#_total').val(total);
        });

        $('#_productId').on('select2:select', function (e) {
            var price = $('#_productId :selected').attr('data-price');
            var quantity = $('#_quantity :selected').val();
            var total = price * quantity;
            $('#_price').val(price);
            $('#_total').val((Math.round(total * 100) / 100).toFixed(2));
        });

        $('#_quantity').on('change', function() {
            var price = $('#_productId :selected').attr('data-price');
            if(typeof price != 'undefined'){
                var quantity = this.value;
                var total = price * quantity;
                $('#_total').val((Math.round(total * 100) / 100).toFixed(2));
            }
        });

        $('#editProduct').on('click', function(e) {
            e.preventDefault();
            let _detailId = $('#_saleDetailId').val();
            let _price = $('#_productId :selected').attr('data-price');
            let _quantity = $('#_quantity :selected').val();
            let _total = $('#_total').val();
            let _productId = $('#_productId :selected').val();
            let saleId = $('#saleId').val();
            if(typeof price != 'undefined') {
                $.ajax({
                    url:"{{ route('salesdetail.edit') }}",
                    method:'post',
                    data:{detailId:_detailId, eprice:_price,equantity:_quantity,etotal:_total,eproductId:_productId},
                    success:function(res){
                        if(res.status=="success"){
                            $('#editModal').modal('hide');
                            $('#frmEditProduct')[0].reset();
                            window.location = "/detail/"+saleId;
                        }
                    },error:function(err){

                    }
                });        
            }
        });

    });    
</script>    
@stop