<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $areas = Area::all();
        return view('staff.index', ['areas' => $areas]);
    }

    public function list(Request $request): JsonResponse
    {
        $staffs = Staff::select('staff.id', 'staff.name', 'staff.dni', 'staff.phone1', 'staff.phone2', 'staff.address', 'staff.email', 'staff.description', 'staff.areaId', 'area.area')
            ->join('area', 'area.id', '=', 'staff.areaId')
            ->get();

        return response()->json(['staffs' => $staffs]);
    }

    public function add(Request $request): JsonResponse 
    {
        $staff = new Staff();
        $staff->name = $request->name;
        $staff->dni = $request->dni;
        $staff->phone1 = $request->phone1;
        $staff->phone2 = $request->phone2;
        $staff->address = $request->address;
        $staff->email = $request->email;
        $staff->description = $request->description;
        $staff->areaId = $request->areaId;
        $staff->save();

        return response()->json(['status'=>'success', 'message'=>'El personal fue agregado']);    
    }

    public function edit(Request $request): JsonResponse
    {
        $staff = Staff::find($request->staffId);
        $staff->name = $request->name;
        $staff->dni = $request->dni;
        $staff->phone1 = $request->phone1;
        $staff->phone2 = $request->phone2;
        $staff->address = $request->address;
        $staff->email = $request->email;
        $staff->description = $request->description;
        $staff->areaId = $request->areaId;
        $staff->update();

        return response()->json(['status'=>'success', 'message'=>'El personal fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        $rows = DB::table('expensestaff')->where('staffId', $request->staffId)->count();
        if($rows == 0) {
            Staff::find($request->staffId)->delete(); 
            return response()->json(['status'=>'success', 'message'=>'El personal fue eliminado']);     
        }else{
            return response()->json(['status'=>'success', 'message'=>'No se puede eliminar un personal con modulos relacionados']);     
        }   
    }
}
