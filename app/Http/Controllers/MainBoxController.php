<?php

namespace App\Http\Controllers;

use App\Models\MainBox;
use App\Models\MainBoxHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Provider;
use App\Models\Staff;
use App\Models\Service;
use App\Models\OtherPay;

class MainBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $incomeConcepts = DB::table('incomeconcept')->get();
        $providers = Provider::all();
        $staffs = Staff::all();
        $services = Service::all();
        $otherpays = OtherPay::all();

        return view('mainbox.index', ['incomeConcepts' => $incomeConcepts, 'providers' => $providers, 'staffs' => $staffs, 'services' => $services, 'otherpays' => $otherpays]);
    }

    public function add(Request $request): JsonResponse
    {
        $mainBox = new MainBox();
        $mainBox->movementType = 1;
        $mainBox->incomeconceptId = $request->incomeconceptId;
        $mainBox->income = $request->income;
        $mainBox->description = $request->description;
        $mainBox->userId = Auth::user()->id;
        $mainBox->save();

        return response()->json(['status'=>'success', 'message'=>'El ingreso fue agregado']);
    }

    public function edit(Request $request): JsonResponse
    {
        $mainBox = MainBox::find($request->mainBoxId);
        $lastIncome = $mainBox->income;
        $newIncome = $request->income;

        $mainBox->movementType = 1;
        $mainBox->incomeconceptId = $request->incomeconceptId;
        $mainBox->income = $newIncome;
        $mainBox->description = $request->description;
        $mainBox->userId = Auth::user()->id;
        $mainBox->update();

        $mainBoxHistory = new MainBoxHistory();
        $mainBoxHistory->movementType = 1;
        $mainBoxHistory->action = "Ingreso Actualizado";
        $mainBoxHistory->lastIncome = $lastIncome;
        $mainBoxHistory->newIncome = $newIncome;
        $mainBoxHistory->userId = Auth::user()->id;
        $mainBoxHistory->mainBoxId = $request->mainBoxId;
        $mainBoxHistory->save();

        return response()->json(['status'=>'success', 'message'=>'El ingreso fue actualizado']);
    }

    public function list(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;
        $movementType = $request->movementType;
        $state = 0;
        if($movementType == 3) {
            $state = 1;
        }
        
        $query = MainBox::select('mainbox.id', 'mainbox.movementType', 'mainbox.income', 'mainbox.expense', 'mainbox.expenseType', 'mainbox.staffPayType',
            DB::raw("DATE_FORMAT(mainbox.created_at, '%d %b %Y %H:%i') as createdDate"), 'mainbox.description', 'mainbox.userId', 'incomeconcept.name as incomeConcept',
            'users.name as userName', 'provider.name as providerName', 'mainbox.staffPayType', 'otherpay.motive as otherPayMotive', 'mainbox.incomeconceptId',
            'mainbox.providerId', 'mainbox.staffId', 'mainbox.otherPayId', 'mainbox.voucherType', 'mainbox.voucherNumber', 'mainbox.serviceId', 'mainbox.payboxId',
            DB::raw('(SELECT COUNT(*) FROM mainboxhistory WHERE mainboxhistory.mainboxId = mainbox.id) AS history_count'))
            ->join('users', 'users.id', '=', 'mainbox.userId')
            ->leftjoin('incomeconcept', 'incomeconcept.id', '=', 'mainbox.incomeconceptId')
            ->leftjoin('provider', 'provider.id', '=', 'mainbox.providerId')
            ->leftjoin('otherpay', 'otherpay.id', '=', 'mainbox.otherPayId')
            ->where('mainbox.state', '=', $state);    

        if($movementType == -1) {
            $query->where(function($q) {
                $q->where('mainbox.expense', '>', 0)->orWhere('mainbox.income', '>', 0);
            });  
        }    

        if($movementType > 0 && $movementType < 3) {
            $query->where('mainbox.movementType', '=', $movementType);
        }

        if($dateFilter != 'all') {
            switch($dateFilter){
                case 'today':
                    $query->whereDate('mainbox.created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->wheredate('mainbox.created_at', Carbon::yesterday());
                    break;
                case 'this_week':
                    $query->whereBetween('mainbox.created_at', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                    break;
                case 'last_week':
                    $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                    $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                    $query->whereBetween('mainbox.created_at', [$fromDate, $toDate]);
                    //$query->whereBetween('mainbox.created_at', [Carbon::now()->subWeek(), Carbon::now()]);
                    break;
                case 'this_month':
                    $query->whereMonth('mainbox.created_at', Carbon::now()->month)->whereYear('mainbox.created_at', Carbon::now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('mainbox.created_at', Carbon::now()->subMonth()->month)->whereYear('mainbox.created_at', Carbon::now()->year);
                    break;
                case 'this_year':
                    $query->whereYear('mainbox.created_at', Carbon::now()->year);
                    break;
                case 'custom':
                    $start_date = Carbon::parse($request->startDate);
                    $end_date = Carbon::parse($request->endDate);

                    if ($end_date->greaterThan($start_date)) {
                        $query->whereBetween('mainbox.created_at', [$start_date, $end_date]);
                    } else {
                        $query->whereDate('mainbox.created_at',Carbon::today());
                    }
                    break;
            }
        }

        $list = $query->get();
        $query2 = clone $query;

        $totalIncome = $query->sum('mainbox.income');
        $totalExpense = $query2->sum('mainbox.expense');

        return response()->json(['status'=>'success', 'list' => $list, 'totalIncome' => $totalIncome, 'totalExpense' => $totalExpense]);
    }

    public function addexpense(Request $request): JsonResponse
    {
        $mainBox = new MainBox();
        $mainBox->movementType = 2;
        $mainBox->expense = $request->expense;

        $expenseType = $request->expenseType;

        if ($expenseType == 1) {
            $mainBox->providerId = $request->providerId;
        }

        if ($expenseType == 2) {
            $mainBox->serviceId = $request->serviceId;
        }

        if ($expenseType == 3) {
            $mainBox->staffId = $request->staffId;
            $mainBox->staffPayType = $request->staffPayType;
        }

        if ($expenseType == 4) {
            $mainBox->otherPayId = $request->otherPayId;
        }

        if($expenseType != 3) {
            $mainBox->voucherType = $request->voucherType;
            $mainBox->voucherNumber = $request->voucherNumber;
        }

        $mainBox->expenseType = $expenseType;
        $mainBox->description = $request->description;
        $mainBox->userId = Auth::user()->id;
        $mainBox->save();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue agregado']);
    }

    public function editexpense(Request $request): JsonResponse
    {
        $mainBox = MainBox::find($request->mainBoxId);
        $lastExpense = $mainBox->expense;
        $newExpense = $request->expense;
        
        $mainBox->movementType = 2;
        $mainBox->expense = $newExpense;

        $expenseType = $request->expenseType;

        $mainBox->providerId = null;
        $mainBox->serviceId = null;
        $mainBox->staffId = null;
        $mainBox->staffPayType = null;
        $mainBox->otherPayId = null;
        
        if ($expenseType == 1) {
            $mainBox->providerId = $request->providerId;
        }

        if ($expenseType == 2) {
            $mainBox->serviceId = $request->serviceId;
        }

        if ($expenseType == 3) {
            $mainBox->staffId = $request->staffId;
            $mainBox->staffPayType = $request->staffPayType;
        }

        if ($expenseType == 4) {
            $mainBox->otherPayId = $request->otherPayId;
        }

        if($expenseType != 3) {
            $mainBox->voucherType = $request->voucherType;
            $mainBox->voucherNumber = $request->voucherNumber;
        }

        $mainBox->expenseType = $expenseType;
        $mainBox->description = $request->description;
        $mainBox->userId = Auth::user()->id;
        $mainBox->update();

        $mainBoxHistory = new MainBoxHistory();
        $mainBoxHistory->movementType = 2;
        $mainBoxHistory->action = "Gasto Actualizado";
        $mainBoxHistory->lastExpense = $lastExpense;
        $mainBoxHistory->newExpense = $newExpense;
        $mainBoxHistory->userId = Auth::user()->id;
        $mainBoxHistory->mainBoxId = $request->mainBoxId;
        $mainBoxHistory->save();

        return response()->json(['status'=>'success', 'message'=>'El gasto fue actualizado']);
    }

    public function remove(Request $request): JsonResponse
    {
        //MainBox::find($request->mainboxId)->delete();

        $mainBox = MainBox::find($request->mainboxId);
        $mainBox->state = 1;
        $mainBox->update();

        $mainBoxHistory = new MainBoxHistory();
        $mainBoxHistory->action = "Registro Eliminado";
        $mainBoxHistory->userId = Auth::user()->id;
        $mainBoxHistory->mainBoxId = $request->mainboxId;
        $mainBoxHistory->save();

        return response()->json(['status'=>'success', 'message'=>'El registro fue eliminado']);
    }
}
