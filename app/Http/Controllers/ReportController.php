<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\TipsPercent;
use App\Models\Client;
use App\Models\CompanyPos;
use App\Models\MainBox;
use App\Models\PayBox;
use App\Models\PayBoxExpense;
use App\Models\PosExpense;
use App\Models\SalesDetail;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Provider;
use App\Models\Service;
use App\Models\OtherPay;
use App\Models\ExpenseCategories;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

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

        $companyPosList = CompanyPos::all();

        return view('reports.sales', ['heads' => $heads, 'companyPosList' => $companyPosList]);
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
        $companyPosId = $request->companyPosId;

        $query = Sale::select('sales.id', 'sales.subtotal', 'sales.discount', 'sales.total', 'sales.status', 'sales.withCash',
            DB::raw("DATE_FORMAT(sales.created_at, '%d %b %Y %H:%i') as createdDate"), 'tables.name as table', 'tables.placeId as placeId',
            'places.place as place', 'places.place as pay', 'companypos.pos', 'sales.voucherType', 'sales.isForeign',
            'sales.tips', 'sales.tipsType', 'sales.sunat', 'sales.voucherSerie', 'sales.voucherNumber', 'sales.splitNumber',
            DB::raw('(SELECT COUNT(*) FROM saleshistory WHERE saleshistory.saleId = sales.id) AS history_count'), 'users.name as userName')
            ->join('users', 'users.id','=','sales.userId')
            ->join('tables', 'tables.id','=','sales.tableId')
            ->join('places', 'places.id','=','tables.placeId')
            ->leftjoin('companypos', 'companypos.id','=','sales.companyPosId');

        if($withCash<4){
            $query->where('sales.withCash','=', $withCash);
        }

        if($filterpay<3){
            $query->where('sales.status','=', $filterpay);
        }

        if($withCash==1 && $companyPosId>0){
            $query->where('sales.companyPosId','=', $companyPosId);
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
                $query->whereBetween('sales.created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('sales.created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('sales.created_at',Carbon::now()->month)->whereYear('sales.created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('sales.created_at',Carbon::now()->subMonth()->month); //->whereYear('sales.created_at', Carbon::now()->year);
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
            DB::raw("DATE_FORMAT(sales.created_at, '%d %b %Y %H:%i') as createdDate"), 'places.place as place', 'sales.splitNumber', 'users.name as userName')
            ->join('users', 'users.id','=','sales.userId')
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
                //$query->whereBetween('sales.created_at',[Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
                $query->whereBetween('sales.created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('sales.created_at', [$fromDate, $toDate]);
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

    public function isforeign(Request $request)
    {
        $saleId = $request->saleId;

        $sale = Sale::find($saleId);
        $sale->isforeign = $request->isforeign;
        $sale->update();

        return response()->json(['status'=>'success', 'message'=>'Se actualizó correctamente']);
    }

    public function topfood(Request $request)
    {
        $dateFilter = $request->daterange;
        $top = $request->top;
        $inCharge = $request->incharge;

        $query = SalesDetail::select('products.name' , DB::raw('count(*) as total'))
            ->join('products', 'products.id', '=', 'sales_detail.productId')
            ->where('products.inCharge', $inCharge);

        switch($dateFilter){
            case 'today':
                $query->whereDate('sales_detail.created_at',Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('sales_detail.created_at',Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('sales_detail.created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::SUNDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('sales_detail.created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('sales_detail.created_at',Carbon::now()->month)->whereYear('sales_detail.created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('sales_detail.created_at',Carbon::now()->subMonth()->month)->whereYear('sales_detail.created_at',Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('sales_detail.created_at',Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('sales_detail.created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('sales_detail.created_at',Carbon::today());
                }
                break;
        }

        $query->groupBy('products.name')
            ->orderBy('total', 'desc')
            ->limit($top);

        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function productchart(): View
    {
        $categories = Category::all();
        return view('reports.productchart', ['categories' => $categories]);
    }

    public function productlist(Request $request)
    {
        $dateFilter = $request->dateRange;
        $productId = $request->productId;
        $categoryId = $request->categoryId;

        if($productId == 0 && $categoryId == 0){
            $query = SalesDetail::select(DB::raw('DATE(sales_detail.updated_at) as date'), DB::raw('sum(sales_detail.quantity) as total'))
                ->join('sales', 'sales.id', '=', 'sales_detail.saleId')
                ->where('sales.status','=', 1);
        }

        if($categoryId > 0){
            $query = SalesDetail::select(DB::raw('DATE(sales_detail.updated_at) as date'), DB::raw('sum(sales_detail.quantity) as total'))
                ->join('sales', 'sales.id', '=', 'sales_detail.saleId')
                ->join('products', 'products.id', '=', 'sales_detail.productId')
                ->where('sales.status','=', 1)
                ->where('products.categoryId', $categoryId);
        }

        if($categoryId > 0 && $productId > 0){
            $query = SalesDetail::select(DB::raw('DATE(sales_detail.updated_at) as date'), DB::raw('sum(sales_detail.quantity) as total'))
                ->join('sales', 'sales.id', '=', 'sales_detail.saleId')
                ->join('products', 'products.id', '=', 'sales_detail.productId')
                ->where('sales.status','=', 1)
                ->where('products.categoryId', $categoryId)
                ->where('sales_detail.productId', $productId);
        }

        switch($dateFilter){
            case 'today':
                $query->whereDate('sales_detail.updated_at', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('sales_detail.updated_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('sales_detail.updated_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('sales_detail.updated_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('sales_detail.updated_at',Carbon::now()->month)->whereYear('sales_detail.updated_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('sales_detail.updated_at', Carbon::now()->subMonth()->month)->whereYear('sales_detail.updated_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('sales_detail.updated_at', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('sales_detail.updated_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('sales_detail.updated_at', Carbon::today());
                }
                break;
        }

        $query->groupBy(DB::raw('Date(sales_detail.updated_at)'))
            ->orderBy('sales_detail.updated_at');

        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function saleschartlist(Request $request)
    {
        $dateFilter = $request->dateRange;
        $productId = $request->productId;

        if($productId == 0){
            $query = Sale::select(DB::raw('DATE(sales.updated_at) as date'), DB::raw('count(*) as total'))
                ->where('sales.status', 1);
        }else{
            return response()->json(['status'=>'success', 'list' => []]);
        }

        switch($dateFilter){
            case 'today':
                $query->whereDate('sales.updated_at', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('sales.updated_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('sales.updated_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('sales.updated_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('sales.updated_at',Carbon::now()->month)->whereYear('sales.updated_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('sales.updated_at', Carbon::now()->subMonth()->month)->whereYear('sales.updated_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('sales.updated_at', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('sales.updated_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('sales.updated_at', Carbon::today());
                }
                break;
        }

        $query->groupBy(DB::raw('Date(sales.updated_at)'))
            ->orderBy('sales.updated_at');

        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function notifications(){

        $list = Product::select('id','name', 'stock', 'minStock')
            ->where('useInventory', 1)
            ->whereColumn('stock', '<=', 'minStock')
            ->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function saleschart(): View
    {
        $companyPosList = CompanyPos::all();
        $staffs = Staff::all();
        $providers = Provider::all();
        $services = Service::all();
        $otherpays = OtherPay::all();

        $users = User::select('users.id', 'users.name', 'users.email', 'roles.name as role', 'roles.id as roleId') 
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', '!=', 'Mozo')
            ->get();

        return view('reports.saleschart', ['companyPosList' => $companyPosList, 'staffs' => $staffs, 'providers' => $providers, 'services' => $services, 'otherpays' => $otherpays, 'users' => $users]);
    }

    public function salesreport(Request $request)
    {
        $dateFilter = $request->dateRange;
        $withCash = $request->withCash;
        $companyPosId = $request->companyPosId;
        $movetype = $request->movetype;
        $expenseType = $request->expenseType;
        $staffId = $request->staffId;
        $providerId = $request->providerId;
        $serviceId = $request->serviceId;
        $otherpayId = $request->otherpayId;
        $userId = $request->usersId;
        $incomeType = $request->incometype;

        // consulta de ingresos
        $query = Sale::select(DB::raw('DATE(sales.created_at) as date'), DB::raw('sum(total) as total'))
                ->where('sales.withCash', '<', 3)
                ->where('sales.status', 1);

        if($incomeType == 1){
            if($withCash<4){
                $query->where('sales.withCash','=', $withCash);
            }
    
            if($withCash==1 && $companyPosId>0){
                $query->where('sales.companyPosId','=', $companyPosId);
            }
        }

        if($incomeType == 2){
            if($userId>0){
                $query->where('sales.userId', '=', $userId);
            }
        }
        
        // consulta de gastos
        $expense1 = MainBox::select('mainbox.created_at', 'expense')
                ->where('mainbox.movementType', '=', 2)
                ->where('mainbox.state', '=', 0)
                ->where('mainbox.expenseType', '<>', 5);

        if($expenseType > 0) {
            $expense1->where('mainbox.expenseType', '=', $expenseType);
            
            if($staffId > 0) {
                $expense1->where('mainbox.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense1->where('mainbox.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense1->where('mainbox.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense1->where('mainbox.otherPayId', '=', $otherpayId);
            }
        }

        $expense2 = PayBoxExpense::select('expenseDate as created_at', 'expense');

        if($expenseType > 0) {
            $expense2->where('payboxexpense.expenseType', '=', $expenseType);
            if($staffId > 0) {
                $expense2->where('payboxexpense.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense2->where('payboxexpense.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense2->where('payboxexpense.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense2->where('payboxexpense.otherPayId', '=', $otherpayId);
            }
        }

        $expense3 = PosExpense::select('expenseDate as created_at', 'expense');

        if($expenseType > 0) {
            $expense3->where('posexpense.expenseType', '=', $expenseType);
            if($staffId > 0) {
                $expense3->where('posexpense.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense3->where('posexpense.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense3->where('posexpense.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense3->where('posexpense.otherPayId', '=', $otherpayId);
            }
        }

        $expense1->union($expense2)->union($expense3);

        $query2 = DB::query()
                ->fromSub($expense1, 'union_query')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(expense) as total'));

        switch($dateFilter){
            case 'this_week':
                $query->whereBetween('sales.created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);

                $query2->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('sales.created_at', [$fromDate, $toDate]);

                $query2->whereBetween('created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('sales.created_at',Carbon::now()->month)->whereYear('sales.created_at', Carbon::now()->year);

                $query2->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('sales.created_at', Carbon::now()->subMonth()->month)->whereYear('sales.created_at', Carbon::now()->year);

                $query2->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->year);

                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('sales.created_at', [$start_date, $end_date]);

                    $query2->whereBetween('created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('sales.created_at', Carbon::today());

                    $query2->whereDate('created_at', Carbon::today());
                }
                break;
        }

        $query->groupBy(DB::raw('Date(sales.created_at)'))
            ->orderBy('sales.created_at');

        $list = [];
        if($movetype!=2){
            $list = $query->get();
        }

        $query2->groupBy(DB::raw('Date(created_at)'))
               ->orderBy('created_at');

        $list2 = [];
        if($movetype!=1){
            $list2 = $query2->get();
        }

        return response()->json(['status'=>'success', 'list' => $list, 'list2' => $list2]);
    }

    public function salesreport2(Request $request)
    {
        $dateFilter = $request->dateRange;
        $userId = $request->usersId;

        $query = Sale::select(DB::raw('DATE(sales.created_at) as date'), DB::raw('sum(total) as total'))
                ->where('sales.status', 1);

        if($userId > 0) {
            $query->where('sales.userId', $userId);            
        }       

        switch($dateFilter){
            case 'today':
                $query->whereDate('sales.created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('sales.created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('sales.created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('sales.created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('sales.created_at',Carbon::now()->month)->whereYear('sales.created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('sales.created_at', Carbon::now()->subMonth()->month)->whereYear('sales.created_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('sales.created_at', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('sales.created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('sales.created_at', Carbon::today());
                }
                break;
        }

        $query2 = clone $query;
        $query3 = clone $query;

        $query->where('sales.withCash', 0);
        $query->groupBy(DB::raw('Date(sales.created_at)'))
            ->orderBy('sales.created_at');
        $list1 = $query->get();

        $query2->where('sales.withCash', 1);
        $query2->groupBy(DB::raw('Date(sales.created_at)'))
            ->orderBy('sales.created_at');
        $list2 = $query2->get();

        $query3->where('sales.withCash', 2);
        $query3->groupBy(DB::raw('Date(sales.created_at)'))
            ->orderBy('sales.created_at');
        $list3 = $query3->get();

        return response()->json(['status'=>'success', 'list1' => $list1, 'list2' => $list2, 'list3' => $list3]);
    }

    public function salesreport3(Request $request)
    {
        $dateFilter = $request->dateRange;
        $expenseType = $request->expenseType;
        $staffId = $request->staffId;
        $providerId = $request->providerId;
        $serviceId = $request->serviceId;
        $otherpayId = $request->otherpayId;

        $expense1 = MainBox::select('mainbox.created_at', 'expense', 'mainbox.description as description', 'expenseType', 'staff.name as staffName', 'provider.name as providerName',
                'service.service as serviceName', 'otherpay.motive as otherPayName')
                ->leftjoin('staff', 'staff.id', '=', 'mainbox.staffId')
                ->leftjoin('provider', 'provider.id', '=', 'mainbox.providerId')
                ->leftjoin('service', 'service.id', '=', 'mainbox.serviceId')
                ->leftjoin('otherpay', 'otherpay.id', '=', 'mainbox.otherPayId')  
                ->where('mainbox.movementType', '=', 2)
                ->where('mainbox.state', '=', 0)
                ->where('mainbox.expenseType', '<>', 5);

        if($expenseType > 0) {
            $expense1->where('mainbox.expenseType', '=', $expenseType);
            
            if($staffId > 0) {
                $expense1->where('mainbox.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense1->where('mainbox.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense1->where('mainbox.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense1->where('mainbox.otherPayId', '=', $otherpayId);
            }
        }

        $expense2 = PayBoxExpense::select('expenseDate as created_at', 'expense', 'payboxexpense.description', 'expenseType', 'staff.name as staffName', 'provider.name as providerName',
                'service.service as serviceName', 'otherpay.motive as otherPayName')
                ->leftjoin('staff', 'staff.id', '=', 'payboxexpense.staffId')
                ->leftjoin('provider', 'provider.id', '=', 'payboxexpense.providerId')
                ->leftjoin('service', 'service.id', '=', 'payboxexpense.serviceId')
                ->leftjoin('otherpay', 'otherpay.id', '=', 'payboxexpense.otherPayId');

        if($expenseType > 0) {
            $expense2->where('payboxexpense.expenseType', '=', $expenseType);
            if($staffId > 0) {
                $expense2->where('payboxexpense.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense2->where('payboxexpense.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense2->where('payboxexpense.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense2->where('payboxexpense.otherPayId', '=', $otherpayId);
            }
        }

        $expense3 = PosExpense::select('expenseDate as created_at', 'expense', 'posexpense.description', 'expenseType', 'staff.name as staffName', 'provider.name as providerName',
                'service.service as serviceName', 'otherpay.motive as otherPayName')
                ->leftjoin('staff', 'staff.id', '=', 'posexpense.staffId')
                ->leftjoin('provider', 'provider.id', '=', 'posexpense.providerId')
                ->leftjoin('service', 'service.id', '=', 'posexpense.serviceId')
                ->leftjoin('otherpay', 'otherpay.id', '=', 'posexpense.otherPayId');

        if($expenseType > 0) {
            $expense3->where('posexpense.expenseType', '=', $expenseType);
            if($staffId > 0) {
                $expense3->where('posexpense.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense3->where('posexpense.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense3->where('posexpense.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense3->where('posexpense.otherPayId', '=', $otherpayId);
            }
        }

        $expense1->union($expense2)->union($expense3);

        $query = DB::query()
                ->fromSub($expense1, 'union_query')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('expense as total'), DB::raw('description'), DB::raw('expenseType'), DB::raw('staffName'), DB::raw('providerName'),
                        DB::raw('serviceName'), DB::raw('otherPayName'));

        switch($dateFilter){
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('created_at', Carbon::today());
                }
                break;
        }
        
        $query->orderBy('expense', 'desc')->limit(50);
        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function expensechart(): View
    {
        $staffs = Staff::all();
        $providers = Provider::all();
        $services = Service::all();
        $otherpays = OtherPay::all();

        return view('reports.expensechart', ['staffs' => $staffs, 'providers' => $providers, 'services' => $services, 'otherpays' => $otherpays]);
    }

    public function expensereport(Request $request)
    {
        $dateFilter = $request->dateRange;
        $expenseType = $request->expenseType;
        $staffId = $request->staffId;
        $providerId = $request->providerId;
        $serviceId = $request->serviceId;
        $otherpayId = $request->otherpayId;
        
        $expense1 = MainBox::select('mainbox.created_at', 'expense')
                ->where('mainbox.movementType', '=', 2)
                ->where('mainbox.state', '=', 0)
                ->where('mainbox.expenseType', '<>', 5);

        if($expenseType > 0) {
            $expense1->where('mainbox.expenseType', '=', $expenseType);
            
            if($staffId > 0) {
                $expense1->where('mainbox.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense1->where('mainbox.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense1->where('mainbox.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense1->where('mainbox.otherPayId', '=', $otherpayId);
            }
        }

        $expense2 = PayBoxExpense::select('expenseDate as created_at', 'expense');
        if($expenseType > 0) {
            $expense2->where('payboxexpense.expenseType', '=', $expenseType);
            if($staffId > 0) {
                $expense2->where('payboxexpense.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense2->where('payboxexpense.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense2->where('payboxexpense.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense2->where('payboxexpense.otherPayId', '=', $otherpayId);
            }
        }

        $expense3 = PosExpense::select('expenseDate as created_at', 'expense');
        if($expenseType > 0) {
            $expense3->where('posexpense.expenseType', '=', $expenseType);
            if($staffId > 0) {
                $expense3->where('posexpense.staffId', '=', $staffId);
            }
            if($providerId > 0) {
                $expense3->where('posexpense.providerId', '=', $providerId);
            }
            if($serviceId > 0) {
                $expense3->where('posexpense.serviceId', '=', $serviceId);
            }
            if($otherpayId > 0) {
                $expense3->where('posexpense.otherPayId', '=', $otherpayId);
            }
        }

        $expense1->union($expense2)->union($expense3);

        $query = DB::query()
                ->fromSub($expense1, 'union_query')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(expense) as total'));

        switch($dateFilter){
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
           
                $query->whereBetween('created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('created_at', Carbon::today());
                }
                break;
        }

        $query->groupBy(DB::raw('Date(created_at)'))
               ->orderBy('created_at');

        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function expenses(): View
    {
        $categories = ExpenseCategories::where('isParent', 1)->get();
        return view('reports.expenses', ['categories' => $categories]);
    }

    public function expenselist(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;
        $categoryId = $request->categoryId;
        $subCategoryId = $request->subCategoryId;
        
        $expense1 = MainBox::select('mainbox.created_at', 'expense', DB::raw('1 as boxType'))
                ->join('expensecategories', 'expensecategories.id', '=', 'mainbox.expensecategoryId') 
                ->where('mainbox.movementType', '=', 2)
                ->where('mainbox.state', '=', 0);

        if($categoryId > 0 && $subCategoryId == 0) {
            $expense1->where('expensecategories.parentId', '=', $categoryId);
        }
        if($subCategoryId > 0) {
            $expense1->where('mainbox.expensecategoryId', '=', $subCategoryId);
        }
        
        $expense2 = PayBoxExpense::select('expenseDate as created_at', 'expense', DB::raw('2 as boxType'))
                ->join('expensecategories', 'expensecategories.id', '=', 'payboxexpense.expensecategoryId'); 

        if($categoryId > 0 && $subCategoryId == 0) {
            $expense2->where('expensecategories.parentId', '=', $categoryId);
        }
        if($subCategoryId > 0) {
            $expense2->where('payboxexpense.expensecategoryId', '=', $subCategoryId);
        }

        $expense3 = PosExpense::select('expenseDate as created_at', 'expense', DB::raw('3 as boxType'))
                ->join('expensecategories', 'expensecategories.id', '=', 'posexpense.expensecategoryId'); 

        if($categoryId > 0 && $subCategoryId == 0) {
            $expense3->where('expensecategories.parentId', '=', $categoryId);
        }
        if($subCategoryId > 0) {
            $expense3->where('posexpense.expensecategoryId', '=', $subCategoryId);
        }

        $expense1->union($expense2)->union($expense3);

        $query = DB::query()
                ->fromSub($expense1, 'union_query')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(expense) as total'), DB::raw('boxType'));

        switch($dateFilter){
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                
                $query->whereBetween('created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->year);

                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('created_at', Carbon::today());
                }
                break;
        }

        $query->groupBy(DB::raw('Date(created_at)'), DB::raw('boxType'))
            ->orderBy('created_at');

        $list = [];
        $list = $query->get();
    
        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function topexpense(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;
        $categoryId = $request->categoryId;
        $subCategoryId = $request->subCategoryId;

        $expense1 = MainBox::select('mainbox.created_at', 'expense', 'description', 'mainbox.expenseType', 'expensecategories.category', DB::raw('1 as boxType'))
                ->join('expensecategories', 'expensecategories.id', '=', 'mainbox.expensecategoryId') 
                ->where('mainbox.movementType', '=', 2)
                ->where('mainbox.state', '=', 0)
                ->where('mainbox.expenseType', '<>', 5);

        if($categoryId > 0 && $subCategoryId == 0) {
            $expense1->where('expensecategories.parentId', '=', $categoryId);
        }
        if($subCategoryId > 0) {
            $expense1->where('mainbox.expensecategoryId', '=', $subCategoryId);
        }

        $expense2 = PayBoxExpense::select('expenseDate as created_at', 'expense', 'description', 'payboxexpense.expenseType', 'expensecategories.category', DB::raw('2 as boxType'))
                ->join('expensecategories', 'expensecategories.id', '=', 'payboxexpense.expensecategoryId'); 

        if($categoryId > 0 && $subCategoryId == 0) {
            $expense2->where('expensecategories.parentId', '=', $categoryId);
        }
        if($subCategoryId > 0) {
            $expense2->where('payboxexpense.expensecategoryId', '=', $subCategoryId);
        }

        $expense3 = PosExpense::select('expenseDate as created_at', 'expense', 'description', 'posexpense.expenseType', 'expensecategories.category', DB::raw('3 as boxType'))
                ->join('expensecategories', 'expensecategories.id', '=', 'posexpense.expensecategoryId'); 

        if($categoryId > 0 && $subCategoryId == 0) {
            $expense3->where('expensecategories.parentId', '=', $categoryId);
        }
        if($subCategoryId > 0) {
            $expense3->where('posexpense.expensecategoryId', '=', $subCategoryId);
        }

        $expense1->union($expense2)->union($expense3);

        $query = DB::query()
                ->fromSub($expense1, 'union_query')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('expense as total'), DB::raw('description'), DB::raw('expenseType'), DB::raw('category'), DB::raw('boxType'));

        switch($dateFilter){
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('created_at', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->input('startDate'));
                $end_date = Carbon::parse($request->input('endDate'));

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('created_at', Carbon::today());
                }
                break;
        }
        
        $query->orderBy('expense', 'desc')->limit(50);
        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);        
    } 
}
