<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\AdminUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(AdminUser $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminUser $user, Member $member)
    {
        if($user->hasRole('admin')){
            return true;
        }
        $organizations=$member->organizations;
        foreach($organizations as $organization){
            if(in_array($organization->id, $user->organizations->pluck('id')->toArray())){
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AdminUser $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminUser $user, Member $member)
    {
        if($user->hasRole('admin')){
            return true;
        }
        $organizations=$member->organizations;
        foreach($organizations as $organization){
            if(in_array($organization->id, $user->organizations->pluck('id')->toArray())){
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminUser $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AdminUser $user, Member $member)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AdminUser $user, Member $member)
    {
        //
    }
}
