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
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     * @return bool
     */
    public function view(User $user, Vacancy $vacancy)
    {
        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role == 'employer';
    }

    /**
     * @return bool
     */
    public function book(User $user)
    {
        return true;
    }

    /**
     * @return bool
     */
    public function unbooked(User $user)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     * @return bool
     */
    public function update(User $user, Vacancy $vacancy)
    {
        $vacancy = Organization::find($vacancy->organization_id)->user_id;
        if ($user->role === 'employer' && $vacancy == $user->id){
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     * @return bool
     */
    public function delete(User $user, Vacancy $vacancy)
    {
        /** @var  $user */

        $vacancy = Organization::find($vacancy->organization_id)->user_id;
        if ( ($user->role === 'employer') && ($vacancy === $user->id) ){
            return true;
        }
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     */
    public function restore(User $user, Vacancy $vacancy)
    {
        //
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     */
    public function forceDelete(User $user, Vacancy $vacancy)
    {
        //
    }
}
