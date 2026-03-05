<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('smm_providers', function (Blueprint $table) {
            $table->decimal('conversion_rate', 15, 6)->default(1)->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('smm_providers', function (Blueprint $table) {
            $table->dropColumn('conversion_rate');
        });
    }
};
