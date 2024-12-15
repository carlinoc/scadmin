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
        $query = PayBoxExpense::select('payboxexpense.id', 'expenseDate', 'expense', 'payboxexpense.description', 'payboxexpense.expenseType', 'staffPayType', 'voucherType', 
                'voucherNumber', 'providerId', 'serviceId', 'staffId', 'otherPayId', 
                'provider.name as provider', 'service.service as service', 'staff.name as staff', 'otherpay.motive as motive',
                'expensecategories.category as category', 'expensecategories.id as expensecategoryId', 'expensecategories.parentId')
            ->leftjoin('expensecategories', 'expensecategories.id', '=', 'payboxexpense.expensecategoryId')
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

        $expenseCategoryId = $request->subCategoryId;
        if($expenseCategoryId == "") {
            $expenseCategoryId = $request->expensecategoryId;
        }
        $payBoxExpense->expensecategoryId = $expenseCategoryId;

        if($request->providerId != "") {
            $payBoxExpense->providerId = $request->providerId;
        }
        if($request->serviceId != "") {
            $payBoxExpense->serviceId = $request->serviceId;
        }
        if($request->staffId != "") {
            $payBoxExpense->staffId = $request->staffId;
        }
        if($request->otherPayId != "") {
            $payBoxExpense->otherPayId = $request->otherPayId;
        }
        
        $payBoxExpense->voucherType = $request->voucherType;
        $payBoxExpense->voucherNumber = $request->voucherNumber;    
        $payBoxExpense->payBoxId = $request->payboxId;
        $payBoxExpense->save();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $payBoxExpense = PayBoxExpense::find($request->payboxExpenseId);
        //$payBoxExpense->expenseDate = Carbon::now();
        if($request->payboxState == 1) {
            $payBoxExpense->expense = $request->expense;
        }
        $payBoxExpense->description = $request->description;
        $payBoxExpense->expenseType = $request->expenseType;

        $payBoxExpense->providerId = null;
        $payBoxExpense->serviceId = null;
        $payBoxExpense->staffId = null;
        $payBoxExpense->staffPayType = 0;
        $payBoxExpense->otherPayId = null;

        $expenseCategoryId = $request->subCategoryId;
        if($expenseCategoryId == "") {
            $expenseCategoryId = $request->expensecategoryId;
        }
        $payBoxExpense->expensecategoryId = $expenseCategoryId;

        if($request->providerId != "") {
            $payBoxExpense->providerId = $request->providerId;
        }
        if($request->serviceId != "") {
            $payBoxExpense->serviceId = $request->serviceId;
        }
        if($request->staffId != "") {
            $payBoxExpense->staffId = $request->staffId;
        }
        if($request->otherPayId != "") {
            $payBoxExpense->otherPayId = $request->otherPayId;
        }

        $payBoxExpense->voucherType = $request->voucherType;
        $payBoxExpense->voucherNumber = $request->voucherNumber;
        $payBoxExpense->expenseType = $request->expenseType;
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