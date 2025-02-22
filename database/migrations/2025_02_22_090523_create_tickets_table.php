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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('department');
            $table->string('responsible_department');
            $table->string('concern_type');
            $table->integer('urgency');
            $table->string('serial_number');
            $table->text('remarks')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Denied', 'Send to Supplier'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
