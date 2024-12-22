<?php

namespace App\Http\Controllers;

use App\Models\YapeExpense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Models\Sale;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Staff;
use App\Models\OtherPay;
use App\Models\ExpenseCategories;
use Illuminate\Support\Facades\DB;

class YapeExpenseController extends Controller
{
    public function index(): View
    {
        $services = Service::all();

        $providers = Provider::all();

        $staffs = Staff::all();

        $otherpays = OtherPay::all();

        $categories = ExpenseCategories::where('isParent', 1)->get();

        return view('yapeexpense.index', ['services' => $services, 'staffs' => $staffs, 'providers' => $providers, 'otherpays' => $otherpays, 'categories' => $categories]);
    }

    public function incomelist(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;

        $query = Sale::select('id', 'total', 'created_at')
            ->where('status', 1)
            ->where('withCash', 2);
            
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

        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function expenselist(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;

        $query = YapeExpense::select('yapeexpense.id', DB::raw("DATE_FORMAT(yapeexpense.expenseDate, '%d-%m-%Y %H:%i') as expenseTime"), 'expense', 'yapeexpense.description', 'yapeexpense.expenseType', 'voucherType', 
            'voucherNumber', 'expensecategories.id as expensecategoryId', 'expensecategories.category', DB::raw("DATE_FORMAT(yapeexpense.expenseDate, '%d-%m-%Y') as expenseDate"), 'expensecategories.parentId',
            'yapeexpense.serviceId', 'yapeexpense.providerId', 'yapeexpense.staffId', 'yapeexpense.otherPayId', 'service.service as serviceName', 'provider.name as providerName', 'staff.name as staffName', 
            'otherpay.motive as otherPayName')
            ->join('expensecategories', 'expensecategories.id', '=', 'yapeexpense.expensecategoryId')
            ->leftjoin('service', 'service.id', '=', 'yapeexpense.serviceId')
            ->leftjoin('provider', 'provider.id', '=', 'yapeexpense.providerId')
            ->leftjoin('staff', 'staff.id', '=', 'yapeexpense.staffId')
            ->leftjoin('otherpay', 'otherpay.id', '=', 'yapeexpense.otherPayId');
            
        switch($dateFilter){
            case 'today':
                $query->whereDate('yapeexpense.expenseDate',Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('yapeexpense.expenseDate',Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('yapeexpense.expenseDate', [Carbon::now()->startOfWeek(Carbon::MONDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)]);
                break;
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
                $toDate = Carbon::now()->subWeek()->endOfWeek(Carbon::MONDAY)->toDateString();
                $query->whereBetween('yapeexpense.expenseDate', [$fromDate, $toDate]);
                break;
            case 'this_month':
                $query->whereMonth('yapeexpense.expenseDate',Carbon::now()->month)->whereYear('yapeexpense.expenseDate', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('yapeexpense.expenseDate',Carbon::now()->subMonth()->month)->whereYear('yapeexpense.expenseDate',Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('yapeexpense.expenseDate',Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);

                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('yapeexpense.expenseDate', [$start_date, $end_date]);
                } else {
                    $query->whereDate('yapeexpense.expenseDate',Carbon::today());
                }
                break;
        }

        $list = $query->get();

        return response()->json(['status'=>'success', 'list' => $list]);
    }

    public function add(Request $request)
    {
        $time = Carbon::now()->toTimeString();
        $date = Carbon::parse($request->expenseDate)->toDateString();
        $expenseDate = Carbon::parse($date . $time);

        $yapeExpense = new YapeExpense();
        $expenseCategoryId = $request->subCategoryId;
        if($expenseCategoryId == "") {
            $expenseCategoryId = $request->expensecategoryId;
        }
        $yapeExpense->expensecategoryId = $expenseCategoryId;

        if($request->providerId != "") {
            $yapeExpense->providerId = $request->providerId;
        }
        if($request->serviceId != "") {
            $yapeExpense->serviceId = $request->serviceId;
        }
        if($request->staffId != "") {
            $yapeExpense->staffId = $request->staffId;
        }
        if($request->otherPayId != "") {
            $yapeExpense->otherPayId = $request->otherPayId;
        }

        $yapeExpense->expenseDate = $expenseDate;
        $yapeExpense->expense = $request->expense;
        $yapeExpense->description = $request->description;
        $yapeExpense->expenseType = $request->expenseType;
        $yapeExpense->voucherType = $request->voucherType;
        $yapeExpense->voucherNumber = $request->voucherNumber;
        $yapeExpense->save();          

        return response()->json(['status'=>'success', 'message'=>'El Yape fue agregado']);    
    }

    public function edit(Request $request)
    {
        $time = Carbon::now()->toTimeString();
        $date = Carbon::parse($request->expenseDate)->toDateString();
        $expenseDate = Carbon::parse($date . $time);

        $yapeExpense = YapeExpense::find($request->yapeexpenseId);
        $expenseCategoryId = $request->subCategoryId;
        if($expenseCategoryId == "") {
            $expenseCategoryId = $request->expensecategoryId;
        }
        $yapeExpense->expensecategoryId = $expenseCategoryId;

        if($request->providerId != "") {
            $yapeExpense->providerId = $request->providerId;
        }
        if($request->serviceId != "") {
            $yapeExpense->serviceId = $request->serviceId;
        }
        if($request->staffId != "") {
            $yapeExpense->staffId = $request->staffId;
        }
        if($request->otherPayId != "") {
            $yapeExpense->otherPayId = $request->otherPayId;
        }

        $yapeExpense->expenseDate = $expenseDate;
        $yapeExpense->expense = $request->expense;
        $yapeExpense->description = $request->description;
        $yapeExpense->expenseType = $request->expenseType;
        $yapeExpense->voucherType = $request->voucherType;
        $yapeExpense->voucherNumber = $request->voucherNumber;
        $yapeExpense->update();

        return response()->json(['status'=>'success', 'message'=>'El Yape fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        YapeExpense::find($request->yapeexpenseId)->delete();
        return response()->json(['status'=>'success', 'message'=>'El gasto fue eliminado']);     
    }
}
