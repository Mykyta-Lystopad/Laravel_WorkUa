<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VacancyPolicy
{
    use HandlesAuthorization;

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
     * @return bool|Response
     */
    public function view(User $user, Vacancy $vacancy)
    {
        $organization = Organization::find($vacancy->organization_id);
        if ($user->role === 'employer' && $organization->user_id === $user->id
            || $user->role === 'admin'
            || $user->role === 'worker') {
            return true;
        }
        return $this->deny('This vacancy not yours');
    }

    /**
     * @param User $user
     * @return bool|Response
     */
    public function create(User $user)
    {

        $organization = Organization::find(request()->organization_id);
        if ($user->role === 'employer' && $organization->user_id === $user->id) {
            return true;
        }
        elseif ($user->role !== 'employer')
        {
            return $this->deny('Employers only');
        }
        elseif ($organization->user_id !== $user->id)
        {
            return $this->deny('This organization not yours');
        }

    }

    /**
     * @return bool|Response
     */
    public function book(User $user)
    {
        if ($user->role === 'employer')
        {
            return $this->deny('Employers can not booking');
        }

        if (($user->id === request()->user_id) || $user->role === 'admin'){
            return true;
        }
    }

    /**
     * @param User $user
     * @return bool|Response
     */
    public function unbooked(User $user)
    {
        if ($user->role === 'admin'){
            return true;
        }
        if ($user->id === request()->user_id){
            return true;
        }
        if ($user->role === 'worker' && $user->id !== request()->user_id){
            return $this->deny('You did not unbook on this vacancy');
        }
        // owner or not
        $vacanciesId = $user->organizations()->with('vacancies')
            ->get()->pluck('vacancies')->flatten()->pluck('id');
        foreach ($vacanciesId as $id) {
            if (request()->vacancy_id === $id) {
                return true;
            }

        }
        return $this->deny('This vacancy not yours');
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     * @return bool|Response
     */
    public function update(User $user, Vacancy $vacancy)
    {
        $organization = Organization::find($vacancy->organization_id);
        if ($user->role === 'employer' && $organization->user_id === $user->id || $user->role === 'admin') {
            return true;
        }
        return $this->deny('This vacancy not yours');
    }

    /**
     * @param User $user
     * @param Vacancy $vacancy
     * @return bool|Response
     */
    public function delete(User $user, Vacancy $vacancy)
    {
        /** @var  $user */

        $organization = Organization::find($vacancy->organization_id);
        if ( ($user->role === 'employer'  && $organization->user_id === $user->id) || $user->role === 'admin'){
            return true;
        }
        return $this->deny('This vacancy not yours');
    }
}
