<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Vacancy
 * @package App\Models
 */
class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "vacancy_name",
        "workers_amount",
        "salary"
    ];

    protected $appends = [
        'workers',
        'status'
    ];

    public function getWorkersAttribute()
    {
        return $this->users()->count();
    }

    public function getStatusAttribute()
    {
        return $this->workers_amount > $this->workers ? 'active' : 'closed';
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @return BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    public static function boot()
    {
        parent::boot();

        self::deleting(function (self $model) {
            $users = $model->users;
            $model->users()->detach($users);
        });
    }
}
