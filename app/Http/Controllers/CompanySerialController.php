<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySerial;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class CompanySerialController extends Controller
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
        //dd($request->serietype);
        $companySerial = CompanySerial::where('serieType', $request->serietype)->first();
        return response()->json(['companySerial' => $companySerial]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companySerial = CompanySerial::find($request->companySerialId);
        $companySerial->serieType = $request->serieType;
        $companySerial->serie = $request->serie;
        $companySerial->number = $request->number;
        $companySerial->update();

        return response()->json(['status'=>'success', 'message'=>'Los datos del comprobante fue actualizado']);    

    }

    public function adddebug(Request $request)
    {
        $debugMode = $request->debugMode;
        $debug = 0;
        if($debugMode != ""){
            $debug = 1;
        }
        $nowDate = Carbon::now();
        
        $RUC = env('DATA_COMPANY_RUC','10238228379');
        Company::where('ruc', $RUC)->update(['debug' => $debug]);
        
        CompanySerial::where('serieType', 3)
            ->update(['serie' => $request->serieBoleta, 'number' => $request->numberBoleta, 'updated_at' => $nowDate]);
            
        CompanySerial::where('serieType', 4)
            ->update(['serie' => $request->serieFactura, 'number' => $request->numberFactura, 'updated_at' => $nowDate]);    

        return response()->json(['status'=>'success', 'message'=>'Los datos fueron actualizados']);    
    }

    public function verify(Request $request){

        $type = "03";
        if($request->serieType==2){
            $type = "01";
        }
        $serie = $request->serie;

        $personaId = env('DATA_COMPANY_PERSONAID', '66ec583b65bbba0015243286');
        $personaToken = env('DATA_COMPANY_PERSONATOKEN', 'DEV_6AqWeKCsK7TRoUcytbBAX1qjCuP0lUj5awGJhFSt0xlyWwtxj28i85qmyOM5mCYs');

        $response = Http::post('https://back.apisunat.com/personas/lastDocument', [
            'personaId' => '66ec583b65bbba0015243286',
            'personaToken' => 'DEV_6AqWeKCsK7TRoUcytbBAX1qjCuP0lUj5awGJhFSt0xlyWwtxj28i85qmyOM5mCYs',
            'type' => $type,
            'serie' => $serie,
        ]);

        if($response->successful()){
            $suggestedNumber = $response['suggestedNumber']; 
            $serie = $response['serie'];
            return response()->json(['status'=>'success', 'suggestedNumber'=> (int)$suggestedNumber, 'serie' => $serie]);
        }else{
            return response()->json(['status'=>'error', 'message'=>'Ocurrio un error']);
        }
    }
}
