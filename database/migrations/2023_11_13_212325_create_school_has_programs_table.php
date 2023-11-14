<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_has_programs', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id')->constrained('schools');
            $table->integer('program_id')->constrained('programs');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('payment_limit');
            $table->integer('price');
            $table->integer('initial_fee');
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
        Schema::dropIfExists('school_has_programs');
    }
};
