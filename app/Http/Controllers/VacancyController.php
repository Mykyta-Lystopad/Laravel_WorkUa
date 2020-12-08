<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancies\StoreVacancyRequest;
use App\Http\Requests\Vacancies\UpdateVacancyRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use App\Policies\VacancyPolicy;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VacancyController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource( Vacancy::class );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /** @var  $vacancy */
        $vacancy = Vacancy::with('users')->get();
//        $vacancy->users;
        return response()->json($vacancy);
    }

    /**
     * Display the specified resource.
     *
     * @param Vacancy $vacancies
     * @return Response
     */
    public function show(Vacancy $vacancy)
    {
        return response()->json($vacancy, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createWeb(Organization $organization)
    {
        $org = $organization;
        return view('organization.vacancy.createWeb', compact('org'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreVacancyRequest $request)
    {
        /** @var User  $user */
        $user = auth()->user();
        $vacancy = Vacancy::create($request->validated());
        $organization = Organization::find($user->id);

//        $this->unbook($request);

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
        /** @var  $vacancy */
        $this->authorize('book', Vacancy::class);
//        dd($request);
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
     * @param StoreVacancyRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function storeWeb(StoreVacancyRequest $request, $id)
    {
        $vacancy = Vacancy::create($request->validated());
        $organization = Organization::find($id);
        $organization->vacancies()->save($vacancy);

        return redirect()->route('organization.indexWeb')->with('success', 'Вакансію створено');
    }

    /**
     * @param Organization $organization
     * @return JsonResponse
     */
    public function showWeb(Organization $organization)
    {

        $vacancies = $organization->vacancies;

        if (!$vacancies) {
            return redirect()->route('organization.indexWeb')->withErrors('Такої вакансії не існує');
        }
        return view('organization.vacancy.showWeb', compact('vacancies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Vacancy $vacancies
     * @return Response
     */
    public function edit(Vacancy $vacancies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVacancyRequest $request
     * @param Vacancy $vacancies
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVacancyRequest $request, $id)
    {
        dd('her');
        $vacancies = Vacancy::find($id);
        $vacancies->update($request->validated());
        return response()->json($vacancies);
    }

    /**
     * Remove the specified resource from storage
     * @param Vacancy $vacancies
     * @return Response
     */
    public function destroy($id)
    {
        $vacancies = Vacancy::find($id);
        $vacancies->delete();
        return response()->json(['message'=> 'object '. $id . ' deleted'], 204);
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function destroyWeb($id){
        $vacancy = Vacancy::find($id);
        $vacancy->delete();

        return redirect()->route('organization.indexWeb')->with('success', 'Вакансію видалено');
    }
}
