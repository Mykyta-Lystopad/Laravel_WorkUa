<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequestRequest;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
//    /**
//     * UserController constructor.
//     * @throws \Illuminate\Auth\Access\AuthorizationException
//     */
//    public function __construct()
//    {
//        $this->authorizeResource(User::class, 'user');
//    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->search) {
            $user = \DB::table('users')
                ->where('country', 'like', '%' . $request->search . '%')
                ->orWhere('city', 'like', '%' . $request->search . '%')
                ->orWhere('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%')
                ->get();
            return response()->json($user);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * @param RegisterRequestRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RegisterRequestRequest $request, $id)
    {
        $this->authorize('update', [User::class, $id]);
        $user = User::find($id);
        $user->update($request->validated());

        return response()->json($user);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->authorize('delete', [User::class, $id ]);

        $user = User::find($id);
        if ($user->role == 'admin' || $user->id == $id) {
            $vacancies = Vacancy::where('organization_id', $user->id)->delete();
            $organization = Organization::where('user_id', $user->id)->delete();
            $user->delete();

            return response()->json(['message'=>'user with '. $id .' id - SoftDeleted']);
        }
        return response()->json(['message'=>'error']);
    }
}
