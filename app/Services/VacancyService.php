<?php


namespace App\Services;


use App\Models\User;
use App\Models\Vacancy;

class VacancyService
{

    public function bookAndUnbookWorkers($user_id, $vacancy_id, $key=2)
    {
        $vacancy = Vacancy::find($vacancy_id);
        $usersId = $vacancy->users()->get()->pluck('id');
        if ($key === 1){
            foreach ($usersId as $id) {
                if ($user_id === $id) {
                    return ['message'=>'You already booked'];
                }
            }
            $user = User::find($user_id);
            $vacancy->users()->attach($user);
            return ['You have booked, successfully'];
        }
        else{
            foreach ($usersId as $id) {
                if ($user_id === $id) {
                    $user = User::find($id);
                    $vacancy->users()->detach($user);
                    return ['You unbooked, successfully'];
                }
            }
            return ['User is absence'];
        }
    }
}
