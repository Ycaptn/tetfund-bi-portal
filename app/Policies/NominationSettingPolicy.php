<?php

namespace App\Policies;

use App\Models\NominationSetting;
use Hasob\FoundationCore\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NominationSettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Hasob\FoundationCore\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Hasob\FoundationCore\Models\User  $user
     * @param  \App\Models\NominationSetting  $nominationSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, NominationSetting $nominationSetting)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Hasob\FoundationCore\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Hasob\FoundationCore\Models\User  $user
     * @param  \App\Models\NominationSetting  $nominationSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, NominationSetting $nominationSetting)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Hasob\FoundationCore\Models\User  $user
     * @param  \App\Models\NominationSetting  $nominationSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, NominationSetting $nominationSetting)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Hasob\FoundationCore\Models\User  $user
     * @param  \App\Models\NominationSetting  $nominationSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, NominationSetting $nominationSetting)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Hasob\FoundationCore\Models\User  $user
     * @param  \App\Models\NominationSetting  $nominationSetting
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, NominationSetting $nominationSetting)
    {
        //
    }
}
