<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySerial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::all()->first();
        $serialBoleta = CompanySerial::select('serie', 'number')->where('serieType', 3)->first();
        $serialFactura = CompanySerial::select('serie', 'number')->where('serieType', 4)->first();

        return view('company.index', ['company' => $company, 'serialBoleta' => $serialBoleta, 'serialFactura' => $serialFactura]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company = Company::find($request->companyId);
        $company->company = $request->company;
        $company->slogan = $request->slogan;
        $company->ruc = $request->ruc;
        $company->igv = $request->igv;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->website = $request->website;
        $company->description = $request->description;
        $company->update();

        return response()->json(['status'=>'success', 'message'=>'Los datos de la empresa fue actualizado']);    
    }

    public function yape()
    {
        
        return view('company.yape');
    }
}
