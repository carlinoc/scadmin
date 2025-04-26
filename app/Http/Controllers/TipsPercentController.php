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
        $list = TipsPercent::orderBy('points', 'desc')->get();
            
        return response()->json(['list' => $list]);
    }

    public function add(Request $request): JsonResponse 
    {
        $tipsPercent = new TipsPercent();
        $tipsPercent->area = $request->area;
        $tipsPercent->employ = $request->employ;
        $tipsPercent->points = $request->points;

        $tipsPercent->save();

        return response()->json(['status'=>'success', 'message'=>'El porcentaje fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $tipsPercent = TipsPercent::find($request->tipsPercentId);
        $tipsPercent->area = $request->area;
        $tipsPercent->employ = $request->employ;
        $tipsPercent->points = $request->points;
        $tipsPercent->update();

        return response()->json(['status'=>'success', 'message'=>'El porcentaje fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        TipsPercent::find($request->tipsPercentId)->delete(); 
        return response()->json(['status'=>'success', 'message'=>'El porcentaje fue eliminado']);     
    }
}
