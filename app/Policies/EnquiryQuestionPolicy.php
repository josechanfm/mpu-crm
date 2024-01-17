<?php

namespace App\Policies;

use App\Models\EnquiryQuestion;
use App\Models\User;
use App\Models\AdminUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnquiryQuestionPolicy
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
     * @param  \App\Models\EnquiryQuestion  $enquiryQuestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminUser $user, EnquiryQuestion $enquiryQuestion)
    {
     
        return true;
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
     * @param  \App\Models\EnquiryQuestion  $enquiryQuestion
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function update(AdminUser $user, EnquiryQuestion $enquiryQuestion)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EnquiryQuestion  $enquiryQuestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminUser $user, EnquiryQuestion $enquiryQuestion)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EnquiryQuestion  $enquiryQuestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, EnquiryQuestion $enquiryQuestion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EnquiryQuestion  $enquiryQuestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, EnquiryQuestion $enquiryQuestion)
    {
        //
    }
}
