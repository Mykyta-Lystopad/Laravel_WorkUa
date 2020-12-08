<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Organization
 * @package App\Models
 */
class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "orgName",
        "country",
        "city"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vacancies(){
        return $this->hasMany(Vacancy::class);
    }



}
