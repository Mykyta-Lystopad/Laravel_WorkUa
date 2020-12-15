<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class Organization
 * @package App\Models
 */
class Organization extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

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



}
