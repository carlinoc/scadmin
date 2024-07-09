<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\PayBox;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $payBox = PayBox::find($request->payboxId);
        return view('income.index', ['payBox' => $payBox]);
    }

    public function add(Request $request): JsonResponse 
    {
        $income = new Income();
        $income->payBoxId = $request->payboxId;
        $income->incomeDate = Carbon::now();
        $income->voucherNumber = $request->voucherNumber;
        $income->voucherType = $request->voucherType;
        $income->incomeconceptId = $request->incomeconceptId;
        $income->amount = $request->amount;
        $income->description = $request->description;      
        $income->save();

        $payBox = PayBox::find($request->payboxId);
        $payBox->income = ($payBox->income + $request->amount);
        $payBox->update();

        return response()->json(['status'=>'success', 'message'=>'El ingreso fue agregado']);    
    }

    public function list(Request $request): JsonResponse
    {
        $query = Income::select('income.id', DB::raw("DATE_FORMAT(income.incomeDate, '%d-%m-%Y %H:%i') as incomeDate"), 'income.amount', 'incomeconcept.name as concept')
            ->join('incomeconcept', 'incomeconcept.id', '=', 'income.incomeconceptId')
            ->where('income.payBoxId', '=', $request->payboxId);
                
        $incomes = $query->get();
        $query2 = $query;     
        $totalAmount = $query2->sum('income.amount');

        return response()->json(['incomes' => $incomes, 'totalAmount' => $totalAmount]);
    }

    public function remove(Request $request): JsonResponse
    {
        $income = Income::find($request->incomeId);
        $payboxId = $income->payboxId;
        $amount = $income->amount;

        $payBox = PayBox::find($payboxId);
        $payBox->income = ($payBox->income - $amount);
        $payBox->update();

        $income->delete();      

        return response()->json(['status'=>'success', 'message'=>'El ingreso fue eliminado', 'income' => $payBox->income]);     
    }
}
