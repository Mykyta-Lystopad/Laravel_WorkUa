<?php

namespace App\Policies;

use App\Http\Requests\Organizations\StoreOrganizationRequest;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->role === 'admin'){
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        /** @var  $user */
        if ($user->role == 'employer'){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Organization  $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        if ($user->role === 'employer' && $organization->user_id === $user->id){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        /** @var  $user */
        if ($user->role === 'employer' && $organization->user_id === $user->id ){
            return true;
        }
        return response()->json(['message'=>'object ' . $organization->id . ' deleted']);

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return mixed
     */
    public function restore(User $user, Organization $organization)
    {
//        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Organization  $organization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $organization)
    {
//        return true;
    }
}
