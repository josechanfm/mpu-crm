<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\Organization;
use Inertia\Inertia;

class OrganizationController extends Controller
{
    
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth()->user()->hasRole('admin')){
            $organizations=Organization::all();
        }else{
            $organizations=AdminUser::find(Auth()->user()->id)->organizations;
        }

        if($organizations->count()==1){
            return redirect()->route('organizations.show',$organizations[0]->id);
        }elseif($organizations->count()>1){
            return Inertia::render('Organization/Selection',[
                'organizations' => $organizations
            ]);
        }else{
            echo '!!';
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        return Inertia::render('Organization/Dashboard',[
            'organization'=>$organization
        ]);
        //return redirect(route('organization.certificates.index',[$organization->id]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {         

        //$this->authorize('update' , $organization);


        return Inertia::render('Organization/OrganizationEdit',[
            'organization'=>$organization,
        ]);
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
