<?php

namespace App\Http\Controllers;

use App\Models\PayBoxIncome;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\View\View;

class PayBoxIncomeController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $list = PayBoxIncome::select('payboxincome.id', 'incomeDate', 'income', 'payboxincome.description', 'payBoxId', 'incomeconceptId', 'incomeconcept.name as incomeConcept')
            ->join('incomeconcept', 'incomeconcept.id', '=', 'payboxincome.incomeconceptId')
            ->where('payBoxId', $request->payboxId)
            ->get();
        
        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $payBoxIncome = new PayBoxIncome();
        $payBoxIncome->incomeDate = Carbon::now();
        $payBoxIncome->income = $request->income;
        $payBoxIncome->description = $request->description;
        $payBoxIncome->payBoxId = $request->payboxId;
        $payBoxIncome->incomeconceptId = $request->incomeconceptId;
        $payBoxIncome->save();

        return response()->json(['status'=>'success', 'message'=>'El ingreso fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $payBoxIncome = PayBoxIncome::find($request->payboxIncomeId);
        //$payBoxIncome->incomeDate = Carbon::now();
        //$payBoxIncome->income = $request->income;
        $payBoxIncome->description = $request->description;
        $payBoxIncome->payBoxId = $request->payboxId;
        $payBoxIncome->incomeconceptId = $request->incomeconceptId;
        $payBoxIncome->update();

        return response()->json(['status'=>'success', 'message'=>'El ingreso fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        PayBoxIncome::find($request->payboxIncomeId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El ingreso fue eliminado']);     
    }
}
