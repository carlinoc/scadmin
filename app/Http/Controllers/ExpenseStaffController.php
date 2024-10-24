<?php

namespace App\Http\Controllers;

use App\Models\ExpenseStaff;
use App\Models\PayBox;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseStaffController extends Controller
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
        $expenseStaff = new ExpenseStaff();
        $expenseStaff->expenseDate = Carbon::now();
        $expenseStaff->amount = $request->amount;
        $expenseStaff->description = $request->description;      
        $expenseStaff->concept = $request->concept;
        $expenseStaff->staffId = $request->staffId;
        $expenseStaff->payBoxId = $request->payboxId;
        $expenseStaff->save();

        $payBox = PayBox::find($request->payboxId);
        $payBox->expenses = ($payBox->expenses + $request->amount);
        $payBox->update();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue agregado']);    
    }

    public function list(Request $request): JsonResponse
    {
        $query = ExpenseStaff::select('expensestaff.id', DB::raw("DATE_FORMAT(expensestaff.expenseDate, '%d-%m-%Y %H:%i') as expenseDate"), 'expensestaff.amount', 'expensestaff.concept', 'staff.name as staff')
            ->join('staff', 'staff.id', '=', 'expensestaff.staffId')
            ->where('expensestaff.payboxId', $request->payboxId);
                                
        $expenseStaffs = $query->get();
        $query2 = $query;     
        $totalAmount = $query2->sum('expensestaff.amount');

        return response()->json(['expenseStaffs' => $expenseStaffs, 'totalAmount' => $totalAmount]);
    }

    public function remove(Request $request): JsonResponse
    {
        $expenseStaff = ExpenseStaff::find($request->expenseStaffId);
        $payboxId = $expenseStaff->payboxId;
        $amount = $expenseStaff->amount;

        $payBox = PayBox::find($payboxId);
        $payBox->expenses = ($payBox->expenses - $amount);
        $payBox->update();

        $expenseStaff->delete();      

        return response()->json(['status'=>'success', 'message'=>'El gasto fue eliminado', 'expenses' => $payBox->expenses]);     
    }
}
