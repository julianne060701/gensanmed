<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_sms_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sms_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. "Board of Directors"
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sms_groups');
    }
};
