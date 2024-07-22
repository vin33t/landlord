<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('iata', 3)->nullable();
            $table->string('icao')->nullable();
            $table->string('callsign')->nullable();
            $table->string('country')->nullable();
            $table->string('active', 1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airlines');
    }
};
