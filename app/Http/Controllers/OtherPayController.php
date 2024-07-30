<?php

namespace App\Http\Controllers;

use App\Models\OtherPay;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class OtherPayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('otherpay.index');
    }

    public function list(Request $request): JsonResponse
    {
        $list = OtherPay::all();

        return response()->json(['list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $otherPay = new OtherPay();
        $otherPay->motive = $request->motive;
        $otherPay->description = $request->description;
        $otherPay->save();

        return response()->json(['status'=>'success', 'message'=>'El motivo de pago fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $otherPay = OtherPay::find($request->otherpayId);
        $otherPay->motive = $request->motive;
        $otherPay->description = $request->description;
        $otherPay->update();

        return response()->json(['status'=>'success', 'message'=>'El motivo de pago fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        OtherPay::find($request->otherPayId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El motivo de pago fue eliminado']);     
    }
}
