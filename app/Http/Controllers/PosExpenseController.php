<?php

namespace App\Http\Controllers;

use App\Models\PosExpense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PosExpenseController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;

        $query = PosExpense::select('posexpense.id', DB::raw("DATE_FORMAT(posexpense.expenseDate, '%d-%m-%Y %H:%i') as expenseDate"), 'expense', 'posexpense.description', 'expenseType',
            'staffPayType', 'voucherType', 'voucherNumber', 'provider.name as provider', 'service.service', 'staff.name as staffName', 'otherpay.motive', 'staffPayType', 'providerId',
            'serviceId', 'otherPayId', 'staffId')
            ->leftJoin('provider', 'provider.id' , '=', 'posexpense.providerId')
            ->leftJoin('service', 'service.id' , '=', 'posexpense.serviceId')
            ->leftJoin('staff', 'staff.id' , '=', 'posexpense.staffId')
            ->leftJoin('otherpay', 'otherpay.id' , '=', 'posexpense.otherpayId')
            ->where('companyPosId', $request->companyPosId)
            ->orderBy('posexpense.expenseDate', 'ASC');
        
        switch($dateFilter){
            case 'today':
                $query->whereDate('expenseDate', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('expenseDate', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('expenseDate', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('expenseDate', [Carbon::now()->subWeek(), Carbon::now()]);
                break;
            case 'this_month':
                $query->whereMonth('expenseDate', Carbon::now()->month)->whereYear('expenseDate', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('expenseDate', Carbon::now()->subMonth()->month)->whereYear('expenseDate', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('expenseDate', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                
                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('expenseDate', [$start_date, $end_date]);
                } else {
                    $query->whereDate('expenseDate', Carbon::today());
                }           
                break;           
        } 
        
        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $expenseDate = Carbon::parse($request->expenseDate);
        $posExpense = new PosExpense();
        
        $expenseType = $request->expenseType;

        if ($expenseType == 1) {
            $posExpense->providerId = $request->providerId;    
        }

        if ($expenseType == 2) {
            $posExpense->serviceId = $request->serviceId;    
        }

        if ($expenseType == 3) {
            $posExpense->staffId = $request->staffId;    
            $posExpense->staffPayType = $request->staffPayType;    
        }

        if ($expenseType == 4) {
            $posExpense->otherPayId = $request->otherPayId;    
        }

        if($expenseType != 3) {
            $posExpense->voucherType = $request->voucherType;
            $posExpense->voucherNumber = $request->voucherNumber;    
        }

        $posExpense->expenseType = $expenseType;
        $posExpense->expenseDate = $expenseDate;
        $posExpense->expense = $request->expense;
        $posExpense->description = $request->description;
        $posExpense->companyPosId = $request->companyPosId;
        $posExpense->save();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $expenseDate = Carbon::parse($request->expenseDate);
        $posExpense = PosExpense::find($request->posexpenseId);

        $expenseType = $request->expenseType;

        if ($expenseType == 1) {
            $posExpense->providerId = $request->providerId;    
        }

        if ($expenseType == 2) {
            $posExpense->serviceId = $request->serviceId;    
        }

        if ($expenseType == 3) {
            $posExpense->staffId = $request->staffId;    
            $posExpense->staffPayType = $request->staffPayType;    
        }

        if ($expenseType == 4) {
            $posExpense->otherPayId = $request->otherPayId;    
        }

        if($expenseType != 3) {
            $posExpense->voucherType = $request->voucherType;
            $posExpense->voucherNumber = $request->voucherNumber;    
        }

        $posExpense->expenseType = $expenseType;
        $posExpense->expenseDate = $expenseDate;
        $posExpense->expense = $request->expense;
        $posExpense->description = $request->description;
        $posExpense->companyPosId = $request->companyPosId;
        $posExpense->update();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        PosExpense::find($request->posExpenseId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El gasto fue eliminado']);     
    }
}
