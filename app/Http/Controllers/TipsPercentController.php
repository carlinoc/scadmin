<?php

namespace App\Http\Controllers;

use App\Models\TipsPercent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TipsPercentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function list(Request $request): JsonResponse
    {
        $list = TipsPercent::all();  

        return response()->json(['list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $tipsPercent = new TipsPercent();
        $tipsPercent->employ = $request->employ;
        $tipsPercent->percent = $request->percent;
        $tipsPercent->save();

        return response()->json(['status'=>'success', 'message'=>'El porcentaje fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $tipsPercent = TipsPercent::find($request->tipsPercentId);
        $tipsPercent->employ = $request->employ;
        $tipsPercent->percent = $request->percent;
        $tipsPercent->update();

        return response()->json(['status'=>'success', 'message'=>'El porcentaje fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        TipsPercent::find($request->tipsPercentId)->delete(); 
        return response()->json(['status'=>'success', 'message'=>'El porcentaje fue eliminado']);     
    }
}
