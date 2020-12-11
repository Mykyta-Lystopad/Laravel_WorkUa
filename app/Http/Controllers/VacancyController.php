<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancies\StoreRequest;
use App\Http\Requests\Vacancies\UpdateRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;

/**
 * Class VacancyController
 * @package App\Http\Controllers
 */
class VacancyController extends Controller
{

    /**
     * VacancyController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(  Vacancy::class, 'vacancy' );
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        /** @var  $vacancy */
        $vacancy = Vacancy::with('users')->get();
        return response()->json($vacancy);
    }

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     */
    public function show(Vacancy $vacancy)
    {
        return response()->json($vacancy, 200);
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $user = auth()->user();
        $vacancy = Vacancy::create($request->validated());
        $organization = Organization::find($user->id);
        $organization->vacancies()->save($vacancy);
        return response()->json($vacancy, 201);
     }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function book($id)
    {
        $this->authorize('book', Vacancy::class);
        $vacancy = Vacancy::find($id);
        $user = auth()->user();
        $vacancy_user_id = $vacancy->users->find($user->id);

        if ($vacancy_user_id === null && $vacancy->workers_need > 0)
        {
            $vacancy->workers_need -= 1;
            $vacancy->booking += 1;

            $vacancy->update();
            $user->vacancies()->attach($vacancy);
            return response()->json(['message' => 'Booking success'], 200 );
        }
        elseif ($vacancy_user_id === null || $vacancy->workers_need === 0)
        {
            $vacancy->status = false;
            $vacancy->update();
            return response()->json(['message' => 'Vacancy - closed'], 200 );
        }
        elseif ($vacancy_user_id->id === $user->id)
        {
            return response()->json(['message' => 'This user booked already'], 200 );
        }

    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function unbook($user_id, $vacancy_id)
    {
        $this->authorize('unbook', Vacancy::class);

        $auth_user = auth()->user();

        $vacancy = Vacancy::find($vacancy_id);
        $organization_belong = Organization::find($vacancy->organization_id)->user_id;
        $user = User::find($user_id);
        $vacancy_user_id = $vacancy->users->find($user_id);

        if ($vacancy_user_id === null)
        {
            return response()->json(['message'=>'User was not booked'] );
        }

        elseif ( ($auth_user->role === 'admin')
            || ($auth_user->id === $organization_belong)
            || ($auth_user->id === $vacancy_user_id->id) )
        {

            $vacancy->workers_need += 1;
            $vacancy->booking -= 1;
            if ($vacancy->status === false)
            {
                $vacancy->status = true;
            }
            $vacancy->update();
            $user->vacancies()->detach($vacancy);
            return response()->json(['message'=>'User '. $user->first_name .' was unbooked successfully']);
        }

        return response()->json(['message'=>'You can not unbook this user']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Vacancy $vacancies
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateRequest $request, Vacancy $vacancy)
    {
        $vacancies = Vacancy::find($vacancy->id);
        $vacancies->update($request->validated());
        return response()->json($vacancies);
    }

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return response()->json(['message'=> 'Object was deleted'], 204);
    }
}
