<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategories;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ExpenseCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = ExpenseCategories::all()->where('isParent', 1);
        return view('expensecategories.index', ['categories' => $categories]);
    }

    public function list(Request $request): JsonResponse
    {
        $results = DB::select(
            DB::raw('
                with recursive cte (id, category, parentId, expenseType, isParent, plevel) as (
                    select id, category, parentId, expenseType, isParent, 1 from expensecategories
                    WHERE parentId is null
                    union all
                    select p.id, p.category, p.parentId, q.expenseType, q.isParent, q.plevel + 1 from expensecategories p
                    inner join cte q on p.parentId = q.id
                    )
                SELECT id, category, plevel, parentId, expenseType, isParent,
                (SELECT category FROM expensecategories WHERE id = cte.parentId) AS parent 
                from cte;
            ')
            ->getValue(DB::connection()->getQueryGrammar())
        );

        return response()->json(['status'=>'success', 'list' => $results]);    
    }

    public function add(Request $request): JsonResponse 
    {
        $isParent = $request->isParent;
        $expenseType = $request->expenseType;
        $parentId = $request->parentId;
        if($isParent != ""){
            $isParent = 1;
            $parentId = null;
        }else{
            $isParent = 0;
            $expenseType = 0;
        }

        $rows = DB::table('expensecategories')->where('category', trim($request->category))->count();
        if($rows > 0){
            return response()->json(['status'=>'error', 'message'=>'El nombre de la categoría ya existe']);
        }else{
            $expenseCategories = new ExpenseCategories();
            $expenseCategories->category = $request->category;
            $expenseCategories->expenseType = $expenseType;
            $expenseCategories->isParent = $isParent;
            $expenseCategories->parentId = $parentId;
            $expenseCategories->save();

            return response()->json(['status'=>'success', 'message'=>'La categoría fue agregada']);    
        }
    }

    public function edit(Request $request): JsonResponse
    {
        $isParent = $request->isParent;
        $expenseType = $request->expenseType;
        $parentId = $request->parentId;
        if($isParent != ""){
            $isParent = 1;
            $parentId = null;
        }else{
            $isParent = 0;
            $expenseType = 0;
        }

        $expenseCategories = ExpenseCategories::find($request->expenseCategoryId);
        $expenseCategories->category = $request->category;
        $expenseCategories->expenseType = $expenseType;
        $expenseCategories->isParent = $isParent;
        $expenseCategories->parentId = $parentId;
        $expenseCategories->update();

        return response()->json(['status'=>'success', 'message'=>'La categoría fue actualizada']);    
    }

    public function remove(Request $request): JsonResponse
    {
        ExpenseCategories::find($request->expenseCategoryId)->delete();
        
        return response()->json(['status'=>'success', 'message'=>'La categoría fue eliminada']);     
    }

    public function subcategories(Request $request): JsonResponse
    {
        $parentId = $request->parentId;
        $list = ExpenseCategories::where('parentId', $parentId)->get();

        return response()->json(['status'=>'success', 'list' => $list]);    
    }
}
