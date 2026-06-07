<?php

namespace KY\AdminPanel\Policies;

use Illuminate\Auth\Access\Response;
use KY\AdminPanel\Contracts\UserContract;

class RolePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    public function list(UserContract $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return Response|bool
     */
    public function show(UserContract $user, $model)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create(UserContract $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(UserContract $user, $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
     */
    public function delete(UserContract $user, $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(UserContract $user, $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete(UserContract $user, $model)
    {
        return $user->hasRole('admin');
    }
}
