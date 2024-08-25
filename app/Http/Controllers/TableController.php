<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Place;
use App\Models\SalesDetail;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    
    public function index(): View
    {
        $tables = Table::select('tables.id', 'name', 'ability', 'placeId', 'place', 'active')->join('places', 'places.id','=','tables.placeId')->get();
        $places = Place::all();

        $heads = [
            'ID',
            'Lugar',
            'Nombre',
            'Capacidad',
            'Activo',
            'Acciones'
        ];

        return view('tables.index', ['tables' => $tables, 'heads' => $heads, 'places' => $places]);
    }
    
    public function create(): View
    {
        $places = Place::all();

        return view('tables.create', ['places' => $places]);
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'placeId' => 'required'   
        ]);

        $active = 0;
        if($request->active != ""){
            $active = 1;
        }
                
        $table = new Table();
        $table->name = $request->name;
        $table->placeId = $request->placeId;
        $table->ability = $request->ability;
        $table->active = $active;
        $table->save();

        return redirect()->route('tables.index')->with('success', 'Nueva mesa creada');
    }
    
    public function show(Table $table)
    {
        //
    }

    public function edit(Table $table): View 
    {
        $places = Place::all();

        return view('tables.edit', ['table'=>$table, 'places' => $places]);
    }

    public function update(Request $request, Table $table) 
    {
        $active = 0;
        if($request->active != ""){
            $active = 1;
        }
                
        $table = Table::find($table->id);
        $table->name = $request->name;
        $table->placeId = $request->placeId;
        $table->ability = $request->ability;
        $table->active = $active;
        $table->update();

        return redirect()->route('tables.index')->with('success', 'Mesa actualizada');
    }

    public function destroy(Table $table)
    {
        $rows = DB::table('sales')->where('tableId', $table->id)->count();
        if($rows == 0) {
            $table->delete();
            return redirect()->route('tables.index')->with('success', 'Mesa eliminada');
        }else{
            return redirect()->route('tables.index')->with('error', 'No se puede elimiar una mesa com ventas relacionadas');
        }   
    }    

    public function clean(Request $request) 
    {
        $table = Table::find($request->tableId);
        $table->state = 0;
        $table->update();

        return response()->json(['status'=>'success', 'message'=>'El estado fue actualizado']);
    }

    public function clear(Request $request)
    {
        try {
            SalesDetail::where('saleId', $request->saleId)->delete();
            Sale::find($request->saleId)->delete();

            return response()->json(['status'=>'success', 'message'=>'La mesa fue desocupada']);
        } catch (\Throwable $th) {
            return response()->json(['status'=>'error', 'message'=>'Error al desocupar la mesa']);
        }
    }
}
