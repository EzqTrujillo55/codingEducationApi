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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthdate');
            $table->string('nationality');
            $table->string('passport');
            $table->boolean('valid_visa');
            $table->date('end_of_validity');
            $table->string('student_email')->unique();
            $table->string('residence_country');
            $table->string('city');
            $table->string('postal_code');
            $table->string('emergency_contact_full_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_email');
            $table->string('emergency_contact_phone_number');
            $table->foreignId('parents_id')->constrained('familyparents');
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
        Schema::dropIfExists('students');
    }
};
