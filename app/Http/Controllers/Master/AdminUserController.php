<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use App\Models\AdminUser;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(){
        return Inertia::render('Master/AdminUsers',[
            'adminUsers'=>AdminUser::with('departments')->get(),
            'departments'=>Department::all()
        ]);
    }
    public function store(Request $request){
        $adminUser=new AdminUser();
        $adminUser->name=$request->name;
        $adminUser->email=$request->email;
        $adminUser->password=Hash::make($request->password);
        $adminUser->save();
        $adminUser->departments()->attach($request->departments);
        $adminUser->roles()->attach(Role::where('name','department')->first());
        return redirect()->back();
    }

    public function update(AdminUser $adminUser,Request $request){
        $adminUser->name=$request->name;
        $adminUser->email=$request->email;
        $adminUser->save();
        $adminUser->departments()->sync($request->departments);
        return redirect()->back();
    }
}
