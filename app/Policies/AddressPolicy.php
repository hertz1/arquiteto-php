<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Address $address
     * @return mixed
     */
    public function view(User $user, Address $address)
    {
        return $user->uuid === $address->user_uuid;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Address $address
     * @return mixed
     */
    public function update(User $user, Address $address)
    {
        return $user->uuid === $address->user_uuid;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Address $address
     * @return mixed
     */
    public function delete(User $user, Address $address)
    {
        return $user->uuid === $address->user_uuid;
    }
}
