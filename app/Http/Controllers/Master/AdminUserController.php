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
        if(isset($data['password'])){
            $data['password']=Hash::make($data['password']);
        }
        $adminUser=AdminUser::where('email',$data['email'])->get();
        if($adminUser){
            return redirect()->back()->withError(['message'=>'Can not create with duplicate email!']);
        }
        $adminUser=AdminUser::create($data);
        if(isset($data['belongs_departments'])){
            $adminUser->departments()->sync(Department::whereIn('abbr',$data['belong_departments'])->get());
        }
        if(isset($data['manage_departments'])){
            $adminUser->roles()->sync(Role::whereIn('name',$data['manage_departments'])->get());
        }
        
        return redirect()->back();
    }

    public function update(AdminUser $adminUser,Request $request){
        $data=$request->all();
        if(isset($data['password'])){
            $data['password']=Hash::make($data['password']);
        }
        $adminUser=AdminUser::where('email',$data['email'])->get();
        if($adminUser){
            return redirect()->back()->withError(['message'=>'Can not update with duplicate email!']);
        }
        $adminUser->update($data);
        if(isset($data['belongs_departments'])){
            $adminUser->departments()->sync(Department::whereIn('abbr',$data['belong_departments'])->get());
        }
        if(isset($data['manage_departments'])){
            $adminUser->roles()->sync(Role::whereIn('name',$data['manage_departments'])->get());
        }
        return redirect()->back();
    }
}
