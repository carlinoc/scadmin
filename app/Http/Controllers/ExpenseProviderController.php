<?php

namespace App\Http\Controllers;

use App\Models\ExpenseProvider;
use App\Models\PayBox;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function add(Request $request): JsonResponse 
    {
        $expenseProvider = new ExpenseProvider();
        $expenseProvider->expenseDate = Carbon::now();
        $expenseProvider->voucherNumber = $request->voucherNumber;
        $expenseProvider->voucherType = $request->voucherType;
        $expenseProvider->amount = $request->amount;
        $expenseProvider->description = $request->description;      
        $expenseProvider->providerId = $request->providerId;
        $expenseProvider->payBoxId = $request->payboxId;
        $expenseProvider->save();

        $payBox = PayBox::find($request->payboxId);
        $payBox->expenses = ($payBox->expenses + $request->amount);
        $payBox->update();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue agregado']);    
    }

    public function list(Request $request): JsonResponse
    {
        $query = ExpenseProvider::select('expenseprovider.id', DB::raw("DATE_FORMAT(expenseprovider.expenseDate, '%d-%m-%Y %H:%i') as expenseDate"), 'expenseprovider.amount', 'expenseprovider.providerId', 'provider.name as provider')
            ->join('provider', 'provider.id', '=', 'expenseprovider.providerId')
            ->where('expenseprovider.payboxId', $request->payboxId);
                    
        $expenseProviders = $query->get();
        $query2 = $query;     
        $totalAmount = $query2->sum('expenseprovider.amount');

        return response()->json(['expenseProviders' => $expenseProviders, 'totalAmount' => $totalAmount]);
    }

    public function remove(Request $request): JsonResponse
    {
        $expenseProvider = ExpenseProvider::find($request->expenseProviderId);
        $payboxId = $expenseProvider->payboxId;
        $amount = $expenseProvider->amount;

        $payBox = PayBox::find($payboxId);
        $payBox->expenses = ($payBox->expenses - $amount);
        $payBox->update();

        $expenseProvider->delete();      

        return response()->json(['status'=>'success', 'message'=>'El gasto fue eliminado', 'expenses' => $payBox->expenses]);     
    }
}
