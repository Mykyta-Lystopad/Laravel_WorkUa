<?php


namespace App\Services;


use App\Http\Resources\VacancyResource;
use App\Models\User;
use App\Models\Vacancy;

class VacancyService
{
    public function forIndex()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            if ((request()->only_active === 'false') || (request()->only_active === null)) {
                $vacancies = Vacancy::paginate();
            }
        }
        $vacanciesActive = Vacancy::all()->where('status', 'active');
        return $vacanciesActive;
    }

    public function forShow(Vacancy $vacancy)
    {
        $user = auth()->user();
        if ($user->role === 'worker') {
            return $vacancy;
        }
        $workers = $vacancy->load('users');
        return $workers;
    }

    public function bookAndUnbookWorkers($user_id, $vacancy_id, $key = 2)
    {
        $vacancy = Vacancy::find($vacancy_id);
        $userBooked = $vacancy->users()->get()->contains($user_id);
//        dd($userBooked);
        if ($userBooked && $key == 1)
        {
            return ['message' => 'You already booked'];
        }
        elseif (!$userBooked && $key == 1)
        {
            $usersId = $vacancy->users()->syncWithoutDetaching($user_id);
            return ['message' =>'You have booked, successfully'];
        }
        if ($userBooked && $key != 1)
        {
            $vacancy->users()->detach($user_id);
            return ['message' =>'You unbooked, successfully'];
        }
        return ['message'=>'User was not booked'];
    }


}
