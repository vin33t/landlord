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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('country')->default('United Kingdom');
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->date('dob')->nullable();
            $table->string('client_type')->nullable();
            $table->boolean('has_passport')->default(false);
            $table->string('passport_number')->nullable();
            $table->string('place_of_issue')->nullable();
            $table->date('passport_expire_date')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->string('passport_front')->nullable();
            $table->string('passport_back')->nullable();
            $table->string('permission_letter')->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->decimal('credit_limit', 10, 2)->nullable();
            $table->string('currency')->default('£');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
