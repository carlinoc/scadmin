<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\PayBoxExpense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('provider.index');
    }

    public function list(Request $request): JsonResponse
    {
        $providers = Provider::all();

        return response()->json(['providers' => $providers]);
    }

    public function add(Request $request): JsonResponse 
    {
        $provider = new Provider();
        $provider->name = $request->name;
        $provider->phone = $request->phone;
        $provider->contactName = $request->contactName;
        $provider->contactPhone = $request->contactPhone;
        $provider->address = $request->address;
        $provider->paymentMethod = $request->paymentMethod;
        if($request->paymentMethod=="Yape"){
            $provider->yapeNumber = $request->yapeNumber;
        }
        if($request->paymentMethod=="Plin"){
            $provider->plinNumber = $request->plinNumber;
        }
        if($request->paymentMethod=="Transferencia"){
            $provider->accountNumber = $request->accountNumber;
        }
        $provider->description = $request->description;
        $provider->save();

        return response()->json(['status'=>'success', 'message'=>'El proveedor fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $provider = Provider::find($request->providerId);
        $provider->name = $request->name;
        $provider->phone = $request->phone;
        $provider->contactName = $request->contactName;
        $provider->contactPhone = $request->contactPhone;
        $provider->address = $request->address;
        $provider->paymentMethod = $request->paymentMethod;
        if($request->paymentMethod=="Yape"){
            $provider->yapeNumber = $request->yapeNumber;
        }
        if($request->paymentMethod=="Plin"){
            $provider->plinNumber = $request->plinNumber;
        }
        if($request->paymentMethod=="Transferencia"){
            $provider->accountNumber = $request->accountNumber;
        }
        $provider->description = $request->description;
        $provider->update();

        return response()->json(['status'=>'success', 'message'=>'El proveedor fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        Provider::find($request->providerId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El proveedor fue eliminado']);     
    }

    public function detail(Request $request): View
    {
        $provider = Provider::find($request->providerId);
        return view('provider.detail', ['provider' => $provider]);
    }

    public function listpayments(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;
        $providerId = $request->providerId;

        $list1 = DB::table("mainbox")->select('id', 'created_at as expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('null as pos'), DB::raw('1 as boxType'))
            ->where('providerId', $providerId)
            ->where('state', 0);

        $list2 = DB::table("payboxexpense")->select('id','expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('null as pos'), DB::raw('2 as boxType'))
            ->where('providerId', $providerId)      
            ->where('expenseType', 1);

        $list3 = DB::table("posexpense")->select('posexpense.id','posexpense.expenseDate', 'posexpense.expense', 'posexpense.description', 'posexpense.voucherType', 
            'posexpense.voucherNumber', 'companypos.pos', DB::raw('3 as boxType'))
            ->join('companypos', 'companypos.id', '=', 'posexpense.companyPosId')
            ->where('providerId', $providerId)      
            ->where('expenseType', 1);    

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
