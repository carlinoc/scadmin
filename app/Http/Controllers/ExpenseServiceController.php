<?php

namespace App\Http\Controllers;

use App\Models\ExpenseService;
use App\Models\PayBox;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseServiceController extends Controller
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
        $expenseService = new ExpenseService();
        $expenseService->expenseDate = Carbon::now();
        $expenseService->voucherNumber = $request->voucherNumber;
        $expenseService->voucherType = $request->voucherType;
        $expenseService->amount = $request->amount;
        $expenseService->description = $request->description;      
        $expenseService->serviceId = $request->serviceId;
        $expenseService->payBoxId = $request->payboxId;
        $expenseService->save();

        $payBox = PayBox::find($request->payboxId);
        $payBox->expenses = ($payBox->expenses + $request->amount);
        $payBox->update();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue agregado']);    
    }

    public function list(Request $request): JsonResponse
    {
        $query = ExpenseService::select('expenseservice.id', DB::raw("DATE_FORMAT(expenseservice.expenseDate, '%d-%m-%Y %H:%i') as expenseDate"), 'expenseservice.amount', 'expenseservice.serviceId', 'service.service as service')
            ->join('service', 'service.id', '=', 'expenseservice.serviceId')
            ->where('expenseservice.payboxId', $request->payboxId);
                    
        $expenseServices = $query->get();
        $query2 = $query;     
        $totalAmount = $query2->sum('expenseservice.amount');

        return response()->json(['expenseServices' => $expenseServices, 'totalAmount' => $totalAmount]);
    }

    public function remove(Request $request): JsonResponse
    {
        $expenseService = ExpenseService::find($request->expenseServiceId);
        $payboxId = $expenseService->payboxId;
        $amount = $expenseService->amount;

        $payBox = PayBox::find($payboxId);
        $payBox->expenses = ($payBox->expenses - $amount);
        $payBox->update();

        $expenseService->delete();      

        return response()->json(['status'=>'success', 'message'=>'El gasto fue eliminado', 'expenses' => $payBox->expenses]);     
    }    
}
