<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool|Response
     */
    public function viewAny(User $user)
    {
        if ($user->role === 'admin' || $user->role === 'employer')
        {
            return true;
        }
        return $this->deny('Workers can not view organization(s)');
    }

    /**
     * @param User $user
     * @param Organization $organization
     * @return bool|Response
     */
    public function view(User $user, Organization $organization)
    {
        if ($user->role === 'admin' || ($user->role == 'employer' && $organization->user_id == $user->id))
        {
            return true;
        }
        elseif ($user->role === 'worker')
        {
            return $this->deny('Workers can not view organization(s)');
        }
        elseif ($organization->user_id !== $user->id)
        {
            return $this->deny('Organization dose not belong to you');
        }
    }

    /**
     * @param User $user
     * @return bool|Response
     */
    public function create(User $user)
    {
        if ($user->role === 'employer')
        {
            return true;
        }
        return $this->deny('Admin or workers can not create organization(s)');
    }

    /**
     * @param User $user
     * @return bool|Response
     */
    public function storeForEmployers(User $user)
    {
        $userOwner = User::find(request()->user_id);
//        dd($userOwner);
        if ($userOwner->role !== 'employer')
        {
            return $this->deny('Admin can not create organization for yourself or workers');
        }
        if ($user->role === 'admin')
        {
            return true;
        }
        return $this->deny('Admins only');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        if ($user->role === 'admin' || ($user->role === 'employer' && $organization->user_id === $user->id))
        {
            return true;
        }
        elseif ($user->role === 'employer' && $organization->user_id !== $user->id)
        {
            return $this->deny('This organization not yours');
        }
        elseif ($user->role === 'worker')
        {
            return $this->deny('Worker can not update any organizations');
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        /** @var  $user */
        if ($user->role === 'admin' || ($user->role === 'employer' && ($organization->user_id === $user->id)))
        {
            return true;
        }
        elseif ($user->role === 'employer' && $organization->user_id !== $user->id)
        {
            return $this->deny('This organization not yours');
        }
        elseif ($user->role === 'worker')
        {
            return $this->deny('Worker can not delete any organizations');
        }
        return $this->deny('Action denied');

    }

}
