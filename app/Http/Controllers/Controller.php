<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data = null, int $status = JsonResponse::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success'=> true, // відображає результат запиту
            'data'=>$data, /*об'єкт, який ми повертаємо юзеру, або ресурс для пагінацій*/
        ], $status);
    }

    protected function error($data = null, int $status = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success'=> false,
            'data'=>$data  /*сюди пишеми повідомлення, що пішло не так(або набір повідомлень)*/
        ], $status);
    }

    protected function created($data=null): JsonResponse
    {
        return $this->success($data, JsonResponse::HTTP_CREATED);
    }

    protected function deleted():JsonResponse
    {
        return $this->success(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
