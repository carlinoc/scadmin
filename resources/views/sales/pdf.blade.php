<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ticket</title>
	<style>
 		* {
    margin: 0;
    padding: 0;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    box-sizing: border-box;
    font-size: 14px;
}

img {
    max-width: 100%;
}

body {
    -webkit-font-smoothing: antialiased;
    -webkit-text-size-adjust: none;
    width: 100% !important;
    height: 100%;
    line-height: 1.6;
}

table td {
    vertical-align: top;
}

body {
    background-color: #f6f6f6;
}

.body-wrap {
    background-color: #f6f6f6;
    width: 100%;
}

.container {
    display: block !important;
    max-width: 600px !important;
    margin: 0 auto !important;
    /* makes it centered */
    clear: both !important;
}

.content {
    max-width: 600px;
    margin: 0 auto;
    display: block;
    padding: 20px;
}

.main {
    background: #fff;
    border: 1px solid #e9e9e9;
    border-radius: 3px;
}

.content-wrap {
    padding: 20px;
}

.content-block {
    padding: 0 0 20px;
}

.header {
    width: 100%;
    margin-bottom: 20px;
}

.footer {
    width: 100%;
    clear: both;
    color: #999;
    padding: 20px;
}
.footer a {
    color: #999;
}
.footer p, .footer a, .footer unsubscribe, .footer td {
    font-size: 12px;
}


h1, h2, h3 {
    font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
    color: #000;
    margin: 40px 0 0;
    line-height: 1.2;
    font-weight: 400;
}

h1 {
    font-size: 32px;
    font-weight: 500;
}

h2 {
    font-size: 24px;
}

h3 {
    font-size: 18px;
}

h4 {
    font-size: 14px;
    font-weight: 600;
}

p, ul, ol {
    margin-bottom: 10px;
    font-weight: normal;
}
p li, ul li, ol li {
    margin-left: 5px;
    list-style-position: inside;
}

.last {
    margin-bottom: 0;
}

.first {
    margin-top: 0;
}

.aligncenter {
    text-align: center;
}

.alignright {
    text-align: right;
}

.alignleft {
    text-align: left;
}

.clear {
    clear: both;
}


.invoice {
    margin: 40px auto;
    text-align: left;
    width: 80%;
}
.invoice td {
    padding: 5px 0;
}
.invoice .invoice-items {
    width: 100%;
}
.invoice .invoice-items td {
    border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
    font-weight: 700;
}

@media only screen and (max-width: 640px) {
    h1, h2, h3, h4 {
        font-weight: 600 !important;
        margin: 20px 0 5px !important;
    }

    h1 {
        font-size: 22px !important;
    }

    h2 {
        font-size: 18px !important;
    }

    h3 {
        font-size: 16px !important;
    }

    .container {
        width: 100% !important;
    }

    .content, .content-wrap {
        padding: 10px !important;
    }

    .invoice {
        width: 100% !important;
    }
} 									
  	</style>
</head>
<body>
    <div>
        <div class="row">
            <table class="body-wrap">
                <tbody>
                    <tr>
                    <td></td>
                    <td class="container" width="600">
                        <div class="content">
                            <table class="main" width="100%" cellpadding="0" cellspacing="0">
                                <tbody><tr>
                                    <td class="content-wrap aligncenter">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tbody><tr>
                                                <td class="content-block">
                                                    <h2>SanCris - Restobar</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="content-block">
                                                    <table class="invoice">
                                                        <tbody><tr>
                                                            <td>
                                                                Mesa: {{$table->table}}
                                                            <br>
                                                                Ticket #{{$sale->id}}
                                                            <br>
                                                                {{date_format($sale->created_at,"d-m-Y g:i A")}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                    <tbody>
                                                                    <?php $sumTotal = 0 ?>    
                                                                    @foreach($salesDetails as $saleDetail)
                                                                    <tr>
                                                                        <td>{{ $saleDetail->product }}</td>
                                                                        <td>{{ $saleDetail->quantity }}</td>
                                                                        <td class="alignright">S/{{ $saleDetail->total }}</td>
                                                                    </tr>
                                                                    <?php $sumTotal += $saleDetail->total ?>
                                                                    @endforeach
                                                                    <tr class="total">
                                                                        <td class="alignright" width="60%"></td>
                                                                        <td class="alignright" width="30%">Total</td>
                                                                        <td class="alignright">S/ {{ $sumTotal}}</td>
                                                                    </tr>
                                                                    @if($discount>0)
                                                                    <?php 
                                                                        $total = $sumTotal;
                                                                        $desc = $total * 0.20;
                                                                        $total = round($sumTotal - $desc);
                                                                    ?>
                                                                    <tr class="total">
                                                                        <td class="alignright" width="60%">Descuento por promoción -20%</td>
                                                                        <td class="alignright" width="30%">Total</td>
                                                                        <td class="alignright">S/ {{ $total}}</td>
                                                                    </tr>    
                                                                    @endif
                                                                </tbody></table>
                                                            </td>
                                                        </tr>
                                                    </tbody></table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="content-block">
                                                    Pumacurco 650, San Cristobal - Cusco
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                            </tbody></table>
                        </div>
                    </td>
                    <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    </body>
</html>