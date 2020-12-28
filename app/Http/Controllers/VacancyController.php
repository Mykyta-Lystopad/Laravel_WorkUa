<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancies\StoreRequest;
use App\Http\Requests\Vacancies\UpdateRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\VacancyService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Class VacancyController
 * @package App\Http\Controllers
 */
class VacancyController extends Controller
{
    private $vacancyService;

    /**
     * VacancyController constructor.
     * @param VacancyService $vacancyService
     */
    public function __construct(VacancyService $vacancyService)
    {
        $this->authorizeResource(Vacancy::class, 'vacancy');
        $this->vacancyService = $vacancyService;
    }

    /**
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            if ((request()->only_active === 'false') || (request()->only_active === null)) {
                $vacancy = Vacancy::all();
                return response()->json($vacancy);
            }
        }

        $vacanciesActive = Vacancy::all()->where('status', 'active');
        return response()->json($vacanciesActive);
    }

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     */
    public function show(Vacancy $vacancy)
    {
        $user = auth()->user();
        if ($user->role === 'worker') {
            return response()->json($vacancy);
        }
        $workers = $vacancy->load('users');
        return $this->success($workers);
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $vacancy = Vacancy::make($request->validated());
        $vacancy = Organization::find($request->organization_id)->vacancies()->save($vacancy);
        return $this->success($vacancy);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function book(Request $request)
    {
        $this->authorize('book', Vacancy::class);

        $book = $this->vacancyService->bookAndUnbookWorkers($request->user_id, $request->vacancy_id, 1);
        return $this->success($book);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function unbooked(Request $request)
    {
        $this->authorize('unbooked', Vacancy::class);

        $unbook = $this->vacancyService->bookAndUnbookWorkers($request->user_id, $request->vacancy_id);
        return $this->success($unbook);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Vacancy $vacancy
     * @return Response
     */
    public function update(UpdateRequest $request, Vacancy $vacancy)
    {
        $vacancy->update($request->validated());
        return response()->json($vacancy);
    }

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

    }
}
