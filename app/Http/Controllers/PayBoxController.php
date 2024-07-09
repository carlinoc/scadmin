<?php

namespace App\Http\Controllers;

use App\Models\PayBox;
use App\Models\Provider;
use App\Models\Staff;
use App\Models\Service;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
        $payboxs = PayBox::all();
        
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

        return view('paybox.detail', ['paybox' => $payBox]);
    }

    public function close(Request $request)
    {
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

        Sale::where('status', 0)->update(['payboxId' => null]); 

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
