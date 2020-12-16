<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancies\StoreRequest;
use App\Http\Requests\Vacancies\UpdateRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
        $this->authorizeResource(Vacancy::class, 'vacancy');
    }

    /**
     * @param $user
     * @param $id
     * @param $vacancy2
     * @return JsonResponse
     */
    public function vacanciesHelper($user, $id, $vacancy2)
    {
        if ($id == 2) {
            $organization = Organization::all();
        }
        if ($id == 1) {
            $organization = Organization::where('user_id', $user->id)->get();
        }
        if ($id == 3) {
            $organization = Organization::where('id', $vacancy2->organization_id)->get();
            if ($organization->contains($user->id)) {
                $vacancy2->load('users');
                return response()->json($vacancy2);
            }
            return response()->json(['message' => 'It is not of your vacancy or vacancy is not exist']);
        }
        foreach ($organization as $org) {
            foreach ($org->vacancies as $vacancy) {
                if ($vacancy->users->count() === $vacancy->workers_amount) {

                    $vacancy_closed[] = ($vacancy->withoutRelations());

                } elseif ($vacancy->users->count() < $vacancy->workers_amount) {
                    $vacancy_active[] = ($vacancy->withoutRelations());
                    $vacancy_active_relations[] = ($vacancy);
                }
            }
        }
        if ($id === 1) {
            return response()->json($vacancy_closed);
        }
        if ($id === 2) {
            if ($vacancy2 != null) {
                foreach ($vacancy_active as $value) {
                    if ($vacancy2->id == $value->id) {
                        return response()->json($vacancy2);
                    }
                }
                return response()->json(['message' => 'Vacancy closed']);
            }
            return response()->json($vacancy_active);

        }

    }

    /**
     * @param Request $request
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'worker' || $user->role === 'employer') {
            return response()->json($this->vacanciesHelper($user, 2, null));
        }
        if ($user->role === 'admin') {
            if ($request->only_active == true) {
                return response()->json($this->vacanciesHelper($user, 2, null));
            } else {
                $vacancy = Vacancy::all();
                return VacancyResource::collection($vacancy);
            }
        }
    }

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     */
    public function show(Vacancy $vacancy)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $vacancy->load('users');

            return response()->json($vacancy);
        } elseif ($user->role === 'employer') {
            return response()->json($this->vacanciesHelper($user, 3, $vacancy));
        }

        return response()->json($this->vacanciesHelper($user, 2, $vacancy));
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $user = auth()->user();
        $organization = Organization::find($request->organization_id);
        if ($organization->user_id === $user->id)
        {
            $vacancy = Vacancy::create($request->validated());
            $organization->vacancies()->save($vacancy);
            return response()->json($vacancy, 201);
        }
        else
        {
            return $this->error(['message' => 'Action refused'], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function book(Request $request)
    {
        $this->authorize('book', Vacancy::class);

        $vacancy = Vacancy::find($request->vacancy_id); // вакансия
        $user = User::find($request->user_id);          // пользователь
        $authUser = auth()->user();                     // авторезир пользователь
        $owner = Organization::find($vacancy->organization_id)->user_id; // айди владельца вакансии

        if ($vacancy->users->count() < $vacancy->workers_amount) {

            if ($vacancy->users->contains($user->id)) {
                return $this->success(['message' => 'You already booked'], 202);
            }
            if ( $user->role === 'worker' &&
                ($authUser->id === $request->user_id
                || $authUser->role === 'admin'
                || $authUser->id === $owner) )
            {
                $user->vacancies()->attach($vacancy);
                return $this->success(['message' => 'Booking success'], 200);
            }
        } elseif ($vacancy->users->count() == $vacancy->workers_amount) {
            return $this->error(['message' => 'Vacancy closed'], 403);
        }
        // запрет на регистрацию работодателей, себя как работодателя на свою вакансию и ост.
        if (($user->role === 'employer' || $user->role === 'admin') && $authUser->id !== $user->id)
        {
            return $this->error(['message' => 'You can not booking any employers or admins'], 403);
        }
        if ($user->role === 'employer' && $authUser->id === $user->id)
        {
            return $this->error(['message' => 'You can not booking for yourself'], 403);
        }
        return $this->success(['message' => 'Booking refused'], 403);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function unbooked(Request $request)
    {
        $this->authorize('unbooked', Vacancy::class);

        $vacancy = Vacancy::find($request->vacancy_id);
        $user = User::find($request->user_id);
        $authUser = auth()->user();
        $owner = Organization::find($vacancy->organization_id)->user_id;
        if (($user->id == $authUser->id) || ($authUser->id == $owner) || $authUser->role == 'admin') {
            if ($vacancy->users->contains($user->id)) {
                $user->vacancies()->detach($vacancy);
                return $this->success(['message' => 'User ' . $user->first_name . ' unbooked'], 200);
            }
//            elseif ($user->role == 'employer' || $user->role == 'admin' || $user->id !== $authUser->id)
//            {
//                return $this->error(['message' => 'Unbooking refuse'], 403);
//            }
            else {
                return $this->success(['message' => 'You did not book'], 200);
            }
        }
        return $this->error(['message' => 'Unbooking refused'], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\Response
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
        $users = \DB::table('user_vacancy')->where('vacancy_id', $vacancy->id)->delete();
        $vacancy->delete();
        return response()->json(['message' => 'Object was deleted'], 204);
    }
}
