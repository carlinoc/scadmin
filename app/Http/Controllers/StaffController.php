<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Area;
use App\Models\PayBoxExpense;
use App\Models\PayBox;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $areas = Area::all();
        return view('staff.index', ['areas' => $areas]);
    }

    public function list(Request $request): JsonResponse
    {
        $staffs = Staff::select('staff.id', 'staff.name', 'staff.dni', 'staff.phone1', 'staff.phone2', 'staff.address', 'staff.email', 'staff.description', 'staff.areaId', 'area.area')
            ->join('area', 'area.id', '=', 'staff.areaId')
            ->get();

        return response()->json(['staffs' => $staffs]);
    }

    public function add(Request $request): JsonResponse 
    {
        $staff = new Staff();
        $staff->name = $request->name;
        $staff->dni = $request->dni;
        $staff->phone1 = $request->phone1;
        $staff->phone2 = $request->phone2;
        $staff->address = $request->address;
        $staff->email = $request->email;
        $staff->description = $request->description;
        $staff->areaId = $request->areaId;
        $staff->save();

        return response()->json(['status'=>'success', 'message'=>'El personal fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $staff = Staff::find($request->staffId);
        $staff->name = $request->name;
        $staff->dni = $request->dni;
        $staff->phone1 = $request->phone1;
        $staff->phone2 = $request->phone2;
        $staff->address = $request->address;
        $staff->email = $request->email;
        $staff->description = $request->description;
        $staff->areaId = $request->areaId;
        $staff->update();

        return response()->json(['status'=>'success', 'message'=>'El personal fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        $rows = DB::table('expensestaff')->where('staffId', $request->staffId)->count();
        if($rows == 0) {
            Staff::find($request->staffId)->delete(); 
            return response()->json(['status'=>'success', 'message'=>'El personal fue eliminado']);     
        }else{
            return response()->json(['status'=>'success', 'message'=>'No se puede eliminar un personal con modulos relacionados']);     
        }   
    }

    public function detail(Request $request): View
    {
        $staff = Staff::find($request->staffId);        
        $areas = Area::all();

        return view('staff.detail', ['staff' => $staff, 'areas' => $areas]);
    }

    public function addexpense(Request $request): JsonResponse 
    {
        $paybox = PayBox::select('id')->where('state','=', 1)->where('startDate', '>=', Carbon::now()->subDays(1)->toDateTimeString());
        $rows = $paybox->count();
        if($rows==0){
            return response()->json(['status'=>'error', 'message'=>'Es necesario aperturar la CAJA']);    
        }else{
            $payBoxId = $paybox->get()[0]["id"];

            $pBExpense = new PayBoxExpense();
            $pBExpense->expenseDate = Carbon::now();
            $pBExpense->expense = $request->expense;
            $pBExpense->description = $request->description;
            $pBExpense->expenseType = 3;
            $pBExpense->staffPayType = $request->staffPayType;
            $pBExpense->payboxId = $payBoxId;
            $pBExpense->staffId = $request->staffId;
            $pBExpense->save();

            return response()->json(['status'=>'success', 'message'=>'El monto fue agregado']);    
        }
    }

    public function editexpense(Request $request): JsonResponse 
    {
        $pBExpense = PayBoxExpense::find($request->payboxExpenseId);
        $pBExpense->expenseDate = Carbon::now();
        $pBExpense->expense = $request->expense;
        $pBExpense->description = $request->description;
        $pBExpense->staffPayType = $request->staffPayType;
        $pBExpense->update();

        return response()->json(['status'=>'success', 'message'=>'El monto fue actualizado']);    
    }

    public function listexpense(Request $request)
    {
        $dateFilter = $request->dateRange;
        $staffPayType = $request->staffPayType;
        
        $query = PayBoxExpense::select('id', 'expenseDate', 'expense', 'description', 'staffPayType')
            ->where('staffId', $request->staffId)
            ->where('expenseType', 3);   

        if($staffPayType > 0) {
            $query->where('staffPayType','=', $staffPayType);
        }    

        switch($dateFilter){
            case 'today':
                $query->whereDate('expenseDate',Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('expenseDate',Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('expenseDate',[Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('expenseDate',[Carbon::now()->subWeek(),Carbon::now()]);
                break;
            case 'this_month':
                $query->whereMonth('expenseDate',Carbon::now()->month)->whereYear('expenseDate', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('expenseDate',Carbon::now()->subMonth()->month)->whereYear('expenseDate',Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('expenseDate',Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                
                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('expenseDate', [$start_date, $end_date]);
                } else {
                    $query->whereDate('expenseDate',Carbon::today());
                }           
                break;           
        }  
        
        $list = $query->get();    
        $query2 = $query;

        $totalExpense = $query2->sum('expense');
        
        return response()->json(['status'=>'success', 'list' => $list, 'totalExpense' => $totalExpense]);    
    }

    public function removeexpense(Request $request): JsonResponse
    {
        PayBoxExpense::find($request->posExpenseId)->delete();      

        return response()->json(['status'=>'success', 'message'=>'El gasto fue eliminado']);     
    }
}
