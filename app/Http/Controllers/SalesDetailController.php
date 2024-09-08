<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\SalesHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Models\Table;
use App\Models\User;
use App\Models\PayBox;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesDetailController extends Controller
{

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'saleId' => 'required',
            'total' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'productId' => 'required'      
        ]);

        $detail = new SalesDetail();
        $detail->price = $request->price; 
        $detail->quantity = $request->quantity; 
        $detail->total = $request->total; 
        $detail->saleId = $request->saleId; 
        $detail->productId = $request->productId; 
        $detail->save();

        $sale = Sale::find($request->saleId);
        $sale->touch();

        $product = Product::find($request->productId);
        if($product->useInventory=1){
            $product->stock = $product->stock - $request->quantity;
            $product->update();
        }

        if($request->saveHistory == 1) {
            $this->saveSalesHistory($request->saleId, 'Producto Agregado', $sale->discount, $sale->total, $request->productId, $request->quantity, $request->quantity);    
        }

        return response()->json(['status'=>'success', 'message'=>'El producto fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $request->validate([
            'total' => 'required',
            'quantity' => 'required',
            'price' => 'required'
        ]);

        $saleDetail = SalesDetail::find($request->saleDetailId);
        
        if($saleDetail->quantity < $request->quantity){
            $product = Product::find($saleDetail->productId);
            if($product->useInventory==1){
                $quantity = $request->quantity - $saleDetail->quantity;
                $product->stock = $product->stock - $quantity;
                $product->update();
            }
        }else{
            $product = Product::find($saleDetail->productId);
            if($product->useInventory==1){
                $quantity = $saleDetail->quantity - $request->quantity;
                $product->stock = $product->stock + $quantity;
                $product->update();
            }
        }

        $lastQuantity = $saleDetail->quantity;

        $saleDetail->price = $request->price; 
        $saleDetail->quantity = $request->quantity; 
        $saleDetail->total = $request->total; 
        $saleDetail->update();

        $sale = Sale::find($request->saleId);
        $sale->touch();

        if($request->saveHistory == 1) {
            $this->saveSalesHistory($request->saleId, 'Producto Editado', $sale->discount, $sale->total, $saleDetail->productId, $lastQuantity, $request->quantity);    
        }

        return response()->json(['status'=>'success', 'message'=>'El producto fue actualizado']);    
    }

    public function remove(Request $request)
    {
        $saleDetail = SalesDetail::find($request->saleDetailId);

        $sale = Sale::find($saleDetail->saleId);
        $sale->touch();

        $product = Product::find($saleDetail->productId);
        if($product->useInventory==1){
            $product->stock = $product->stock + $saleDetail->quantity;
            $product->update();
        }

        $lastQuantity = $saleDetail->quantity;
        if($request->saveHistory == 1) {
            $this->saveSalesHistory($saleDetail->saleId, 'Producto Eliminado', $sale->discount, $sale->total, $saleDetail->productId, $lastQuantity, $lastQuantity);    
        }

        $saleDetail->delete();
        
        return response()->json(['status'=>'success', 'message'=>'El producto fue eliminado']);    
    }

    public function print(Request $request)
    {
        $saleDetail = SalesDetail::select('sales_detail.id', 'sales_detail.quantity', 'products.name as product', 'products.inCharge', 'sales_detail.saleId')
            ->join('products', 'products.id', '=', 'sales_detail.productId')
            ->where('sales_detail.id', $request->saleDetailId)
            ->first();

        $sale = Sale::find($saleDetail->saleId);
        $table = Table::find($sale->tableId);
        $user = User::find($sale->userId);

        //$this->printOrder($sale, '$request->inCharge', $table->name, $user->name, $saleDetail->product->name, $saleDetail->quantity);
        $this->printOrder($sale, $saleDetail->inCharge, $table->name, $user->name, $saleDetail->product, $saleDetail->quantity);

        SalesDetail::where('id', $request->saleDetailId)->update(['printOrder' => 1]);
                
        return response()->json(['status'=>'success', 'message'=>'Se imprimio la comanda']);    
    }

    public function saveSalesHistory(string $saleId, string $action, Int $discount, float $total, int $productId, int $quantity, int $newquantity){
        $salesHistory = new SalesHistory();
        $salesHistory->saleId = $saleId;
        $salesHistory->action = $action;
        $salesHistory->discount = $discount;
        $salesHistory->newDiscount = $discount;
        $salesHistory->newTotal = $total;
        $salesHistory->lastTotal = $total;
        $salesHistory->userid = Auth::id();
        $salesHistory->productId = $productId;
        $salesHistory->quantity = $quantity;
        $salesHistory->newquantity = $newquantity;
        $salesHistory->save();
    }


    private function printOrder(Sale $sale, String $inCharge, String $tableName, String $user, String $product, int $quantity)
    {
        $print_name = env('DATA_COMPANY_POS','POS-80C');
        $connector = new WindowsPrintConnector($print_name);
        $printer = new Printer($connector);

        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("SANCRIS - RESTOBAR" . "\n");
        $printer->text($inCharge." - Ticket Nro: #".$sale->id." - Mesa: ".$tableName." \n");
        $printer->text("Fecha: ".date_format($sale->created_at,"d-m-Y g:i A")."\n");
        $printer->text("Mozo: " . $user . "\n");
        $printer->feed(1);

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("-----------------------------------------------\n");

        $qty = $quantity;
        $name = trim($product);
        $line = sprintf("%-3s %-30.30s \n", $qty, $name);
        $printer->text("$line");

        $printer->feed(2);

        $printer->cut();
        $printer->close();
    }

    public function list(Request $request){
        $list = SalesDetail::select('sales_detail.id','sales_detail.price', 'sales_detail.quantity', 'sales_detail.total', 'sales_detail.productId', 'products.name as product')
            ->join('products', 'products.id','=','sales_detail.productId')
            ->where('sales_detail.saleId', $request->saleId)
            ->get();

        return response()->json(['status'=>'success', 'list' => $list]);    
    }    

    public function addsale(Request $request)
    {
        $rows = Sale::all()->where('parentId', $request->saleId)->count();
        $rows = $rows + 1;

        $sale = new Sale();
        $sale->tableId = $request->tableId;
        $sale->userId = Auth::user()->id;
        $sale->total = 0;
        $sale->status = 0;
        $sale->payboxId = $request->payboxId;
        $sale->parentId = $request->saleId;
        $sale->clientId = $request->clientId;
        $sale->splitNumber = $rows;
        $sale->save();

        return response()->json(['status'=>'success', 'message'=>'Se agrego la cuenta', 'saleId' => $sale->id, 'rows' => $rows]);    
    }

    public function splitlist(Request $request){

        $list = Sale::with(['detail' => function($query){
                $query->select('sales_detail.id', 'sales_detail.saleId', 'sales_detail.price', 'sales_detail.quantity', 'sales_detail.total', 
                    'sales_detail.productId', 'products.name as product')
                    ->join('products', 'products.id', '=', 'sales_detail.productId');
            }])
            ->where('parentId', $request->saleId)
            ->orderBy('splitNumber', 'desc')
            ->get();

        return response()->json(['status'=>'success', 'list' => $list]);    
    }

    public function adddetail(Request $request)
    {
        $oldsaleId = $request->oldsaleId;
        $saleId = $request->saleId;
        $saledetailId = $request->saledetailId;
        
        $saleDetail = SalesDetail::where('id', $saledetailId)->first();
        $quantity = $saleDetail->quantity;
        $price = $saleDetail->price;
        $productId = $saleDetail->productId;     

        if($quantity > 1){
            $detail = new SalesDetail();
            $detail->price = $price; 
            $detail->quantity = 1; 
            $detail->total = $price; 
            $detail->saleId = $saleId; 
            $detail->productId = $productId; 
            $detail->save();

            $quantity = $quantity - 1;
            SalesDetail::where('id', $saledetailId)->update(['quantity' => $quantity, 'total' => $price * $quantity]);

        }else{
            //SalesDetail::where('id', $saledetailId)->delete();
            SalesDetail::where('id', $saledetailId)->update(['saleId' => $saleId]);
        }

        $this->updateTotals( $saleId, $oldsaleId);

        return response()->json(['status'=>'success', 'message'=>'Se agrego el producto']);
    }

    public function removedetail(Request $request){
        $oldsaleId = $request->oldsaleId;
        $saledetailId = $request->saledetailId;    
        
        $saleDetail = SalesDetail::where('id', $saledetailId)->first();
        $saleId = $saleDetail->saleId;

        SalesDetail::where('id', $saledetailId)->update(['saleId' => $oldsaleId]);
        
        $this->updateTotals($saleId, $oldsaleId);

        return response()->json(['status'=>'success', 'message'=>'Se quito el producto']);
    } 

    private function updateTotals( $saleId, $oldsaleId){
        $total = SalesDetail::select('id', 'total')->where('saleId', $saleId)->sum('total');

        $sale = Sale::find($saleId);
        $sale->total = $total;
        $sale->subtotal = $total;
        $sale->update();

        $total1 = SalesDetail::select('id', 'total')->where('saleId', $oldsaleId)->sum('total');

        $sale = Sale::find($oldsaleId);
        $sale->total = $total1;
        $sale->subtotal = $total1;
        $sale->update();
    }


    public function sendticket(Request $request)
    {
        $clientId = 1;
        $discount = $request->discount;
        
        $sale = Sale::find($request->saleId);
        $table = Table::find($sale->tableId);
        $tableName = $table->name;
        if($sale->splitNumber > 0){
            $tableName = $table->name . ' - ' . $sale->splitNumber;    
        }

        try{
            $this->printTicket($sale->id, $discount, 0, $clientId, $tableName);
        } catch (\Throwable $th) {
            return response()->json(['status'=>'error', 'message'=>'Error al imprimir el ticket']);
        }

        return response()->json(['status'=>'success', 'message'=>'El ticket fue imprimido']);
    }

    protected function printTicket(int $saleId, int $discount, int $withCash, int $clientId, String $tableName)
    {
        $now = date('d/m/Y');
        $hour = date('H:i');

        $salesDetails = SalesDetail::select('sales_detail.id','sales_detail.price', 'quantity', 'total', 'products.name as product', 'products.id as productId')
        ->join('products', 'products.id','=','sales_detail.productId')
        ->where('sales_detail.saleId', $saleId)->get();
        
        $print_name = env('DATA_COMPANY_POS','POS-80C');
        $connector = new WindowsPrintConnector($print_name);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("SANCRIS - RESTOBAR" . "\n");
        $printer->text("Pumacurco #650, San Cristobal - Cusco / Perú" . "\n");
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
            ->update(['subtotal' => $total1, 'total' => $total2, 'discount' => $discount, 'status' => 1, 'withCash' => $withCash, 'clientId' => $clientId]);

        $printer->feed(2);

        $printer->text("Muchas gracias por su visita, vuelva pronto.\n");
        $printer->feed(2);

        $printer->cut();
        $printer->close();
    }
}
