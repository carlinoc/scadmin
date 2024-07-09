<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $places = Place::all();

        $heads = [
            'ID',
            'Lugar',
            'Descripción',
            'Acciones'
        ];

        return view('places.index', ['places' => $places, 'heads' => $heads]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('places.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'place' => 'required'
        ]);

        Place::create($request->all());
        return redirect()->route('places.index')->with('success', 'Nuevo lugar creado');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place): View
    {
        //
        return view('places.edit', ['place'=>$place]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place): RedirectResponse
    {
        $place->update($request->all());
        return redirect()->route('places.index')->with('success', 'Lugar actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place): RedirectResponse
    {
        $rows = DB::table('tables')->where('placeId', $place->id)->count();
        if($rows == 0) {
            $place->delete();
            return redirect()->route('places.index')->with('success', 'Lugar eliminado');
        }else{
            return redirect()->route('places.index')->with('error', 'No se puede elimiar un lugar com mesas relacionadas');
        }   
    }
}
