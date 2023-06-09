<?php

namespace KY\AdminPanel\Policies;

use Str;

use Illuminate\Auth\Access\HandlesAuthorization;
use KY\AdminPanel\Contracts\UserContract;
use KY\AdminPanel\Facades\AdminPanel;

class BasePolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view any models.
     *
     * @param  UserContract  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function list(UserContract $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  UserContract  $user
     * @param  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function show(UserContract $user,$model)
    {
        return false;
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  UserContract  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(UserContract $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  UserContract  $user
     * @param  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserContract $user, $model)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  UserContract  $user
     * @param  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserContract $user, $model)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  UserContract  $user
     * @param  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(UserContract $user, $model)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  UserContract  $user
     * @param  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(UserContract $user, $model)
    {
        return true;
    }

}
