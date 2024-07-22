<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->after('tenant_id');
            $table->string('zip_code')->nullable()->after('type');
            $table->string('country')->nullable()->after('address');
            $table->string('city')->nullable()->after('type');
            $table->string('state')->nullable()->after('type');
            $table->string('contact_person_name')->nullable()->after('country');
            $table->text('phone')->nullable()->after('country');
            $table->string('country_code')->nullable()->after('country');
            $table->string('dial_code')->nullable()->after('country_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}