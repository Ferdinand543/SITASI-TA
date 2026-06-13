<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_proposal_bimbingan', function (Blueprint $table) {
            $table->string('status_admin')->default('pending')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_proposal_bimbingan', function (Blueprint $table) {
            $table->dropColumn('status_admin');
        });
    }
};