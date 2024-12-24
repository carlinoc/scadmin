<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Js;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    
    public function index(): View
    {
        $products = Product::select('products.id', 'products.name', 'price', 'stock', 'categoryId', 'categories.Name as category')
            ->join('categories', 'categories.id','=','products.categoryId')
            ->orderBy('name')
            ->get();
        
        $heads = [
            'Nombre',
            'Tipo',
            'Precio',
            'Stock',
            'Acciones'
        ];

        return view('products.index', ['products' => $products, 'heads' => $heads]);
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('products.create', ['categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'inCharge' => 'required',
            'categoryId' => 'required',
            'cost' => 'required',
            'price' => 'required'   
        ]);       


        $rows = DB::table('products')->where('name', $request->name)->count();
        if($rows==0) {
            $product = new Product();
            $product->name = $request->name; 
            $product->code = $request->code; 
            $product->cost = $request->cost; 
            $product->price = $request->price; 
            $product->inCharge = $request->inCharge; 
            $product->categoryId = $request->categoryId; 

            if($request->dueDate!=""){
                $product->dueDate = Carbon::parse($request->dueDate); 
            }
            
            if($request->useInventory){
                $product->useInventory = 1;
                $product->stock = $request->stock; 
                $product->minStock = $request->minStock; 
            }else{
                $product->useInventory = 0;
                $product->stock = 0; 
                $product->minStock = 0; 
            }
            $product->save();

            if($request->hasFile('image')){
                $path = 'files/products/';
                $file = time() .'-'. $product->id . '.jpg';
                $success = $request->file('image')->move($path, $file);
                if($success){
                    DB::table('products')->where('id', $product->id)->update(['image' => $path . $file]);
                }
            }
            return redirect()->route('products.index')->with('success', 'Nuevo producto creado');
        }else{
            return redirect()->route('products.create')->with('error', 'Ya existe un producto con el mismo nombre');
        }
    }
        
    public function edit(Product $product): View
    {
        $categories = Category::all();

        return view('products.edit', ['product'=>$product, 'categories' => $categories]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product = Product::find($product->id);
        if($request->hasFile('image')){
            if(!is_null($product->image)){
                File::delete($product->image);
            }
            $path = 'files/products/';
            $file = time() .'-'. $product->id . '.jpg';
            $success = $request->file('image')->move($path, $file);
            if($success){
                $product->image = $path . $file;    
            }
        }

        $product->name = $request->name; 
        $product->code = $request->code; 
        $product->cost = $request->cost; 
        $product->price = $request->price; 
        $product->inCharge = $request->inCharge; 
        $product->categoryId = $request->categoryId; 

        if($request->dueDate!=""){
            $product->dueDate = Carbon::parse($request->dueDate); 
        }
        
        if($request->useInventory){
            $product->useInventory = 1;
            $product->stock = $request->stock; 
            $product->minStock = $request->minStock; 
        }else{
            $product->useInventory = 0;
            $product->stock = 0; 
            $product->minStock = 0; 
        }

        $product->update();

        return redirect()->route('products.index')->with('success', 'Producto actualizado');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $rows = DB::table('sales_detail')->where('productId', $product->id)->count();
        if($rows == 0) {
            $row = Product::find($product->id);
            if(strlen($row) > 0) {
                if($row->image!=""){
                    File::delete($row->image);    
                }
            }
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Producto eliminado');
        }else{
            return redirect()->route('products.index')->with('error', 'No se puede elimiar un producto com ventas relacionadas');
        }   
    }

    public static function getImage($image){
        if(is_null($image)){
            return "images/movie-default.jpg";
        }
        return $image;
    }

    public function list(Request $request): JsonResponse
    {
        $categoryId = $request->categoryId;

        $list = Product::select('id', 'name')
            ->where('categoryId', $categoryId)
            ->get();
                        
        return response()->json(['status'=>'success', 'list' => $list]);
    }
}
