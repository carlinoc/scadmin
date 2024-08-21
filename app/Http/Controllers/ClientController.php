<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('client.index');
    }

    public function list(Request $request): JsonResponse
    {
        $clients = Client::all();

        return response()->json(['clients' => $clients]);
    }

    public function add(Request $request)
    {
        $clientType = $request->clientType;
        if($clientType==1) {
            $rows = Client::select('id')->where('dni','=', $request->dni)->count();
            if($rows > 0) {
                return response()->json(['status'=>'error', 'message'=>'Ya existe un cliente con ese DNI']);
            }
        }
        if($clientType==2) {
            $rows = Client::select('id')->where('ruc','=', $request->ruc)->count();
            if($rows > 0) {
                return response()->json(['status'=>'error', 'message'=>'Ya existe un cliente con ese RUC']);
            }
        }
        
        $client = new Client();
        $client->name = $request->name;
        $client->dni = $request->dni;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->email = $request->email;
        $client->discount = $request->discount;
        $client->description = $request->description;
        $client->clientType = $request->clientType;
        $client->ruc = $request->ruc;
        $client->save();

        return response()->json(['status'=>'success', 'message'=>'El cliente fue agregado', 'clientId'=>$client->id]);    
    }

    public function edit(Request $request): JsonResponse
    {
        $client = Client::find($request->clientId);
        $client->name = $request->name;
        $client->dni = $request->dni;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->email = $request->email;
        $client->discount = $request->discount;
        $client->description = $request->description;
        $client->update();

        return response()->json(['status'=>'success', 'message'=>'El cliente fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        $rows = DB::table('sales')->where('clientId', $request->clientId)->count();
        if($rows == 0) {
            Client::find($request->clientId)->delete();      
            return response()->json(['status'=>'success', 'message'=>'El cliente fue eliminado']);     
        }else{
            return response()->json(['status'=>'error', 'message'=>'No se puede eliminar un cliente con ventas relacionadas']);     
        }   
    }

    public function detail(Request $request): View
    {
        $client = Client::find($request->clientId);
        return view('client.detail', ['client' => $client]);
    }

    public function listpayments(Request $request): JsonResponse
    {
        $dateFilter = $request->dateRange;
        $clientId = $request->clientId;

        $query = Sale::select('id', DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i') as createdDate"), 'created_at', 'subtotal', 'discount', 'total', 'voucherType', 'voucherNumber', 'voucherSerie')
            ->where('clientId', $clientId)
            ->where('status', 1);

        switch($dateFilter){
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->wheredate('created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                $start_date = Carbon::parse($request->startDate);
                $end_date = Carbon::parse($request->endDate);
                
                if ($end_date->greaterThan($start_date)) {
                    $query->whereBetween('created_at', [$start_date, $end_date]);
                } else {
                    $query->whereDate('created_at', Carbon::today());
                }           
                break;           
        }  
        
        $list = $query->get();
        $totalPayment = $query->sum('total');
            
        return response()->json(['status'=>'success', 'list' => $list, 'totalPayment' => $totalPayment]);    
    }
}
