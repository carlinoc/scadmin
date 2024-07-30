<?php

namespace App\Http\Controllers;

use App\Models\PosIncome;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PosIncomeController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;

        $query = PosIncome::select('id', DB::raw("DATE_FORMAT(incomeDate, '%d-%m-%Y %H:%i') as incomeDate"), 'income', 'description', 'operationNumber')
            ->where('companyPosId', $request->companyPosId)
            ->orderBy('incomeDate', 'ASC');

        switch($dateFilter){
            case 'today':
                $query->whereDate('incomeDate', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('incomeDate', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('incomeDate', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('incomeDate', [Carbon::now()->subWeek(), Carbon::now()]);
                break;
            case 'this_month':
                $query->whereMonth('incomeDate', Carbon::now()->month)->whereYear('incomeDate', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('incomeDate', Carbon::now()->subMonth()->month)->whereYear('incomeDate', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('incomeDate', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                
                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('incomeDate', [$start_date, $end_date]);
                } else {
                    $query->whereDate('incomeDate', Carbon::today());
                }           
                break;           
        } 
        
        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $incomeDate = Carbon::parse($request->incomeDate);
        $posIncome = new PosIncome();
        $posIncome->incomeDate = $incomeDate;
        $posIncome->income = $request->income;
        $posIncome->operationNumber = $request->operationNumber;
        $posIncome->description = $request->description;
        $posIncome->companyPosId = $request->companyPosId;
        $posIncome->save();

        return response()->json(['status'=>'success', 'message'=>'El depósito fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $incomeDate = Carbon::parse($request->incomeDate);
        $posIncome = PosIncome::find($request->posincomeId);
        $posIncome->incomeDate = $incomeDate;
        $posIncome->income = $request->income;
        $posIncome->operationNumber = $request->operationNumber;
        $posIncome->description = $request->description;
        $posIncome->companyPosId = $request->companyPosId;
        $posIncome->update();

        return response()->json(['status'=>'success', 'message'=>'El depósito fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        PosIncome::find($request->posIncomeId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El depósito fue eliminado']);     
    }
}
