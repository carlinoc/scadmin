<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\PayBoxExpense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

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

        $query = PayBoxExpense::select('id', 'expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber')
            ->where('expenseType', 1)
            ->where('providerId', $providerId);

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
        $totalExpense = $query->sum('expense');
            
        return response()->json(['status'=>'success', 'list' => $list, 'totalExpense' => $totalExpense]);    
    }
}
