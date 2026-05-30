<?php
// database/migrations/xxxx_create_penilaian_seminar_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penilaian_seminar', function (Blueprint $table) {
            $table->id();
            $table->string('nim_nid');               // NIM mahasiswa
            $table->string('nim_nid_penguji');        // NID dosen penguji
            $table->unsignedBigInteger('proposal_id');

            // Komponen nilai (sesuaikan dengan rubrik kampus lo)
            $table->decimal('nilai_penguasaan_materi', 5, 2)->nullable();
            $table->decimal('nilai_penyajian', 5, 2)->nullable();
            $table->decimal('nilai_tanya_jawab', 5, 2)->nullable();
            $table->decimal('nilai_sistematika', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();

            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamps();

            $table->unique(['nim_nid', 'nim_nid_penguji', 'proposal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_seminar');
    }
};