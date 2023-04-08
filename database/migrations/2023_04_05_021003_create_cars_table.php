<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('registration')->unique();
            $table->string('color');
            $table->string('brand');
            $table->enum('type', ['public','particular']);
            $table->foreignId('owner_id');
            $table->bigInteger('driver_id')->unsigned()->nullable();
            $table->foreign("driver_id")->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
