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
        Schema::create('purchaser_po', function (Blueprint $table) {
            $table->id();
            $table->integer('po_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['Pending',  'Approved', 'Denied', 'Send to Supplier'])->default('Pending');
            $table->string('image_url')->nullable();
            $table->string('admin_attachment')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->timestamp('approval_date')->nullable();
            $table->timestamp('accepted_date')->nullable();
            $table->integer('total_duration')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaser_po');
    }
};
