<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('smm_providers', function (Blueprint $table) {
            $table->timestamp('last_synced_at')->nullable()->after('is_active');
            $table->unsignedInteger('total_services')->default(0)->after('last_synced_at');
            $table->unsignedInteger('imported_services')->default(0)->after('total_services');
        });
    }

    public function down(): void
    {
        Schema::table('smm_providers', function (Blueprint $table) {
            $table->dropColumn(['last_synced_at', 'total_services', 'imported_services']);
        });
    }
};
