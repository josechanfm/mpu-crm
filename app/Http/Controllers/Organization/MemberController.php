<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Organization;
use App\Models\Member;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
        $this->authorizeResource(Member::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $this->authorize('view',$organization);
        return Inertia::render('Organization/Member',[
            'members'=>$organization->members,
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
    public function show(Organization $organization, Member $member)
    {
        return Inertia::render('Organization/MemberShow',[
            //'member'=>$member->belongsToOrganization($organization)->first(),
            'member'=>$member,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization, Member $member)
    {
        return Inertia::render('Organization/MemberEdit',[
            //'member'=>$member->belongsToOrganization($organization)->first(),
            'member'=>$member,
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

    public function createLogin(Organization $organization, Member $member){
        $this->authorize('update',$member);
        if (!$member->hasUser()) {
            $user = $member->createUser();
        } else {
            $user = $member->user;
        }
        Password::broker(config('fortify.passwords'))->sendResetLink(
            [ 'email' => $user->email ]
        );
    }    
}
