<?php

namespace App\Http\Controllers;

use App\Models\Provider;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        //
    }
}
