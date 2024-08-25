<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\TipsPercent;
use App\Models\Client;
use App\Models\CompanyPos;
use App\Models\PayBox;
use App\Models\PayBoxExpense;
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

    public function tips(): View
    {
        $tipsPercent = TipsPercent::all();
        return view('reports.tips', ['tipsPercent' => $tipsPercent]);
    }

    public function saleslist(Request $request)
    {
        $dateFilter = $request->dateRange;
        $withCash = $request->withCash;
        $filterpay = $request->filterpay;

        $query = Sale::select('sales.id', 'sales.subtotal', 'sales.discount', 'sales.total', 'sales.status', 'sales.withCash', 
            DB::raw("DATE_FORMAT(sales.created_at, '%d-%m-%Y %H:%i') as createdDate"), 'tables.name as table', 'tables.placeId as placeId', 
            'places.place as place', 'places.place as pay', 'companypos.pos', 'sales.voucherType',
            'sales.tips', 'sales.tipsType', 'sales.sunat', 'sales.voucherSerie', 'sales.voucherNumber',
            DB::raw('(SELECT COUNT(*) FROM saleshistory WHERE saleshistory.saleId = sales.id) AS history_count'),)
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')
            ->leftjoin('companypos', 'companypos.id','=','sales.companyPosId');

        if($withCash<4){
            $query->where('sales.withCash','=', $withCash);
        }    

        if($filterpay<3){
            $query->where('sales.status','=', $filterpay);
        }

        $payboxId = 0;
        if($request->currentPayBox!=""){
            $paybox = PayBox::select('id')->where('state','=', 1);
            if($paybox->count() > 0){
                $payboxId = $paybox->first()->id;
                $query->where('sales.payboxId','=', $payboxId);
            }
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
        $query2 = clone $query;
        $query3 = clone $query;

        $withYape = $query->where('sales.withCash','=', 2)->sum('sales.total');
        $totalSales = $query2->where('sales.withCash','!=', 3)->sum('sales.total');
        $withCash = $query2->where('sales.withCash','=', 0)->sum('sales.total');
        $withCard = $query3->where('sales.withCash','=', 1)->sum('sales.total');
        
        return response()->json(['status'=>'success', 'sales' => $sales, 'totalSales' => $totalSales, 'withCard' => $withCard, 'withCash' => $withCash, 'withYape' => $withYape]);    
    }

    public function receivablelist(Request $request)
    {
        $dateFilter = $request->dateRange;
        $clientId = $request->clientId;

        $query = Sale::select('sales.id', 'sales.subtotal', 'sales.discount', 'sales.total', 'sales.status', 'sales.withCash', 
            DB::raw("DATE_FORMAT(sales.created_at, '%d-%m-%Y %H:%i') as createdDate"), 'tables.name as table', 'clients.name as client')
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('clients', 'clients.id', '=', 'sales.clientId')
            ->where('sales.withCash','=', 3);

        if($clientId!=0){
            $query->where('sales.clientId','=', $clientId);
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
        
        return response()->json(['status'=>'success', 'sales' => $sales]);    
    }

    public function lastorders(): View
    {
        $users = User::all();
        return view('reports.lastorders', ['users' => $users]);
    }

    public function lastorderslist(Request $request)
    {
        $dateRange = $request->dateRange;

        $query = Sale::select('sales.id', 'subtotal', 'discount', 'total', 'status', 'withCash', 'tables.name as table', 'tables.placeId as placeId',
            DB::raw("DATE_FORMAT(sales.created_at, '%d %b %Y %H:%i') as createdDate"), 'places.place as place')
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')
            ->where('sales.status','=', 1);

        $userId = $request->userId;    
        if($userId!=0){
            $query->where('sales.userId', $request->userId);
        }   

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
        if($withCash<4){
            $query->where('sales.withCash','=', $withCash);
        }        

        $sales = $query->get();    
        $query2 = $query; 
        $totalSales = $query2->sum('sales.total');

        return response()->json(['sales' => $sales, 'totalSales' => $totalSales]);
    }

    public function salesporcobrar(Request $request){
        $query = Sale::select('sales.id', 'sales.total', DB::raw("DATE_FORMAT(sales.updated_at, '%d-%m-%Y %H:%i') as dateUpdate"), 'tables.name as table', 
                 'users.name as user', 'paybox.state as payboxState', 'clients.name as client')
            ->join('tables', 'tables.id','=', 'sales.tableId')
            ->join('users', 'users.id','=','sales.userId')
            ->join('paybox', 'paybox.id','=','sales.payboxId') 
            ->join('clients', 'clients.id', '=', 'sales.clientId')
            ->where('sales.status', 1)
            ->where('sales.payboxId', $request->payboxid); 

        $withCash = $request->withcash;    
        if($withCash<4){
            $query->where('sales.withCash','=', $withCash);
        }        

        $sales = $query->get();    
        $query2 = $query; 
        $totalSales = $query2->sum('sales.total');

        return response()->json(['sales' => $sales, 'totalSales' => $totalSales]);
    }

    public function tipslist(Request $request)
    {
        $dateFilter = $request->dateRange;
        $tipsType = $request->tipsType;

        $query = Sale::select('sales.id', 'sales.subtotal', 'sales.discount', 'sales.total', 'sales.status', 'sales.withCash', 
            DB::raw("DATE_FORMAT(sales.created_at, '%d-%m-%Y %H:%i') as createdDate"), 'companypos.pos', 'sales.tips', 'sales.tipsType')
            ->leftjoin('companypos', 'companypos.id','=','sales.companyPosId')
            ->where('sales.status', '=', 1)
            ->where('sales.tips', '>', 0);

        if($tipsType<3) {
            $query->where('sales.tipsType','=', $tipsType);
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

        $totalTips = $query2->sum('sales.tips');
        $tipsCash = $query2->where('sales.tipsType','=', 1)->sum('sales.tips');
        $tipsCard = round(($totalTips - $tipsCash), 2);

        return response()->json(['status'=>'success', 'sales' => $sales, 'totalTips' => $totalTips, 'tipsCash' => $tipsCash, 'tipsCard' => $tipsCard]);    
    }

    public function receivable(): View
    {
        $list = Client::all();
        $companyPosList = CompanyPos::all();

        return view('reports.receivable', ['list' => $list, 'companyPosList' => $companyPosList]);
    }

    public function receivableadd(Request $request)
    {
        $saleId = $request->saleId;
        $withCash = $request->withCash;
        $companyPosId = $request->companyPosId;
        $nowDate = Carbon::now();

        $paybox = PayBox::select('id')->where('state','=', 1)->where('startDate', '>=', Carbon::now()->subDays(1)->toDateTimeString());
        $rows = $paybox->count();
        if($rows==0){
            return response()->json(['status'=>'error', 'message'=>'Es necesario aperturar la CAJA']);    
        }else{
            $payBoxId = $paybox->get()[0]["id"];

            $sale = Sale::find($saleId);
            $sale->withCash = $withCash;
            $sale->updated_at = $nowDate;
            $sale->created_at = $nowDate;
            if($request->whitCash == 1){
                $sale->companyPosId = $companyPosId;    
            }
            $sale->payboxId = $payBoxId;
            $sale->update();

            return response()->json(['status'=>'success', 'message'=>'El pago se realizo correctamente']);        
        }
    }

    public function sunat(Request $request)
    {
        $saleId = $request->saleId;
        
        $sale = Sale::find($saleId);
        $sale->sunat = $request->sunat;
        $sale->update();

        return response()->json(['status'=>'success', 'message'=>'Se actualizó sunat']);        
    }

    public function topfood(Request $request)
    {
        // $query = Sale::select('sales.id', 'sales.subtotal', 'sales.discount', 'sales.total', 'sales.status', 'sales.withCash', 
        //     DB::raw("DATE_FORMAT(sales.created_at, '%d-%m-%Y %H:%i') as createdDate"), 'companypos.pos', 'sales.tips', 'sales.tipsType')
        //     ->leftjoin('companypos', 'companypos.id','=','sales.companyPosId')
        //     ->where('sales.status', '=', 1)
        //     ->where('sales.tips', '>', 0);

        return response()->json(['status'=>'success']);    
    }
}
