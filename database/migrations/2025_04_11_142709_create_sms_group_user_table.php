<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('sms_group_user', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('sms_group_id')->constrained()->onDelete('cascade');
    //         $table->foreignId('user_sms_id')->constrained('users_sms')->onDelete('cascade');
    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('sms_group_user');
    // }
};
