<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sms_group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_group_id')->constrained('sms_groups')->onDelete('cascade');
            $table->foreignId('user_s_m_s_id')->constrained('users_sms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_group_user');
    }
};
