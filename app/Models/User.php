<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'verify_code',
        'password',
        'country',
        'city',
        'telephone',
        'role'
    ];

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::make($value);
    }

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'role' => 'worker'
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function vacancies()
    {
        return $this->belongsToMany(Vacancy::class)->withTimestamps();
    }

    public function createdVacancies()
    {
        return $this->hasManyThrough(Vacancy::class, Organization::class);
    }

    // softDelete for organization(s)
    public static function boot()
    {
        parent::boot();

        self::deleting(function (self $model) {
            foreach ($model->organizations as $organization) {
                $organization->delete();
            }
        });
    }

    public function scopeUserSearch($query, $request)
    {
        return $query->where('country', 'like', '%' . $request->search . '%')
            ->orWhere('city', 'like', '%' . $request->search . '%')
            ->orWhere('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->orWhere('country', 'like', '%' . $request->search . '%')
            ->orWhere('role', 'like', '%' . $request->search . '%');
    }

}
