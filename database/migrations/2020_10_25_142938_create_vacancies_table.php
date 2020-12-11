<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organization::class)->default(1);
            $table->boolean('status')->default(true);
            $table->string('name');
            $table->unsignedInteger('workers_need');
            $table->bigInteger('booking')->default(0);
            $table->string('salary');
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
//        Schema::table('vacancies', function (Blueprint $table) {
//            $table->dropSoftDeletes();
//        });
    }
}
