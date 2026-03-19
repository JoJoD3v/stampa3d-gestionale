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
        Schema::table('lavori', function (Blueprint $table) {
            $table->foreignId('printer_id')->nullable()->after('customer_id')
                  ->constrained('printers')->nullOnDelete();
            $table->dateTime('avvio_stampa_at')->nullable()->after('printer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lavori', function (Blueprint $table) {
            $table->dropForeign(['printer_id']);
            $table->dropColumn(['printer_id', 'avvio_stampa_at']);
        });
    }
};
