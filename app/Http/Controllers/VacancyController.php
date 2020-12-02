<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organizations\UpdateOrganizationRequest;
use App\Http\Requests\Vacancies\StoreVacancyRequest;
use App\Http\Requests\Vacancies\UpdateVacancyRequest;
use App\Models\Organization;
use App\Models\Organization_Vacancy;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Vacancy::class );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $vacancy = Vacancy::all();
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
     */
    public function store(StoreVacancyRequest $request)
    {
        /** @var User  $user */
            $user = auth()->user();
            $vacancy = Vacancy::create($request->validated());
            $organization = Organization::find($user->id);
            $organization->vacancies()->save($vacancy);

            return response()->json($vacancy, 201);
     }


    /**
     * @param StoreVacancyRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function storeWeb(StoreVacancyRequest $request, $id)
    {
//        dd($request, $id);
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
     * @return Response
     *
     */
    public function update(UpdateVacancyRequest $request, Vacancy $vacancies)
    {
            $vacancies->update($request->validated());
            return response()->json($vacancies);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vacancy $vacancies
     * @return Response
     */
    public function destroy(Vacancy $vacancies)
    {
        $vacancies->delete();

        return response()->json(['message'=> 'object '. $vacancies->id . ' deleted'], 204);
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
