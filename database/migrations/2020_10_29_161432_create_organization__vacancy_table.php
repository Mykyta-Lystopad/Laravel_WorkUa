<?php

use App\Models\Organization;
use App\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationVacancyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_vacancy', function (Blueprint $table) {
            $table->primary(['organization_id', 'vacancy_id']);
            $table->foreignIdFor(Organization::class, 'organization_id');
            $table->foreignIdFor(Vacancy::class, 'vacancy_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_vacancy');
    }
}
