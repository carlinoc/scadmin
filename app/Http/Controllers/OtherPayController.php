<?php

namespace App\Http\Controllers;

use App\Models\OtherPay;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OtherPayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('otherpay.index');
    }

    public function list(Request $request): JsonResponse
    {
        $list = OtherPay::all();

        return response()->json(['list' => $list]);
    }

    public function add(Request $request): JsonResponse
    {
        $isParent = $request->isParent;
        $parentId = $request->parentId1;
        $parentId2 = $request->parentId2;

        if($parentId2 != ""){
            $parentId = $parentId2;
        }

        $otherPay = new OtherPay();
        if($isParent == ""){
            $otherPay->parentId = $parentId;
        }else{
            $otherPay->isParent = 1;    
        }
        $otherPay->motive = $request->motive;
        $otherPay->description = $request->description;
        $otherPay->save();

        return response()->json(['status'=>'success', 'message'=>'El motivo de pago fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $otherPay = OtherPay::find($request->otherpayId);
        $otherPay->motive = $request->motive;
        $otherPay->description = $request->description;
        $otherPay->update();

        return response()->json(['status'=>'success', 'message'=>'El motivo de pago fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        $rows1 = DB::table('mainbox')->where('otherpayId', $request->otherpayId)->count();
        $rows2 = DB::table('payboxexpense')->where('otherpayId', $request->otherpayId)->count();
        $counts = (int)$rows1 + (int)$rows2;

        if($counts > 0){
            return response()->json(['status'=>'error', 'message'=>'El registro no puede ser eliminado']);
        }

        OtherPay::find($request->otherpayId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El motivo de pago fue eliminado']);     
    }

    public function detail(Request $request): View
    {
        $otherpay = OtherPay::find($request->otherpayId);
        return view('otherpay.detail', ['otherpay' => $otherpay]);
    }

    public function listexpense(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;
        $otherpayId = $request->otherpayId;

        $list1 = DB::table("mainbox")->select('id', 'created_at as expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('1 as boxType'))
            ->where('otherpayId', $otherpayId)
            ->where('state', 0);

        $list2 = DB::table("payboxexpense")->select('id','expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('2 as boxType'))
            ->where('otherpayId', $otherpayId)      
            ->where('expenseType', 4);

        $list3 = DB::table("posexpense")->select('id','expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('3 as boxType'))
            ->where('otherpayId', $otherpayId)      
            ->where('expenseType', 4);    

        $allUnions = $list1->union($list2)->union($list3);
        $query = DB::query()->fromSub($allUnions , 'fq'); 

        switch($dateFilter){
            case 'today':
                $query->whereDate('fq.expenseDate', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('fq.expenseDate', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('fq.expenseDate', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek()->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek()->toDateString();
                $query->whereBetween('fq.expenseDate', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('fq.expenseDate', Carbon::now()->month)->whereYear('fq.expenseDate', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('fq.expenseDate', Carbon::now()->subMonth()->month)->whereYear('fq.expenseDate', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('fq.expenseDate', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                
                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('fq.expenseDate', [$start_date, $end_date]);
                } else {
                    $query->whereDate('fq.expenseDate', Carbon::today());
                }           
                break;           
        }
        $query = $query->orderBy('fq.expenseDate', 'desc');  
        
        $list = $query->get();
        $totalExpense = $query->sum('expense');
            
        return response()->json(['status'=>'success', 'list' => $list, 'totalExpense' => $totalExpense]);    
    }
}
