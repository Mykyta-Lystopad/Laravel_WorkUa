<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class StatsPolicy
 * @package App\Policies
 */
class StatsPolicy
{

    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->role === 'admin'){
            return true;
        }
    }

    /**
     * @return bool
     */
    public function users()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function vacancies()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function organizations()
    {
        return false;
    }
}
