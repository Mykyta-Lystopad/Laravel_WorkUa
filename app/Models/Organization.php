<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Organization
 * @package App\Models
 */
class Organization extends Model
{
    use HasFactory, SoftDeletes;

    public static function boot()
    {
        parent::boot();

        self::deleting(function (self $model){
            foreach ($model->vacancies as $vacancy){
                $vacancy->delete();
            }
        });
    }

    protected $fillable = [
        "title",
        "country",
        "city"
    ];

    /**
     * @return HasMany
     */
    public function vacancies(){
        return $this->hasMany(Vacancy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
