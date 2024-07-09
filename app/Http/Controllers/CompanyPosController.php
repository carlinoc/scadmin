<?php

namespace App\Http\Controllers;

use App\Models\CompanyPos;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CompanyPosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('companypos.index');
    }

    public function list(Request $request): JsonResponse
    {
        $list = CompanyPos::all();
        return response()->json(['list' => $list]);
    }

    public function add(Request $request)
    {
        //todo: verificar que ya existe el ruc y el dni
        $companyPos = new CompanyPos();
        $companyPos->pos = $request->pos;
        $companyPos->commission = $request->commission;
        $companyPos->contactName = $request->contactName;
        $companyPos->contactPhone = $request->contactPhone;
        $companyPos->description = $request->description;
        $companyPos->save();

        return response()->json(['status'=>'success', 'message'=>'El POS fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $companyPos = CompanyPos::find($request->companyPosId);
        $companyPos->pos = $request->pos;
        $companyPos->commission = $request->commission;
        $companyPos->contactName = $request->contactName;
        $companyPos->contactPhone = $request->contactPhone;
        $companyPos->description = $request->description;
        $companyPos->update();

        return response()->json(['status'=>'success', 'message'=>'El POS fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        //todo: verificar que no haya ventas asociadas al pos
        CompanyPos::find($request->companyPosId)->delete();      
        return response()->json(['status'=>'success', 'message'=>'El POS fue eliminado']);     

        // $rows = DB::table('sales')->where('clientId', $request->clientId)->count();
        // if($rows == 0) {
            
        // }else{
        //     return response()->json(['status'=>'error', 'message'=>'No se puede eliminar un cliente con ventas relacionadas']);     
        // }   
    }
}
