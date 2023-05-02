<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use App\Models\AdminUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
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
        //return $department->hasUser($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminUser $user, Department $department)
    {
        if($user->hasRole('admin')){
            return true;
        }
        // dd($user);
        // dd($department);
        return $department->hasUser($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AdminUser $user)
    {
        if($user->hasRole(['admin','department'])){
            return true;
        }

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminUser $user, Department $department)
    {
        if($user->hasRole('admin')){
            return true;
        }
        return $department->hasUser($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminUser $user, Department $department)
    {
        if($user->hasRole('admin')){
            return true;
        }
        return $department->hasUser($user);

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AdminUser $user, Department $department)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AdminUser $user, Department $department)
    {
        //
    }
}
