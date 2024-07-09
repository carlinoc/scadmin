<?php

namespace App\Http\Controllers;

use App\Models\CompanySerial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanySerialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function list(Request $request): JsonResponse
    {
        //dd($request->serietype);
        $companySerial = CompanySerial::where('serieType', $request->serietype)->first();
        return response()->json(['companySerial' => $companySerial]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companySerial = CompanySerial::find($request->companySerialId);
        $companySerial->serieType = $request->serieType;
        $companySerial->serie = $request->serie;
        $companySerial->number = $request->number;
        $companySerial->update();

        return response()->json(['status'=>'success', 'message'=>'Los datos del comprobante fue actualizado']);    

    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySerial $companySerial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanySerial $companySerial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanySerial $companySerial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanySerial $companySerial)
    {
        //
    }
}
