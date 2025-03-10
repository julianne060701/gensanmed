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
        Schema::create('pr', function (Blueprint $table) {
            $table->id();
            $table->integer('request_number')->unique();
            $table->string('requester_name');
            $table->text('remarks')->nullable();
            $table->enum('status', ['Pending For PO', 'Approved', 'Denied', 'Pending Delivery', 'Hold'])->default('Pending For PO');
            $table->string('attachment_url')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr');
    }
};
