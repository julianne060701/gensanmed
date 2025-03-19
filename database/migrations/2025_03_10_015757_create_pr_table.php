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
            $table->string('po_number')->nullable(); // Added po_number column
            $table->string('requester_name');
            $table->text('description')->nullable(); // Added description column
            $table->enum('status', ['Pending For Admin', 'Pending For PO', 'Approved', 'Denied', 'Pending Delivery', 'Hold'])->default('Pending For Admin');
            $table->string('attachment_url')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('remarks')->nullable(); // Moved remarks column
            $table->timestamps();
            $table->timestamp('approval_date')->nullable();
            $table->timestamp('accepted_date')->nullable();
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
