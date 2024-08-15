<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\PayBoxExpense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('provider.index');
    }

    public function list(Request $request): JsonResponse
    {
        $providers = Provider::all();

        return response()->json(['providers' => $providers]);
    }

    public function add(Request $request): JsonResponse 
    {
        $provider = new Provider();
        $provider->name = $request->name;
        $provider->phone = $request->phone;
        $provider->contactName = $request->contactName;
        $provider->contactPhone = $request->contactPhone;
        $provider->address = $request->address;
        $provider->paymentMethod = $request->paymentMethod;
        if($request->paymentMethod=="Yape"){
            $provider->yapeNumber = $request->yapeNumber;
        }
        if($request->paymentMethod=="Plin"){
            $provider->plinNumber = $request->plinNumber;
        }
        if($request->paymentMethod=="Transferencia"){
            $provider->accountNumber = $request->accountNumber;
        }
        $provider->description = $request->description;
        $provider->save();

        return response()->json(['status'=>'success', 'message'=>'El proveedor fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $provider = Provider::find($request->providerId);
        $provider->name = $request->name;
        $provider->phone = $request->phone;
        $provider->contactName = $request->contactName;
        $provider->contactPhone = $request->contactPhone;
        $provider->address = $request->address;
        $provider->paymentMethod = $request->paymentMethod;
        if($request->paymentMethod=="Yape"){
            $provider->yapeNumber = $request->yapeNumber;
        }
        if($request->paymentMethod=="Plin"){
            $provider->plinNumber = $request->plinNumber;
        }
        if($request->paymentMethod=="Transferencia"){
            $provider->accountNumber = $request->accountNumber;
        }
        $provider->description = $request->description;
        $provider->update();

        return response()->json(['status'=>'success', 'message'=>'El proveedor fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        Provider::find($request->providerId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El proveedor fue eliminado']);     
    }

    public function detail(Request $request): View
    {
        $provider = Provider::find($request->providerId);
        return view('provider.detail', ['provider' => $provider]);
    }

    public function listpayments(Request $request): JsonResponse
    {
        $list = PayBoxExpense::select('id', 'expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber')
            ->where('expenseType', 1)
            ->where('providerId', $request->providerId)
            ->get();
            
        return response()->json(['status'=>'success', 'list' => $list]);    
    }
}
