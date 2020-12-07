<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyPolicy
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
     * @param  User  $user
     * @param  Vacancy  $vacancy
     * @return mixed
     */
    public function view(User $user, Vacancy $vacancy)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
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
     * @return bool
     */
    public function book()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function unbook()
    {
        return true;
    }
    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Vacancy  $vacancy
     * @return mixed
     */
    public function update(User $user)
    {
        dd('her');
//        $organization = Organization::find($user->id);
//        $vacancy = Vacancy::find($organization->user_id);
//        dd($vacancy);
//        if ($user->role === 'employer' && $vacancy->organization_id == $user->id){
//            return true;
//        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Vacancy  $vacancy
     * @return mixed
     */
    public function delete(User $user, Vacancy $vacancy)
    {
        /** @var  $user */
        $organization = Organization::find($vacancy->organization_id);
        if ($user->role === 'employer' && $organization->user_id === $user->id ){
            return true;
        }
        return response()->json(['message'=>'object ' . $organization->id . ' deleted']);

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vacancy  $vacancy
     * @return mixed
     */
    public function restore(User $user, Vacancy $vacancy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vacancy  $vacancy
     * @return mixed
     */
    public function forceDelete(User $user, Vacancy $vacancy)
    {
        //
    }
}
