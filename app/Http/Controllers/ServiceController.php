<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('service.index');
    }

    public function list(Request $request): JsonResponse
    {
        $list = Service::all();

        return response()->json(['list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $service = new Service();
        $service->service = $request->service;
        $service->description = $request->description;
        $service->save();

        return response()->json(['status'=>'success', 'message'=>'El servicio fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $service = Service::find($request->serviceId);
        $service->service = $request->service;
        $service->description = $request->description;
        $service->update();

        return response()->json(['status'=>'success', 'message'=>'El servicio fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        $rows1 = DB::table('mainbox')->where('serviceId', $request->serviceId)->count();
        $rows2 = DB::table('payboxexpense')->where('serviceId', $request->serviceId)->count();
        $counts = (int)$rows1 + (int)$rows2;

        if($counts > 0){
            return response()->json(['status'=>'error', 'message'=>'El registro no puede ser eliminado']);
        }

        Service::find($request->serviceId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El servicio fue eliminado']);     
    }

    public function detail(Request $request): View
    {
        $service = Service::find($request->serviceId);
        return view('service.detail', ['service' => $service]);
    }

    public function listexpense(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;
        $serviceId = $request->serviceId;

        $list1 = DB::table("mainbox")->select('id', 'created_at as expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('1 as boxType'))
            ->where('serviceId', $serviceId)
            ->where('state', 0);

        $list2 = DB::table("payboxexpense")->select('id','expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('2 as boxType'))
            ->where('serviceId', $serviceId)      
            ->where('expenseType', 2);
        
        $list3 = DB::table("posexpense")->select('id','expenseDate', 'expense', 'description', 'voucherType', 'voucherNumber', DB::raw('3 as boxType'))
            ->where('serviceId', $serviceId)      
            ->where('expenseType', 2);    

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
