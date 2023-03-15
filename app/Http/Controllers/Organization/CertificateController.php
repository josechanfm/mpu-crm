<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Organization;
use App\Models\Certificate;
use App\Models\Member;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
        $this->authorizeResource(Certificate::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $this->authorize('view',$organization);
        return Inertia::render('Organization/Certificate',[
            'organization' => $organization,
            'certificates'=>$organization->certificates,
        ]);

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
    public function show(Organization $organization, Certificate $certificate)
    {
        return redirect(route('organization.certificate.memebers',[$organization->id,$certificate->id]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function members(Organization $organization, Certificate $certificate){
        $this->authorize('view',$organization);
        $this->authorize('view',$certificate);
        return Inertia::render('Organization/CertificateMember',[
            'organization'=>$organization,
            'certificate'=>$certificate,
            'members'=>$certificate->members
        ]);
        
    }
}
