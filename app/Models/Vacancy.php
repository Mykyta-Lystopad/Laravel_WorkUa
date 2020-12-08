<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Vacancy
 * @package App\Models
 */
class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      "name",
      "workers_need",
      "salary"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Users() {
        return $this->belongsToMany(User::class);
    }


}
