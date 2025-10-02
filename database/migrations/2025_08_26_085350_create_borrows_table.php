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
        Schema::create('borrows', function (Blueprint $table) {
            $table->bigIncrements('id'); // auto-increment primary key
            $table->string('borrower_name');
            $table->string('purpose');
            $table->string('location');
            $table->string('type_of_equipment');
            $table->dateTime('borrowed_at'); // date & time of borrowing
            $table->dateTime('returned_at')->nullable(); // date & time returned
            $table->tinyInteger('status')->default(0)->comment('0 = pending, 1 = accept, 2 = borrowed, 3 = returned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};
