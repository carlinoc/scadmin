<?php

namespace App\Http\Controllers;

use App\Models\CompanyPos;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Staff;
use App\Models\OtherPay;

class CompanyPosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $list = Staff::all();
        return view('companypos.index', ['list' => $list]);
    }

    public function list(Request $request): JsonResponse
    {
        $list = CompanyPos::select('companypos.id', 'companypos.pos', 'companypos.commission', 'companypos.contactName', 'companypos.contactPhone', 'companypos.description', 
            'staff.name as staffName', 'staff.id as staffId', 'ruc', 'bank', 'accountNumber', 'mainPos')
            ->leftJoin('staff', 'companypos.staffId', '=', 'staff.id')
            ->get();
        return response()->json(['list' => $list]);
    }

    public function add(Request $request)
    {
        $mainPos = $request->mainPos;
        if($mainPos != ""){
            $mainPos = 1;
            CompanyPos::query()->update(['mainPos' => 0]);
        }else{
            $mainPos = 0;
        }
        
        $companyPos = new CompanyPos();
        $companyPos->pos = $request->pos;
        $companyPos->commission = $request->commission;
        $companyPos->staffId = $request->staffId;
        $companyPos->ruc = $request->ruc;
        $companyPos->bank = $request->bank;
        $companyPos->accountNumber = $request->accountNumber; 
        $companyPos->contactName = $request->contactName;
        $companyPos->contactPhone = $request->contactPhone;
        $companyPos->description = $request->description;
        $companyPos->mainPos = $mainPos;
        $companyPos->save();

        return response()->json(['status'=>'success', 'message'=>'El POS fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $mainPos = $request->mainPos;
        if($mainPos != ""){
            $mainPos = 1;
            CompanyPos::query()->update(['mainPos' => 0]);
        }else{
            $mainPos = 0;
        }

        $companyPos = CompanyPos::find($request->companyPosId);
        $companyPos->pos = $request->pos;
        $companyPos->commission = $request->commission;
        $companyPos->staffId = $request->staffId;
        $companyPos->ruc = $request->ruc;
        $companyPos->bank = $request->bank;
        $companyPos->accountNumber = $request->accountNumber; 
        $companyPos->contactName = $request->contactName;
        $companyPos->contactPhone = $request->contactPhone;
        $companyPos->description = $request->description;
        $companyPos->mainPos = $mainPos;
        $companyPos->update();

        return response()->json(['status'=>'success', 'message'=>'El POS fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        $rows = DB::table('sales')->where('companyPosId', $request->companyPosId)->count();
        if($rows == 0) {
            CompanyPos::find($request->companyPosId)->delete();      
            return response()->json(['status'=>'success', 'message'=>'El POS fue eliminado']);         
        }else{
            return response()->json(['status'=>'error', 'message'=>'No se puede eliminar un POS con ventas relacionadas']);     
        }   
    }


    public function detail(Request $request): View
    {
        $companyPos = CompanyPos::find($request->companyPosId);

        $services = Service::all();

        $providers = Provider::all();

        $staffs = Staff::all();

        $otherpays = OtherPay::all();

        return view('companypos.detail', ['companyPos' => $companyPos, 'services' => $services, 'staffs' => $staffs, 'providers' => $providers, 'otherpays' => $otherpays]);
    }
}
