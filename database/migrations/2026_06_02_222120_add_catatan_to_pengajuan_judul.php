<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_judul', function (Blueprint $table) {
            $table->text('catatan_1')->nullable()->after('mitra_1');
            $table->text('catatan_2')->nullable()->after('mitra_2');
            $table->text('catatan_3')->nullable()->after('mitra_3');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_judul', function (Blueprint $table) {
            $table->dropColumn(['catatan_1', 'catatan_2', 'catatan_3']);
        });
    }
};