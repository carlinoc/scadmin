<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
        //todo: verificar que ya existe el ruc y el dni
        $client = new Client();
        $client->name = $request->name;
        $client->dni = $request->dni;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->email = $request->email;
        $client->level = $request->level;
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
        $client->level = $request->level;
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
}
