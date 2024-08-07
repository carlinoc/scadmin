<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\SalesHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
}
