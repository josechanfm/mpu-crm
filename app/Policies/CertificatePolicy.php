<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\AdminUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertificatePolicy
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
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminUser $user, Certificate $certificate)
    {
        if($user->hasRole('admin')){
            return true;
        }
        return $certificate->organization->hasUser($user);
        
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
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminUser $user, Certificate $certificate)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminUser $user, Certificate $certificate)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AdminUser $user, Certificate $certificate)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AdminUser $user, Certificate $certificate)
    {
        //
    }
}
