<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;


class RecycleUserController extends Controller
{
     public function trash(){
        $users = User::with('role')
        ->where('is_delete',1)
        ->orderBy('id','desc')
        ->get();
        $roles = Role::all();
        return view('admin.trashUser', compact('users','roles'));

    }

    public function restore ( Request $request){
        $user = User::findOrFail($request->id);

        $user->is_delete = 0;
        $user->status = 1;

        $user->save();

         return redirect()->back()->with('success', 'User restored successfully!');
    }
    
    public function delete ( Request $request){
    $user = User::findOrFail($request->id);
    $user->Delete();

    return redirect()->back()->with('success', 'User deleted successfully!');
    }
}
