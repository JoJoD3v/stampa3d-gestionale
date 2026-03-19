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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('height_cm', 7, 2)->nullable()->after('print_minutes');
            $table->decimal('width_cm',  7, 2)->nullable()->after('height_cm');
            $table->decimal('depth_cm',  7, 2)->nullable()->after('width_cm');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['height_cm', 'width_cm', 'depth_cm']);
        });
    }
};
