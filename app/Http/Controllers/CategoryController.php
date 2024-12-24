<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        //
        $categories = Category::all();

        $heads = [
            'ID',
            'Nombre',
            'Descripción',
            'Acciones'
        ];
        return view('categories.index', ['categories' => $categories, 'heads' => $heads]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $request->validate([
            'name' => 'required'
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoria agregada');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        //
        return view('categories.edit', ['category'=>$category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $rows = DB::table('products')->where('categoryId', $category->id)->count();
        if($rows == 0) {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Categoría eliminada');
        }else{
            return redirect()->route('categories.index')->with('error', 'No se puede elimiar una categoría con productos');
        }   
    } 
}
