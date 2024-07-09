<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::all()->first();
        return view('company.index', ['company' => $company]);
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

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
