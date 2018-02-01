<?php

namespace App;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class SamplePolicy
{
    use HandlesAuthorization;

    //todo: add extra checks. For example: user->id === $requestedItem->owner->id

    public function index(User $user)
    {
        if (!$user->hasPermissionTo(SamplePermissions::RETRIEVE_ALL_SAMPLES)) {
            return false;
        }

        return true;
    }

    public function show(User $user, $requestedItem)
    {
        if ($user->hasPermissionTo(SamplePermissions::RETRIEVE_ALL_SAMPLES)) {
            return true;
        }

        return $user->hasPermissionTo(SamplePermissions::RETRIEVE_SAMPLE);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(SamplePermissions::CREATE_SAMPLE);
    }

    public function update(User $user, $requestedItem)
    {
        return $user->hasPermissionTo(SamplePermissions::UPDATE_SAMPLE);
    }

    public function delete(User $user, $requestedItem)
    {
        return $user->hasPermissionTo(SamplePermissions::DELETE_SAMPLE);
    }
}
