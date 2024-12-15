<?php

namespace App\Http\Controllers;

use App\Models\MainBox;
use App\Models\PayBox;
use App\Models\Provider;
use App\Models\Staff;
use App\Models\Service;
use App\Models\OtherPay;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ExpenseCategories;

class PayBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomeConcepts = DB::table('incomeconcept')->get();

        $providers = Provider::all();

        $staffs = Staff::all();

        $services = Service::all();

        return view('paybox.index', ['incomeConcepts' => $incomeConcepts, 'providers' => $providers, 'staffs' => $staffs, 'services' => $services]);
    }

    public function list(Request $request): JsonResponse
    {
        $payboxs = PayBox::select('paybox.*', 'users.name as userName'
            ,DB::raw("DATE_FORMAT(paybox.startDate, '%d %b %Y %H:%i') as startDate")
            ,DB::raw("DATE_FORMAT(paybox.closingDate, '%d %b %Y %H:%i') as closingDate"))
            ->join('users', 'users.id', '=', 'paybox.userId')
            ->get();
        
        return response()->json(['payboxs' => $payboxs]);
    }


    public function add(Request $request): JsonResponse 
    {
        $payBox = new PayBox();
        $payBox->cashBalance = $request->cashBalance;
        $payBox->startDate = Carbon::now();
        $payBox->state = 1;
        $payBox->userId = $request->userId;
        $payBox->save();

        Sale::where('status', 0)->update(['payboxId' => $payBox->id]);

        $mainBox = new MainBox();
        $mainBox->movementType = 2;
        $mainBox->expense = $request->cashBalance;
        $mainBox->expenseType = 5;
        $mainBox->description = "Para Caja Diaria";
        $mainBox->userId = $request->userId;
        $mainBox->payboxId = $payBox->id;
        $mainBox->save();

        return response()->json(['status'=>'success', 'message'=>'La caja fue iniciada']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $payBox = PayBox::find($request->payboxId);
        $payBox->name = $request->name;
        if($request->state){
            $payBox->state = 1;
        }else{
            $payBox->state = 0;
        }
        $payBox->description = $request->description;
        $payBox->update();

        return response()->json(['status'=>'success', 'message'=>'La caja fue actualizada']);    
    }

    public function initbox(Request $request)
    {
        $userId = Auth::id();
        $payBox = new PayBox();
        $payBox->name = 'Caja Principal';
        $payBox->state = 1;
        $payBox->userId = $userId;
        $payBox->save();

        return response()->json(['status'=>'success', 'message'=>'La caja fue creada']);
    }

    public function detail(Request $request): View
    {
        $payBox = PayBox::find($request->payboxId);

        $sales = Sale::select('id', 'tips', 'tipsType')->where('payboxId', $request->payboxId);
        $query = $sales;
        $totalTips = $sales->sum('tips');
        $query = $query->where('tipsType', '1');
        $totalCash = $query->sum('tips');
        $totalCard = round(($totalTips - $totalCash), 2);
        $posPercent = env('DATA_COMPANY_POS_PERCENT', 4.09);
        $desc = $totalCard * $posPercent / 100;
        $totalCard = $totalCard - $desc;
        //$totalCard = number_format(47, 2);
        $totalCard = number_format($totalCard, 2);
        $totalTips = $totalCash + $totalCard;        

        return view('paybox.detail', ['paybox' => $payBox, 'totalCash' => $totalCash, 'totalTips' => $totalTips, 'totalCard' => $totalCard]);
    }

    public function show(Request $request): View
    {
        $incomeConcepts = DB::table('incomeconcept')->get();
        $providers = Provider::all();
        $staffs = Staff::all();
        $services = Service::all();
        $otherpays = OtherPay::all();

        $categories = ExpenseCategories::where('isParent', 1)->get();
        
        //$payBox = PayBox::find($request->payboxId);

        $payBox = PayBox::select('paybox.*', 'users.name as userName')
            ->join('users', 'users.id', '=', 'paybox.userId')
            ->find($request->payboxId);

        $sales = Sale::select('id', 'tips', 'tipsType')->where('payboxId', $request->payboxId);
        $query = $sales;
        $totalTips = $sales->sum('tips');
        $query = $query->where('tipsType', '1');
        $totalCash = $query->sum('tips');
        $totalCard = round(($totalTips - $totalCash), 2);
        $posPercent = env('DATA_COMPANY_POS_PERCENT', 4.09);
        $desc = $totalCard * $posPercent / 100;
        $totalCard = $totalCard - $desc;
        $totalCard = number_format($totalCard, 2);
        $totalTips = $totalCash + $totalCard;        

        return view('paybox.show', ['paybox' => $payBox, 'totalCash' => $totalCash, 'totalTips' => $totalTips, 
            'totalCard' => $totalCard, 'incomeConcepts' => $incomeConcepts, 'providers' => $providers, 'staffs' => $staffs, 'services' => $services, 'otherpays' => $otherpays, 'categories' => $categories]);
    }

    public function close(Request $request)
    {
        $rows = Sale::all()->where('status', 0)->count();
        if($rows > 0){
            Sale::where('status', 0)->update(['payboxId' => null]);
            //return response()->json(['status'=>'error', 'message'=>'No se puede cerrar la caja, existen pedidos pendientes']);
        }

        $payBox = PayBox::find($request->payboxId);
        $payBox->closingDate = Carbon::now();
        $payBox->income = $request->income;
        $payBox->expenses = $request->expenses;
        $payBox->cashSales = $request->cashSales;
        $payBox->cardSales = $request->cardSales;
        $payBox->missingBalance = $request->missingBalance;
        $payBox->leftoverBalance = $request->leftoverBalance;
        $payBox->finalBalance = $request->finalBalance;
        $payBox->cashRegister = $request->cashRegister;
        $payBox->state = 2;
        $payBox->update();

        $mainBox = new MainBox();
        $mainBox->movementType = 1;
        $mainBox->incomeconceptId = 2;
        $mainBox->income = $request->cashRegister;
        $mainBox->description = "Cierre de caja";
        $mainBox->userId = Auth::user()->id;
        $mainBox->save();

        return response()->json(['status'=>'success', 'message'=>'Se realizo el cierre de caja']);    
    }

    public function verifyopen(Request $request ){
        $rows = DB::table('paybox')->where('state', 1)->count();
        if($rows > 0){
            return response()->json(['status'=>'success', 'message'=>'Es necesario cerrar la caja para aperturar una nueva.']);
        }else{
            return response()->json(['status'=>'error', 'message'=>'']);
        }
    }
}
