<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->time('punch_in')->nullable()->after('status');
            $table->time('punch_out')->nullable()->after('punch_in');
            $table->text('remarks')->nullable()->after('punch_out');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['punch_in', 'punch_out']);
        });
    }
};
