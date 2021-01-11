<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancies\StoreRequest;
use App\Http\Requests\Vacancies\UpdateRequest;
use App\Http\Resources\VacancyResource;
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
        $vacancies = $this->vacancyService->forIndex();
        return $this->success(VacancyResource::collection($vacancies));
    }

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     */
    public function show(Vacancy $vacancy)
    {
       $vacancyOrWorkers = $this->vacancyService->forShow($vacancy);
        return $this->success(new VacancyResource($vacancyOrWorkers));
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $vacancy = Vacancy::make($request->validated());
        $vacancy = Organization::find($request->organization_id)->vacancies()->save($vacancy);
        return $this->success(new VacancyResource($vacancy));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function book(Request $request)
    {
        $this->authorize( Vacancy::class);

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
        $this->authorize(Vacancy::class);

        $unbook = $this->vacancyService->bookAndUnbookWorkers($request->user_id, $request->vacancy_id);
        return $this->success($unbook);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Vacancy $vacancy
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Vacancy $vacancy)
    {
        $vacancy->update($request->validated());
        return $this->success(new VacancyResource($vacancy));
    }

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return $this->deleted();
    }
}
