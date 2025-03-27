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
            $table->string('equipment')->nullable();
            $table->enum('urgency', ['Not Urgent', 'Neutral', 'Urgent']);
            $table->string('serial_number');
            $table->text('remarks')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('status', ['Pending', 'Approved By Admin', 'Accepted', 'In Progress', 'Denied', 'Completed', 'Defective'])->default('Pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('approval_date')->nullable();
            $table->timestamp('accepted_date')->nullable();
            $table->integer('days_from_request')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->integer('days_to_complete')->nullable();
            $table->integer('total_duration')->nullable();
            $table->string('remarks_by')->nullable();
            $table->string('completed_by')->nullable();
            $table->string('responsible_remarks')->nullable();
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
