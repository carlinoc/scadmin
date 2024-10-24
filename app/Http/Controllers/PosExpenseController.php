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

        $query = PosExpense::select('posexpense.id', DB::raw("DATE_FORMAT(posexpense.expenseDate, '%d-%m-%Y %H:%i') as expenseTime"), 'expense', 'posexpense.description', 'expenseType',
            'staffPayType', 'voucherType', 'voucherNumber', 'provider.name as provider', 'service.service', 'staff.name as staffName', 'otherpay.motive', 'staffPayType', 'providerId',
            'serviceId', 'otherPayId', 'staffId')
            ->leftJoin('provider', 'provider.id' , '=', 'posexpense.providerId')
            ->leftJoin('service', 'service.id' , '=', 'posexpense.serviceId')
            ->leftJoin('staff', 'staff.id' , '=', 'posexpense.staffId')
            ->leftJoin('otherpay', 'otherpay.id' , '=', 'posexpense.otherpayId')
            ->where('companyPosId', $request->companyPosId);
                    
        switch($dateFilter){
            case 'today':
                $query->whereDate('posexpense.expenseDate', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('posexpense.expenseDate', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('posexpense.expenseDate', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('posexpense.expenseDate', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('posexpense.expenseDate', Carbon::now()->month)->whereYear('posexpense.expenseDate', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('posexpense.expenseDate', Carbon::now()->subMonth()->month)->whereYear('posexpense.expenseDate', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('posexpense.expenseDate', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));
                
                $query->whereBetween('posexpense.expenseDate', [$start_date.' 00:00:00', $end_date.' 23:59:59']);

                // if ($end_date->greaterThan($start_date)) {
                //     $query->whereBetween('posexpense.expenseDate', [$start_date, $end_date]);
                // } else {
                //     $query->whereDate('posexpense.expenseDate', Carbon::today());
                // }           
                break;           
        } 
        
        $query->orderBy('posexpense.expenseDate');
        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $time = Carbon::now()->toTimeString();
        $date = Carbon::parse($request->expenseDate)->toDateString();
        $expenseDate = Carbon::parse($date . $time);

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
        $time = Carbon::now()->toTimeString();
        $date = Carbon::parse($request->expenseDate)->toDateString();
        $expenseDate = Carbon::parse($date . $time);

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
