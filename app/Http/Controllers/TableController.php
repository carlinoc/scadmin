<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    
    public function index(): View
    {
        $tables = Table::select('tables.id', 'name', 'ability', 'placeId', 'place')->join('places', 'places.id','=','tables.placeId')->get();
        $places = Place::all();

        $heads = [
            'ID',
            'Lugar',
            'Nombre',
            'Capacidad',
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

        Table::create($request->all());
        return redirect()->route('tables.index')->with('success', 'Nuevo mesa creada');
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

    public function update(Request $request, Table $table): RedirectResponse
    {
        $table->update($request->all());
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
}
