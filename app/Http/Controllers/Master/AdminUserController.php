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
        //dd(AdminUser::with('departments')->with('roles')->get());
        return Inertia::render('Master/AdminUsers',[
            'roles'=>Role::all(),
            'departments'=>Department::all(),
            'adminUsers'=>AdminUser::with('departments')->with('roles')->get()
        ]);
    }
    public function store(Request $request){
        $data=$request->all();
        $data['password']=Hash::make($data['password']);
        $adminUser=AdminUser::create($data);
        $adminUser->departments()->attach($request->departments);
        $adminUser->roles()->attach(Role::where('name','department')->first());
        return redirect()->back();
    }

    public function update(AdminUser $adminUser,Request $request){
        $adminUser->update($request->all());
        $adminUser->departments()->sync(Department::whereIn('abbr',$request->belong_departments)->get());
        $adminUser->roles()->sync(Role::whereIn('name',$request->manage_departments)->get());
        return redirect()->back();
    }
}
