<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('user.index', ['roles' => $roles]);
    }

    public function list(Request $request): JsonResponse
    {
        $users = User::select('users.id', 'users.name', 'users.email', 'roles.name as role', 'roles.id as roleId') 
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->get();

        return response()->json(['users' => $users]);
    }

    public function add(Request $request): JsonResponse
    {
        $rows = DB::table('users')->where('email', $request->email)->count();
        if($rows > 0 ) {
            return response()->json(['status'=>'error', 'message'=>'Ya existe un usuario con el mismo correo']);    
        }else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            if($request->roleId!=""){
                $userRole = User::find($user->id);
                $userRole->assignRole($request->roleId);
            }else{
                $userRole = User::find($user->id);
                $userRole->assignRole('Mozo');
            }

            return response()->json(['status'=>'success', 'message'=>'El usuario fue agregado']);    
        } 
    }

    public function edit(Request $request): JsonResponse
    {
        $user = User::find($request->userId);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->update();

        if($request->roleId!=""){
            DB::table('model_has_roles')->where('model_id', $request->userId)->delete();

            //$userRole = User::find($request->userId);
            $user->assignRole($request->roleId);
        }

        return response()->json(['status'=>'success', 'message'=>'El usuario fue actualizado']);    
    }

    public function remove(Request $request): JsonResponse
    {
        $rows = DB::table('sales')->where('userId', $request->userId)->count();
        if($rows > 0 ) {
            return response()->json(['status'=>'error', 'message'=>'No se puede eliminar usuarios con ventas relacionadas']);    
        }else{
            User::find($request->userId)->delete();      
            return response()->json(['status'=>'success', 'El Usuario fue eliminado']);
        }
    }
}
