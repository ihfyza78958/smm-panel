<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // google_id already exists from previous migration; add the rest safely
            if (!Schema::hasColumn('users', 'github_id')) {
                $table->string('github_id')->nullable()->after('google_id');
            }
            if (!Schema::hasColumn('users', 'facebook_id')) {
                $table->string('facebook_id')->nullable()->after('github_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('users', 'github_id'))   $cols[] = 'github_id';
            if (Schema::hasColumn('users', 'facebook_id')) $cols[] = 'facebook_id';
            if ($cols) $table->dropColumn($cols);
        });
    }
};
