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
        Schema::create('notifications_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('email_notification')->default(0)->comment('1-On 0-Off');
            $table->string('sms_notification')->default(0)->comment('1-On 0-Off');
            $table->string('notify')->default(0)->comment('1-On 0-Off');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('notifications_settings');
    }
};
