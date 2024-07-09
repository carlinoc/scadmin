<?php

namespace App\Http\Controllers;

use App\Models\SaleSplit;
use Illuminate\Http\Request;

class SaleSplitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    
    public function add(Request $request)
    {
        $saleSplit = new SaleSplit();
        $saleSplit->saleId = $request->saleId;
        $saleSplit->save();

        return response()->json(['status'=>'success', 'message'=>'SubCuenta creada', 'splitId'=>$saleSplit->id]);    
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleSplit $saleSplit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleSplit $saleSplit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleSplit $saleSplit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleSplit $saleSplit)
    {
        //
    }
}
