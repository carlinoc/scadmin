<?php

namespace App\Http\Controllers;

use App\Models\PayBox;
use App\Models\Sale;
use App\Models\Table;
use App\Models\Product;
use App\Models\SalesDetail;
use App\Models\Client;
use App\Models\CompanySerial;
use App\Models\Company;
use App\Models\User;
use App\Models\CompanyPos;
use App\Models\SalesHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Type\Integer;
use Luecano\NumeroALetras\NumeroALetras;
use Myhelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::select('sales.id', 'total', 'sales.created_at', 'sales.updated_at', 'tables.name as table', 'tables.placeId as placeId', 'places.place as place')
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')->where('sales.status', 0)
            ->orderBy('sales.id', 'DESC')
            ->get();

        $paybox = PayBox::select('id')->where('state','=', 1);
        $payboxId = 0;
        if($paybox->count() > 0){
            $payboxId = $paybox->first()->id;
        }

        $tables = Table::all();

        $heads = $this->getHeads();

        return view('sales.index', ['sales' => $sales, 'heads' => $heads, 'tables' => $tables, 'payboxId' => $payboxId]);
    }

    public function store(Request $request, Sale $sale)
    {
        $request->validate([
            'tableId' => 'required'
        ]);

        $paybox = PayBox::select('id')->where('state','=', 1)->where('startDate', '>=', Carbon::now()->subDays(1)->toDateTimeString())->count();
        if($paybox==0){
            return redirect()->route('sales.index')->with('warning', 'Es necesario aperturar la CAJA');
        }else{
            $rows = Sale::all()->where('tableId', $request->tableId)->where('status', 0)->count();
            if($rows>0){
                return redirect()->route('sales.index')->with('error', 'Ya existe un pedido con esa mesa');
            }else{
                $sale = new Sale();
                $sale->tableId = $request->tableId;
                $sale->userId = $request->userId;
                $sale->total = 0;
                $sale->status = 0;
                $sale->payboxId = $request->payboxId;
                $sale->save();

                return redirect()->route('sales.show', ['saleId' => $sale->id]);
            }
        }
    }

    public function show(Request $request): View
    {
        $sale = Sale::select('sales.id as saleId', 'total', 'sales.created_at', 'sales.updated_at', 'tables.name as table', 'users.name as user', 'sales.printOrder')
             ->join('tables', 'tables.id','=','sales.tableId')
             ->join('users', 'users.id','=','sales.userId')
             ->where('sales.id', $request->saleId)->first();

        $products = Product::orderBy('name')->get();

        $tables = Table::all();

        $clients = Client::all();

        $salesDetails = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId', 'sales_detail.printOrder')
            ->join('products', 'products.id','=','sales_detail.productId')
            ->where('sales_detail.saleId', $request->saleId)->get();

        $companyPosList = CompanyPos::all();    

        return view('sales.show', ['sale' => $sale, 'products' => $products, 'salesDetails' => $salesDetails, 'tables' => $tables, 'clients' => $clients, 'companyPosList' => $companyPosList]);
    }

    public function history(Request $request): View {
        $historys = SalesHistory::select('saleshistory.id','saleshistory.action', 'saleshistory.lasttotal', 'saleshistory.newtotal', 'saleshistory.discount', 'saleshistory.newdiscount',
            'saleshistory.quantity', 'saleshistory.newquantity', DB::raw("DATE_FORMAT(saleshistory.created_at, '%d-%m-%Y %H:%i') as created_at"), 'products.name as product', 'users.name as user')
            ->leftjoin('products', 'products.id', '=', 'saleshistory.productId')
            ->join('users', 'users.id', '=', 'saleshistory.userId')
            ->where('saleId', $request->saleId)
            ->get();
        
        return view('reports.history', ['historys' => $historys]);
    }

    public function addtips(Request $request): JsonResponse 
    {
        $sale = Sale::find($request->saleId);
        $sale->tips = $request->tips;
        $tipsType = $request->tipsType;
        if($request->tips==0){
            $tipsType=0; 
        }
        $sale->tipsType = $tipsType;
        $sale->update();

        return response()->json(['status'=>'success', 'message'=>'La propina fue actualizada']);    
    }

    public function pdf(Sale $sale, Int $discount)
    {
        $salesDetails = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
             ->join('products', 'products.id','=','sales_detail.productId')
             ->where('sales_detail.saleId', $sale->id)->get();

        $table = Sale::select('sales.id', 'tables.name as table')
             ->join('tables', 'tables.id','=','sales.tableId')
             ->where('sales.id', $sale->id)->first();

        $pdf = Pdf::loadView('sales.pdf', compact('sale', 'salesDetails', 'discount', 'table'));
        return $pdf->stream();
    }

    public function senddocument(Request $request){
        
    }

    public function sendboleta(Request $request)
    {
        $RUC = env('DATA_COMPANY_RUC','10238228379');
        $company = Company::all()->where('ruc', $RUC)->first();

        $client = Client::find($request->clientId);
        $cdni = $request->dni;
        $caddress = $request->address;
        $cname = $request->name;

        if($client->dni==""){
            $cdni = "99999999";
            $caddress = "-";
            $cname = "CLIENTE VARIOS";
        }

        $IGV = $company->igv;
        $debug = $company->debug;
        $serieType = 1;
        if($debug==1){
            $serieType = 3;    
        }
        $serial = CompanySerial::select('companyserial.id', 'companyserial.serie', 'companyserial.number')->where('serieType', $serieType)->first();
        $serie = $serial->serie;
        $number = $serial->number;
        $discount = $request->discount;
        $dateOfIssue = Carbon::now()->toDateString();

        $query = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
        ->join('products', 'products.id','=','sales_detail.productId')
        ->where('sales_detail.saleId', $request->saleId);
        
        $salesDetails = $query->get();

        $query2 = $query;
        $total = $query2->sum('total');
        $total2 = $total;
        //Aplicamos descuento si es mayor a cero
        if($discount>0){
            $desc = $total * ($discount / 100);
            $total = $total - $desc;
        }
        $totalgravada = $total / (1 + ($IGV / 100));
        $totaligv = $total - $totalgravada;
        
        $data = array(
            "documento" => "boleta",
            "serie" => $serie,
            "numero" => $number,
            "fecha_de_emision" => $dateOfIssue,
            "fecha_de_vencimiento" => $dateOfIssue,
            "moneda" => "PEN",
            "orden_compra_servicio" => "",
            "tipo_operacion" => "0101",
            "cliente_tipo_de_documento" => "1",
            "cliente_numero_de_documento" => $client->dni,
            "cliente_denominacion" => $client->name,
            "cliente_direccion" => $client->address,
            "total_gravada" => number_format((float)$totalgravada, 2, '.', ''),
            "total_igv" => number_format((float)$totaligv, 2, '.', ''),
            "total" => number_format((float)$total, 2, '.', ''),
        );

        $items = array();

        //echo("-----------------<br>");
        foreach ($salesDetails as $row) {
            $name = trim($row->product);
            $qty = $row->quantity;
            $priceTotal = $row->price;
            //Aplicamos descuento si es mayor a cero
            if($discount > 0){
                $desc = $priceTotal * ($discount / 100); 
                $priceTotal = $priceTotal - $desc;
            }            
            $priceBase = $priceTotal / (1 + ($IGV / 100));
            //echo($qty . " = " . number_format((float)$priceTotal, 2, '.', '') . " - " . number_format((float)$priceBase, 2, '.', '') . "<br>");

            $subtitem = array(
                "unidad_de_medida"          => "ZZ",
                "descripcion"               => $name,
                "cantidad"                  => strval($qty),
                "valor_unitario"            => number_format((float)$priceBase, 2, '.', ''),
                "precio_unitario"           => number_format((float)$priceTotal, 2, '.', ''),
                "porcentaje_igv"            => $IGV,
                "descuento"                 => "0",
                "codigo_tipo_afectacion_igv"=> "10"
            );
            array_push($items, $subtitem);
        }

        $data['items'] = $items;
        //return response()->json($data);

        $autorization = env('DATA_COMPANY_BEARER', 'Bearer 6.pmhqb55lkNsyfGhUlKoUwJwi0CoWwmnAtwm3brYK1A');
        $apisunat = env('DATA_COMPANY_APISUNAT', 'https://api.lucode.pe/api/v222/documents');
        if($debug == 1){
            $apisunat = env('DATA_COMPANY_APISUNAT_DEBUG', 'https://api.lucode.pe/api/v1/documents');
        }

        $response = Http::withBody(json_encode($data), 'application/json')
            ->withHeaders([
                'User-Agent' => 'application/json',
                'Authorization' => $autorization
            ])
            ->post($apisunat);

        //echo($response->body());    

        if ($response->successful()) {
            $client = Client::find($request->clientId);
            $clientName = $client->name;
            $clientDni = $client->dni;

            // Imprimir Boleta
            $this->printBoleta($totaligv, $totalgravada, $total, $serie, $number, $clientName, $clientDni, $request->discount, $salesDetails);

            // Incrementar el número de Boleta
            $newNumber = $number + 1;
            CompanySerial::where('serieType', $serieType)->update(['number' => $newNumber]);
                        
            // Actualizar el total, subtotal y el voucherType
            Sale::where('id', $request->saleId)
                ->update(['subtotal' => $total2, 'total' => $total, 'discount' => $request->discount, 'status' => 1, 'withCash' => $request->withCash, 
                    'clientId' => $request->clientId, 'voucherType' => 1, 'voucherSerie' => $serie, 'voucherNumber' => $number, 'sunat' => 1]);
            
            return response()->json(['status'=>'success', 'message'=>'Se imprimio la boleta correctamente']);
        } else {
            $responseData = $response->json();
            return response()->json(['status'=>'error', 'message'=>$responseData['message']]);
        }
    }

    public function sendfactura(Request $request)
    {
        $RUC = env('DATA_COMPANY_RUC','10238228379');
        $company = Company::all()->where('ruc', $RUC)->first();
        
        $client = Client::find($request->clientId);

        if($client->ruc==""){
            return response()->json(['status'=>'error', 'message'=>'Para emitir una FACTURA es necesario el RUC del cliente']);
        }

        $clientRUC = $client->ruc;
        $clientName = $client->name;
        $clientAddress = (($client->address == "")?"s/n":$client->address);

        $IGV = $company->igv;
        $debug = $company->debug;
        $serieType = 2;
        if($debug==1){
            $serieType = 4;    
        }
        $serial = CompanySerial::select('companyserial.id', 'companyserial.serie', 'companyserial.number')->where('serieType', $serieType)->first();
        $serie = $serial->serie;
        $number = $serial->number;
        $discount = $request->discount;
        $dateOfIssue = Carbon::now()->toDateString();
        
        $query = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
        ->join('products', 'products.id','=','sales_detail.productId')
        ->where('sales_detail.saleId', $request->saleId);
        
        $salesDetails = $query->get();

        $query2 = $query;
        $total = $query2->sum('total');
        $total2 = $total;
        //Aplicamos descuento si es mayor a cero
        if($discount>0){
            $desc = $total * ($discount / 100);
            $total = $total - $desc;
        }
        $totalgravada = $total / (1 + ($IGV / 100));
        $totaligv = $total - $totalgravada;
        //echo(number_format((float)$total, 2, '.', '') . " - " . number_format((float)$totalgravada, 2, '.', '') . " - " . number_format((float)$totaligv, 2, '.', '') . "<br>");

        $data = array(
            "documento" => "factura",
            "serie" => $serie,
            "numero" => $number,
            "fecha_de_emision" => $dateOfIssue,
            "fecha_de_vencimiento" => $dateOfIssue,
            "moneda" => "PEN",
            "orden_compra_servicio" => "",
            "tipo_operacion" => "0101",
            "cliente_tipo_de_documento" => "6",
            "cliente_numero_de_documento" => $clientRUC,
            "cliente_denominacion" => $clientName,
            "cliente_direccion" => $clientAddress,
            "total_gravada" => number_format((float)$totalgravada, 2, '.', ''),
            "total_igv" => number_format((float)$totaligv, 2, '.', ''),
            "total" => number_format((float)$total, 2, '.', ''),
        );

        $items = array();

        //echo("-----------------<br>");
        foreach ($salesDetails as $row) {
            $name = trim($row->product);
            $qty = $row->quantity;
            $priceTotal = $row->price;
            //Aplicamos descuento si es mayor a cero
            if($discount > 0){
                $desc = $priceTotal * ($discount / 100); 
                $priceTotal = $priceTotal - $desc;
            }            
            $priceBase = $priceTotal / (1 + ($IGV / 100));
            //echo($qty . " = " . number_format((float)$priceTotal, 2, '.', '') . " - " . number_format((float)$priceBase, 2, '.', '') . "<br>");

            $subtitem = array(
                "unidad_de_medida"          => "ZZ",
                "descripcion"               => $name,
                "cantidad"                  => strval($qty),
                "valor_unitario"            => number_format((float)$priceBase, 2, '.', ''),
                "precio_unitario"           => number_format((float)$priceTotal, 2, '.', ''),
                "porcentaje_igv"            => $IGV,
                "descuento"                 => "0",
                "codigo_tipo_afectacion_igv"=> "10"
            );
            array_push($items, $subtitem);
        }

        $data['items'] = $items;
        //return response()->json($data);

        $autorization = env('DATA_COMPANY_BEARER', 'Bearer 6.pmhqb55lkNsyfGhUlKoUwJwi0CoWwmnAtwm3brYK1A');
        $apisunat = env('DATA_COMPANY_APISUNAT', 'https://api.lucode.pe/api/v222/documents');
        if($debug == 1){
            $apisunat = env('DATA_COMPANY_APISUNAT_DEBUG', 'https://api.lucode.pe/api/v1/documents');
        }

        $response = Http::withBody(json_encode($data), 'application/json')
            ->withHeaders([
                'User-Agent' => 'application/json',
                'Authorization' => $autorization
            ])
            ->post($apisunat);

        if ($response->successful()) {
            // Imprimir Boleta
            $this->printFactura($totaligv, $totalgravada, $total ,$serie, $number, $clientName, $clientRUC, $clientAddress, $request->discount, $salesDetails);

            // Incrementar el número de Factura
            $newNumber = $number + 1;
            CompanySerial::where('serieType', $serieType)->update(['number' => $newNumber]);
                        
            // Actualizar el total, subtotal y el voucherType
            Sale::where('id', $request->saleId)
                ->update(['subtotal' => $total2, 'total' => $total, 'discount' => $request->discount, 'status' => 1, 'withCash' => $request->withCash, 
                    'clientId' => $request->clientId, 'voucherType' => 2, 'voucherSerie' => $serie, 'voucherNumber' => $number, 'sunat' => 1]);

            return response()->json(['status'=>'success', 'message'=>'Se imprimio la factura correctamente']);
        } else {
            $responseData = $response->json();
            return response()->json(['status'=>'error', 'message'=>$responseData['message']]);
        }
    }

    public function order(Request $request)
    {
        try {
            $sale = Sale::find($request->saleId);
            $table = Table::find($sale->tableId);
            $user = User::find($sale->userId);

            //Genera Ticket para Cocina
            $this->printOrder($sale, 'Cocina', $table->name, $user->name);

            //Genera Ticket para Barra
            $this->printOrder($sale, 'Barra', $table->name, $user->name);

            $sale->printOrder = 1;
            $sale->update();

            return response()->json(['status'=>'success', 'message'=>'Se imprimio la comanda']);
        } catch (\Throwable $th) {
            return response()->json(['status'=>'error', 'message'=>'Error al generar la comanda']);
            //return redirect()->back()->with('error', 'Error al generar la comanda');
        }
    }

    public function sendticket(Request $request)
    {
        $clientId = 1;
        $companyPosId = 0;
        if($request->withCash == 1) {
            $companyPosId = $request->companyPosId;
        }
        if($request->clientId != null) {
            $clientId = $request->clientId;
        }

        $sale = Sale::find($request->saleId);
        $table = Table::find($sale->tableId);
        $tableName = $table->name;
        $table->state = 1;
        $table->update(); 

        try{
            $this->printTicket($sale->id, $request->discount, $request->withCash, $clientId, $tableName, $companyPosId);
        } catch (\Throwable $th) {
            return response()->json(['status'=>'error', 'message'=>'Error al imprimir el ticket']);
        }

        return response()->json(['status'=>'success', 'message'=>'El ticket fue imprimido']);
    }

    public function print(Sale $sale, Int $discount, Int $withcash)
    {
        try{
            //$this->printTicket($sale, $discount, $withcash);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error al imprimir el ticket');
        }

        $sales = Sale::select('sales.id', 'total', 'sales.created_at', 'tables.name as table', 'tables.placeId as placeId', 'places.place as place')
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')->where('sales.status', 0)->get();

        $tables = Table::all();

        $heads = $this->getHeads();

        return view('sales.index', ['sales' => $sales, 'heads' => $heads, 'tables' => $tables]);
    }

    public function update(Request $request) {
        $saleId = $request->saleId;
        $discount = $request->discount;
        $withcash = $request->withCash;
        $clientId = $request->clientId;
        $companyPosId = null;
        
        //try{
            $total = SalesDetail::select('id', 'total')->where('saleId', $saleId)->sum('total');

            $total2 = $total;
            if($discount > 0){
                $desc = $total * ($discount / 100);
                $total2 = $total - $desc;
            }
            if($withcash==1){
                $companyPosId = $request->companyPosId;        
            }

            $sale = Sale::find($saleId);
            if(($request->saveHistory==1) && ($sale->discount != $discount)){
                $this->saveSalesHistory($saleId, 'Descuento Actualizado', $sale->discount, $discount, $total2 , $sale->total);
            }
            if(($request->saveHistory==1) && ($sale->total != $total)){
                $this->saveSalesHistory($saleId, 'VENTA Actualizada', $sale->discount, $discount, $total2 , $sale->total);
            }
            
            Sale::where('id', $saleId)
            ->update(['subtotal'=>$total, 'total'=>$total2, 'discount'=>$discount, 'status'=>1, 'withCash'=>$withcash, 'clientId'=>$clientId, 'companyPosId'=>$companyPosId ]);

            return response()->json(['status'=>'success', 'message'=>'La venta se actualizo']);
        // } catch (\Throwable $th) {
        //     return response()->json(['status'=>'error', 'message'=>'Error al actualizar la venta']);
        // }
    }

    public function saveSalesHistory(string $saleId, string $action, Int $discount, Int $newdiscount, float $newTotal, float $lastTotal){
        $salesHistory = new SalesHistory();
        $salesHistory->saleId = $saleId;
        $salesHistory->action = $action;
        $salesHistory->discount = $discount;
        $salesHistory->newDiscount = $newdiscount;
        $salesHistory->newTotal = $newTotal;
        $salesHistory->lastTotal = $lastTotal;
        $salesHistory->userid = Auth::id();
        $salesHistory->save();
    }

    public function change(Sale $sale, Int $discount, Int $withcash)
    {
        $total = SalesDetail::select('id', 'total')->where('saleId', $sale->id)->sum('total');

        $total2 = $total;
        if($discount>0){
            switch ($discount) {
                case 20:
                    $desc = $total * 0.20;
                    break;
                case 25:
                    $desc = $total * 0.25;
                    break;
                case 30:
                    $desc = $total * 0.30;
                    break;
                case 35:
                    $desc = $total * 0.35;
                    break;
            }
            $total2 = $total - $desc;
        }

        $affected = Sale::where('id', $sale->id)
           ->update(['subtotal' => $total, 'total' => $total2, 'discount' => $discount, 'status' => 1, 'withCash' => $withcash]);

        return redirect()->route('report.sales');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Pedido eliminado');
    }

    public function changetable(Request $request): JsonResponse
    {
        $rows = Sale::where('tableId', $request->tableId)
            ->where('status', 0)
            ->count();
        if($rows == 0) {
            $sale = Sale::find($request->saleId);
            $sale->tableId = $request->tableId;
            $sale->update();

            $table = Table::where('id', $request->tableId)->first();

            return response()->json(['status'=>'success', 'message'=>'Se cambio al cliente de mesa', 'table'=>$table->name]);
        }else{
            return response()->json(['status'=>'error', 'message'=>'Selecciones otra mesa, esta ya esta asignada a un cliente']);
        }
    }

    ////

    public function list(): View
    {
        $sales = $this->listAllSales();

        $tables = Table::all();

        $heads = $this->getHeadsSalesList();

        return view('salelist.list', ['sales' => $sales, 'heads' => $heads, 'tables' => $tables]);
    }

    public function detail(Request $request): View
    {
        $sale = Sale::select('sales.id as saleId', 'subtotal', 'discount', 'total', 'status', 'withCash','created_at', 'tables.name as table')
             ->join('tables', 'tables.id','=','sales.tableId')
             ->where('sales.id', $request->saleId)->first();

        $products = Product::all();

        $salesDetails = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
            ->join('products', 'products.id','=','sales_detail.productId')
            ->where('sales_detail.saleId', $request->saleId)->get();

        return view('salelist.detail', ['sale' => $sale, 'products' => $products, 'salesDetails' => $salesDetails]);
    }

    public function detailorder(Request $request): View
    {
        $sale = Sale::select('sales.id as saleId', 'subtotal', 'sales.discount', 'total', 'status', 'withCash', 'sales.created_at', 'sales.clientId', 'sales.updated_at',
            'tables.name as table', 'clients.name as client', 'users.name as user', 'paybox.state as payboxState', 'sales.companyPosId', 'sales.splitNumber', 'sunat', 
            'voucherType','voucherNumber', 'voucherSerie')
             ->join('tables', 'tables.id','=','sales.tableId')
             ->join('clients', 'clients.id','=','sales.clientId')
             ->join('users', 'users.id','=','sales.userId')
             ->leftjoin('paybox', 'paybox.id','=','sales.payboxId')
             ->where('sales.id', $request->saleId)->first();

        $products = Product::all();

        $clients = Client::all();

        $salesDetails = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
            ->join('products', 'products.id','=','sales_detail.productId')
            ->where('sales_detail.saleId', $request->saleId)->get();

        $companyPosList = CompanyPos::all();    

        return view('reports.detail', ['sale' => $sale, 'products' => $products, 'salesDetails' => $salesDetails, 'clients' => $clients, 'companyPosList' => $companyPosList]);
    }

    public function cancelsale(Request $request): JsonResponse
    {
        $sale = Sale::find($request->saleId);
        if($sale->voucherType > 0) {
            return response()->json(['status'=>'error', 'message'=>'No se puede anular una venta con comprobantes de venta emitidos.']);
        }
        
        $this->saveSalesHistory($sale->id, 'VENTA Anulada', $sale->discount, $sale->discount, $sale->total, $sale->total);
        
        $sale->status = 2;
        $sale->update();

        return response()->json(['status'=>'success', 'message'=>'La venta fue anulada']);
    }

    public function removesale(Request $request): JsonResponse
    {
        $sale = Sale::find($request->saleId);
        if($sale->voucherType > 0) {
            return response()->json(['status'=>'error', 'message'=>'No se puede anular una venta con comprobantes de venta emitidos.']);
        }
        
        $sale->status = 2;
        //$sale->update();

        return response()->json(['status'=>'success', 'message'=>'La venta fue anulada']);
    }

    public function nullify(Request $request): RedirectResponse
    {
        $affected = Sale::where('id', $request->saleId)->update(['status' => 2]);

        return redirect()->route('report.sales')->with('success', 'Venta Anulada');
    }

    public function split(Request $request): View
    {
        $sale = Sale::select('sales.id as saleId', 'subtotal', 'sales.discount', 'total', 'status', 'withCash', 'tableId', 'payboxId', 'created_at', 'updated_at', 
            'tables.name as table', 'clients.name as client', 'clientId', 'paybox.state as payboxState')
             ->join('tables', 'tables.id','=','sales.tableId')
             ->join('clients', 'clients.id','=','sales.clientId')
             ->leftjoin('paybox', 'paybox.id','=','sales.payboxId')
             ->where('sales.id', $request->saleId)->first();

        return view('reports.split', ['sale' => $sale]);
    }

    protected function listAllSales()
    {
        $sales = Sale::select('sales.id', 'total', 'status', 'sales.created_at', 'tables.name as table', 'tables.placeId as placeId', 'places.place as place')
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')->where('sales.status','!=', 0)->orderBy('sales.id','DESC')->take(100)->get();

        return $sales;
    }

    protected function getHeadsSalesList()
    {
        $heads = [
            'ID',
            'Lugar',
            'Mesa',
            'Fecha',
            'Estado',
            'Acciones'
        ];
        return $heads;
    }

    protected function getHeads()
    {
        $heads = [
            'ID',
            'Lugar',
            'Mesa',
            'Fecha',
            'Acciones'
        ];
        return $heads;
    }

    protected function printTicket(int $saleId, int $discount, int $withCash, int $clientId, String $tableName, int $companyPosId)
    {
        $RUC = env('DATA_COMPANY_RUC','10238228379');
        $company = Company::all()->where('ruc', $RUC)->first();
        if($companyPosId == 0){ $companyPosId = null; }

        $now = date('d/m/Y');
        $hour = date('H:i');

        $salesDetails = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
        ->join('products', 'products.id','=','sales_detail.productId')
        ->where('sales_detail.saleId', $saleId)->get();
        
        $print_name = env('DATA_COMPANY_POS','POS-80C');
        $connector = new WindowsPrintConnector($print_name);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($company->slogan . "\n");
        $printer->text($company->address . "\n");
        $printer->text("Mesa: " . $tableName . " - Ticket Nro: #" . $saleId . "\n");
        $printer->text("Fecha: " . $now . "     Hora: " . $hour . "\n");
        $printer->feed(1);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("-----------------------------------------------\n");
        $total1 = 0;
        foreach ($salesDetails as $row) {
            $total1 += $row->total;
            $qty = $row->quantity." x";
            $name = trim($row->product);

            $mask = "%-40.40s\n";
            $line = sprintf($mask, $name);
            $line .= sprintf("%4s %15.2f %15.2f\n", $qty, $row->price, $row->total);
            $printer->text("$line");
        }
        $printer->text("-----------------------------------------------\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("     TOTAL: S/ ". $total1 ."\n");

        $total2 = $total1;
        if($discount > 0){
            $printer->text("     Descuento por promoción - " . $discount . "%\n");
            $desc = $total1 * ($discount / 100);
            $total2 = $total1 - $desc;
            $printer->text("     TOTAL: S/ ". $total2 ."\n");
        }

        Sale::where('id', $saleId)
            ->update(['subtotal' => $total1, 'total' => $total2, 'discount' => $discount, 'status' => 1, 
                'withCash' => $withCash, 'clientId' => $clientId, 'companyPosId' => $companyPosId]);

        $printer->feed(2);

        $printer->text($company->website);
        $printer->feed(2);

        $printer->cut();
        $printer->close();
    }

    private function printOrder(Sale $sale, String $inCharge, String $tableName, String $user)
    {
        $list = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
        ->join('products', 'products.id','=','sales_detail.productId')
        ->where('sales_detail.saleId', $sale->id)
        ->where('products.inCharge', $inCharge)->get();

        if(count($list)){
            $print_name = env('DATA_COMPANY_POS','POS-80C');
            $connector = new WindowsPrintConnector($print_name);
            $printer = new Printer($connector);

            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text($inCharge." - Ticket Nro: #".$sale->id." - Mesa: ".$tableName." \n");
            $printer->text("Fecha: ".date_format($sale->created_at,"d-m-Y g:i A")."\n");
            $printer->text("Mozo: " . $user . "\n");
            $printer->feed(1);

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("-----------------------------------------------\n");
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            foreach ($list as $row) {
                $qty = $row->quantity;
                $name = trim($row->product);
                $line = sprintf("%-3s %-30.30s \n",$qty, $name);
                $printer->text("$line");
            }

            $printer->feed(2);

            $printer->cut();
            $printer->close();

            SalesDetail::where('saleId', $sale->id)
                ->update(['printOrder' => 1]);
        }
    }

    private function printBoleta(float $tigv, float $tgravada, float $ttotal, string $serie, int $snumber, string $client, string $dni, int $discount, \Illuminate\Database\Eloquent\Collection $salesDetails) {

        $now = date('d/m/Y');
        $hour = date('H:i');

        $RUC = env('DATA_COMPANY_RUC','10238228379');
        $company = Company::all()->where('ruc', $RUC)->first();
        $IGV = $company->igv;

        $print_name = env('DATA_COMPANY_POS','POS-80C');
        $connector = new WindowsPrintConnector($print_name);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("RESTAURANTE" . "\n");
        $printer->text($company->slogan . "\n");
        $printer->text("RUC: " . $company->ruc . "\n");
        $printer->text($company->address . "\n");
        $printer->text("Telf: " . $company->phone . "\n");
        if($company->website!=""){
            $printer->text($company->website . "\n");
        }

        $printer->text("-----------------------------------------------\n");
        $printer->text("Boleta de venta electrónica" . "\n");
        $printer->text($serie . "-" . sprintf('%08s', $snumber) . "\n");
        $printer->text("-----------------------------------------------\n");

        $printer->text("Fecha: " . $now . "     Hora: " . $hour . "\n");
        $printer->text("Cliente: " . $client . "\n");
        $printer->text("DNI: " . $dni . "\n");

        $printer->feed(1);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("-----------------------------------------------\n");
        foreach ($salesDetails as $row) {
            $name = trim($row->product);
            $qty = $row->quantity;
            $price = $row->price;

            if($discount > 0){
                $desc = $price * ($discount / 100); 
                $price = $price - $desc;
            }
            
            $total = $price * $qty;
            $qty = $qty . " x";

            $mask = "%-40.40s\n";
            $line = sprintf($mask, $name);
            $line .= sprintf("%4s %15.2f %15.2f\n", $qty, $price, $total);
            $printer->text("$line");

            //echo($line . "<br>");
        }
        $printer->text("-----------------------------------------------\n");

        $printer->setJustification(Printer::JUSTIFY_RIGHT);

        $printer->text("Op. Gravada: S/ ". number_format((float)$tgravada, 2, '.', '') ."\n");
        $printer->text("IGV(" . $IGV . "%): S/ ". number_format((float)$tigv, 2, '.', '') ."\n");
        $printer->text("TOTAL: S/ ". number_format((float)$ttotal, 2, '.', '') ."\n");
        $printer->text("-----------------------------------------------\n");
        //echo(number_format((float)$tgravada, 2, '.', '') . " - " . number_format((float)$tigv, 2, '.', '') . " - " . number_format((float)$ttotal, 2, '.', '') . "<br>");

        $totalLetter = Myhelpers::numberToLetter($ttotal);
        $printer->text("Son: ". $totalLetter ."\n");
        $printer->text("-----------------------------------------------\n");

        $printer->feed(2);

        $printer->cut();
        $printer->close();
    }

    private function printFactura(float $tigv, float $tgravada, float $ttotal, string $serie, int $snumber, string $client, string $ruc, string $address, int $discount, \Illuminate\Database\Eloquent\Collection $salesDetails) {

        $now = date('d/m/Y');
        $hour = date('H:i');

        $RUC = env('DATA_COMPANY_RUC','10238228379');
        $company = Company::all()->where('ruc', $RUC)->first();
        $IGV = $company->igv;

        $print_name = env('DATA_COMPANY_POS','POS-80C');
        $connector = new WindowsPrintConnector($print_name);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("RESTAURANTE" . "\n");
        $printer->text($company->slogan . "\n");
        $printer->text("RUC: " . $company->ruc . "\n");
        $printer->text($company->address . "\n");
        $printer->text("Telf: " . $company->phone . "\n");
        if($company->website!=""){
            $printer->text($company->website . "\n");
        }

        $printer->text("-----------------------------------------------\n");
        $printer->text("FACTURA ELECTRÓNICA" . "\n");
        $printer->text($serie . "-" . sprintf('%08s', $snumber) . "\n");
        $printer->text("-----------------------------------------------\n");

        $printer->text("Fecha: " . $now . "     Hora: " . $hour . "\n");
        $printer->text("Cliente: " . $client . "\n");
        $printer->text("RUC: " . $ruc . "\n");
        if($address!=""){
            $printer->text($address . "\n");
        }

        $printer->feed(1);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("-----------------------------------------------\n");
        
        foreach ($salesDetails as $row) {
            $name = trim($row->product);
            $qty = $row->quantity;
            $price = $row->price;

            if($discount > 0){
                $desc = $price * ($discount / 100); 
                $price = $price - $desc;
            }

            $total = $price * $qty;
            $qty = $qty . " x";

            $mask = "%-40.40s\n";
            $line = sprintf($mask, $name);
            $line .= sprintf("%4s %15.2f %15.2f\n", $qty, $price, $total);
            $printer->text("$line");
        }
        $printer->text("-----------------------------------------------\n");

        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        
        $printer->text("Op. Gravada: S/ ". number_format((float)$tgravada, 2, '.', '') ."\n");
        $printer->text("IGV(" . $IGV . "%): S/ ". number_format((float)$tigv, 2, '.', '') ."\n");
        $printer->text("TOTAL: S/ ". number_format((float)$ttotal, 2, '.', '') ."\n");
        $printer->text("-----------------------------------------------\n");

        $totalLetter = Myhelpers::numberToLetter($ttotal);
        $printer->text("Son: ". $totalLetter ."\n");
        $printer->text("-----------------------------------------------\n");

        $printer->feed(2);

        $printer->cut();
        $printer->close();
    }

    public function available(Request $request): View
    {
        $paybox = PayBox::select('id')->where('state','=', 1);
        $payboxId = 0;
        if($paybox->count() > 0){
            $payboxId = $paybox->first()->id;
        }
        return view('sales.available', ['payboxId' => $payboxId]);
    }
    
    public function tablelist(Request $request): JsonResponse
    {
        $list = Table::select('tables.id', 'tables.name', 'tables.ability', 'tables.placeId', 
            DB::raw('(SELECT COUNT(*) FROM sales WHERE sales.tableId = tables.id AND sales.status = 0 AND sales.splitNumber = 0) AS salesCount'),
            'places.place', 'tables.active', 'tables.state')
            ->join('places', 'places.id', '=', 'tables.placeId')
            ->where('tables.active', 1)
            ->orderBy('tables.name', 'asc')
            ->get();
        
        return response()->json(['status'=>'success', 'list' => $list]);    
    }

    public function takeorder(Request $request)
    {
        $paybox = PayBox::select('id')->where('state','=', 1)->where('startDate', '>=', Carbon::now()->subDays(1)->toDateTimeString())->count();
        if($paybox==0){
            return redirect()->route('sales.available')->with('warning', 'Es necesario aperturar la CAJA');
        }else{
            $rows = Sale::all()->where('tableId', $request->tableId)->where('status', 0);
            if($rows->count()>0){
                $saleId = $rows->first()->id;
                return redirect()->route('sales.show', ['saleId' => $saleId]);
            }else{
                $sale = new Sale();
                $sale->tableId = $request->tableId;
                $sale->userId = $request->userId;
                $sale->total = 0;
                $sale->status = 0;
                $sale->payboxId = $request->payboxId;
                $sale->save();

                return redirect()->route('sales.show', ['saleId' => $sale->id]);
            }
        }
    }

    public function reprint(Request $request){
        $ids = explode(',', $request->ids);
        $_ids = array();
        for($index = 0; $index < count($ids); $index++){
            array_push($_ids, $ids[$index]);    
        }        
        
        $list = SalesDetail::select('sales_detail.id', 'sales_detail.quantity', 'products.name as product')
            ->whereIn('sales_detail.id', $_ids)
            ->join('products', 'products.id', '=', 'sales_detail.productId')
            ->get();

        $print_name = env('DATA_COMPANY_POS','POS-80C');
        $connector = new WindowsPrintConnector($print_name);
        $printer = new Printer($connector);

        $sale = Sale::select('sales.id', 'sales.tableId', 'tables.name as table', 'users.name as user', 'sales.created_at')
            ->join('tables', 'tables.id', '=', 'sales.tableId')
            ->join('users', 'users.id', '=', 'sales.userId')
            ->where('sales.id', $request->saleId)
            ->first();
        
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Ticket Nro: #".$sale->id." - Mesa: ".$sale->table." \n");
        $printer->text("Fecha: ".date_format($sale->created_at,"d-m-Y g:i A")."\n");
        $printer->text("Mozo: " . $sale->user . "\n");
        $printer->feed(1);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("-----------------------------------------------\n");
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
        foreach ($list as $row) {
            $qty = $row->quantity;
            $name = trim($row->product);
            $line = sprintf("%-3s %-30.30s \n", $qty, $name);
            $printer->text("$line");
        }

        $printer->feed(2);

        $printer->cut();
        $printer->close();

        SalesDetail::where('saleId', $sale->id)->update(['printOrder' => 1]);

        return response()->json(['status'=>'success', 'message'=>'Ticket Impreso']);    
    }
}
