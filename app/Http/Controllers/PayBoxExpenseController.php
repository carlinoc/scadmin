<?php

namespace App\Http\Controllers;

use App\Models\PayBoxExpense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\View\View;

class PayBoxExpenseController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = PayBoxExpense::select('payboxexpense.id', 'expenseDate', 'expense', 'payboxexpense.description', 'expenseType', 'staffPayType', 'voucherType', 
                'voucherNumber', 'providerId', 'serviceId', 'staffId', 'otherPayId', 
                'provider.name as provider', 'service.service as service', 'staff.name as staff', 'otherpay.motive as motive')
            ->leftJoin('service', 'service.id' , '=', 'payboxexpense.serviceId')
            ->leftJoin('staff', 'staff.id' , '=', 'payboxexpense.staffId')
            ->leftJoin('otherpay', 'otherpay.id' , '=', 'payboxexpense.otherpayId')
            ->leftJoin('provider', 'provider.id' , '=', 'payboxexpense.providerId')
            ->where('payBoxId', $request->payboxId);
            
        $list = $query->get();
        $query2 = $query;     
        $totalExpense = $query2->sum('expense');
        
        return response()->json(['status'=>'success', 'list' => $list, 'totalExpense' => $totalExpense]);
    }

    public function add(Request $request): JsonResponse 
    {
        $payBoxExpense = new PayBoxExpense();
        $payBoxExpense->expenseDate = Carbon::now();
        $payBoxExpense->expense = $request->expense;
        $payBoxExpense->description = $request->description;
        $payBoxExpense->expenseType = $request->expenseType;

        $expenseType = $request->expenseType;

        if ($expenseType == 1) {
            $payBoxExpense->providerId = $request->providerId;    
        }

        if ($expenseType == 2) {
            $payBoxExpense->serviceId = $request->serviceId;    
        }

        if ($expenseType == 3) {
            $payBoxExpense->staffId = $request->staffId;    
            $payBoxExpense->staffPayType = $request->staffPayType;    
        }

        if ($expenseType == 4) {
            $payBoxExpense->otherPayId = $request->otherPayId;    
        }

        if($expenseType != 3) {
            $payBoxExpense->voucherType = $request->voucherType;
            $payBoxExpense->voucherNumber = $request->voucherNumber;    
        }
        
        $payBoxExpense->payBoxId = $request->payboxId;
        $payBoxExpense->save();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $payBoxExpense = PayBoxExpense::find($request->payboxExpenseId);
        //$payBoxExpense->expenseDate = Carbon::now();
        //$payBoxExpense->expense = $request->expense;
        $payBoxExpense->description = $request->description;

        $payBoxExpense->providerId = null;
        $payBoxExpense->serviceId = null;
        $payBoxExpense->staffId = null;
        $payBoxExpense->staffPayType = null;
        $payBoxExpense->otherPayId = null;

        $expenseType = $request->expenseType;

        if ($expenseType == 1) {
            $payBoxExpense->providerId = $request->providerId;    
        }

        if ($expenseType == 2) {
            $payBoxExpense->serviceId = $request->serviceId;    
        }

        if ($expenseType == 3) {
            $payBoxExpense->staffId = $request->staffId;    
            $payBoxExpense->staffPayType = $request->staffPayType;    
        }

        if ($expenseType == 4) {
            $payBoxExpense->otherPayId = $request->otherPayId;    
        }

        if($expenseType != 3) {
            $payBoxExpense->voucherType = $request->voucherType;
            $payBoxExpense->voucherNumber = $request->voucherNumber;    
        }

        $payBoxExpense->payBoxId = $request->payboxId;
        $payBoxExpense->update();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        PayBoxExpense::find($request->posExpenseId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El gasto fue eliminado']);     
    }    

}