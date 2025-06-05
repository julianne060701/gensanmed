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
        Schema::create('schedule_list', function (Blueprint $table) {
            $table->id();
            $table->text('event');
            $table->text('description');
            $table->text('from_department');
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->enum('status', ['Active', 'Done', 'Inactive'])->default('Active');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
