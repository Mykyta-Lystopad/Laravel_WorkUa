<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    public $counter = 0;

    protected $fillable = [
      "name",
      "workers_need",
      "salary"
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
//    public function organizations() {
//        return $this->belongsToMany(Organization::class)->withTimestamps();
//    };

//    public function Organization() {
//        return $this->belongsToMany(Organization::class);
//    }


}
