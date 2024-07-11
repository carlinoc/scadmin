<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(): View
    {
        
        $heads = [
            'ID',
            'Lugar',
            'Mesa',
            'Fecha',
            'Acciones'
        ];

        return view('reports.sales', ['heads' => $heads]);
    }

    public function saleslist(Request $request)
    {
        $dateFilter = $request->dateRange;
        $withCash = $request->withCash;
        $filterpay = $request->filterpay;

        $query = Sale::select('sales.id', 'sales.subtotal', 'sales.discount', 'sales.total', 'sales.status', 'sales.withCash', 
            DB::raw("DATE_FORMAT(sales.created_at, '%d-%m-%Y %H:%i') as createdDate"), 'tables.name as table', 'tables.placeId as placeId', 
            'places.place as place', 'places.place as pay', 'companypos.pos', 'sales.voucherType',
            'sales.tips', 'sales.tipsType',
            DB::raw('(SELECT COUNT(*) FROM saleshistory WHERE saleshistory.saleId = sales.id) AS history_count'),)
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')
            ->leftjoin('companypos', 'companypos.id','=','sales.companyPosId');

        if($withCash<3){
            $query->where('sales.withCash','=', $withCash);
        }    

        if($filterpay<3){
            $query->where('sales.status','=', $filterpay);
        }
        
        switch($dateFilter){
            case 'today':
                $query->whereDate('sales.created_at',Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('sales.created_at',Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('sales.created_at',[Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('sales.created_at',[Carbon::now()->subWeek(),Carbon::now()]);
                break;
            case 'this_month':
                $query->whereMonth('sales.created_at',Carbon::now()->month)->whereYear('sales.created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('sales.created_at',Carbon::now()->subMonth()->month)->whereYear('sales.created_at',Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('sales.created_at',Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                
                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('sales.created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('sales.created_at',Carbon::today());
                }           
                break;           
        }  
        $sales = $query->get();    
        $query2 = $query; 
        $totalSales = $query2->sum('sales.total');
        $withCash = $query2->where('sales.withCash','=', 0)->sum('sales.total');
        $withCard = round(($totalSales - $withCash), 2);
        
        return response()->json(['status'=>'success', 'sales' => $sales, 'totalSales' => $totalSales, 'withCard' => $withCard, 'withCash' => $withCash]);    
    }

    public function lastorders(): View
    {
        $users = User::all();
        return view('reports.lastorders', ['users' => $users]);
    }

    public function lastorderslist(Request $request)
    {
        $dateRange = $request->dateRange;

        $query = Sale::select('sales.id', 'subtotal', 'discount', 'total', 'status', 'withCash', DB::raw("DATE_FORMAT(sales.created_at, '%d-%m-%Y %H:%i') as createdDate"), 'tables.name as table', 'tables.placeId as placeId', 'places.place as place')
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')
            ->where('sales.status','=', 1)
            ->where('sales.userId', $request->userId);

        switch($dateRange){
            case 'today':
                $query->whereDate('sales.created_at',Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('sales.created_at',Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('sales.created_at',[Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('sales.created_at',[Carbon::now()->subWeek(), Carbon::now()]);
                break;
            case 'this_month':
                $query->whereMonth('sales.created_at',Carbon::now()->month)->whereYear('sales.created_at',Carbon::now()->year);
                break;
        }  
        $sales = $query->get();    
        
        return response()->json(['status'=>'success', 'sales' => $sales]);    
    }

    public function payboxsales(Request $request){
        $query = Sale::select('sales.id', 'sales.total', DB::raw("DATE_FORMAT(sales.updated_at, '%d-%m-%Y %H:%i') as dateUpdate"), 'tables.name as table', 'users.name as user', 'paybox.state as payboxState')
            ->join('tables', 'tables.id','=', 'sales.tableId')
            ->join('users', 'users.id','=','sales.userId')
            ->join('paybox', 'paybox.id','=','sales.payboxId') 
            ->where('sales.status', 1)
            ->where('sales.payboxId', $request->payboxid); 

        $withCash = $request->withcash;    
        if($withCash<2){
            $query->where('sales.withCash','=', $withCash);
        }        

        $sales = $query->get();    
        $query2 = $query; 
        $totalSales = $query2->sum('sales.total');

        return response()->json(['sales' => $sales, 'totalSales' => $totalSales]);
    }
}
