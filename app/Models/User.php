<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public static function boot()
    {
        parent::boot();

        self::deleting(function (self $model){
            foreach ($model->organizations as $organization){
                $organization->delete();
            }
            $vacancies = Vacancy::all();
            foreach ($vacancies as $vacancy){

                $users = $vacancy->users;
                foreach ($users as $user){
                    if ($model->id == $user->id){

                        $vacancy->users()->detach($user);
                    }
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'verify_status',
        'password',
        'country',
        'city',
        'telephone',
        'role'
    ];

    protected $attributes = [
        'role'=>'worker',
        'verify_status'=>'waiting'
    ];

    /**
     * @return HasMany
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    /**
     * @return BelongsToMany
     */
    public function Vacancies() {
        return $this->belongsToMany(Vacancy::class)->withTimestamps();
    }

    /**
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::make($value);
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
