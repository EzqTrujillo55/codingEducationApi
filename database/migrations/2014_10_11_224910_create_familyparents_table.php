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
        Schema::create('familyparents', function (Blueprint $table) {
            $table->id();
            $table->string('mothers_name');
            $table->string('mothers_phone');
            $table->string('mothers_email')->unique();
            $table->string('fathers_name');
            $table->string('fathers_phone');
            $table->string('fathers_email')->unique();
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('familyparents');
    }
};
