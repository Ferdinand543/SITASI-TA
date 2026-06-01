<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSeminar extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'judul_ta',
        'progress_dokumen',
        'total_dokumen',
        'status_administrasi',
        'status_seminar',
        'tanggal_seminar',
        'waktu_mulai',
        'waktu_selesai',
        'ruang',
        'is_draft',
        'draft_data',
        'rencana_tanggal_seminar',
        'file_proposal',
        'file_khs',
        'file_krs',
        'file_spp',
        'file_bimbingan',
        'file_persetujuan',
        'file_laporan_doc',
        'file_laporan_pdf',
        'semester',
        'dosen_wali',
        'ipk',
        'total_sks',
        'sks_nilai_d',
        'mk_nilai_d',
        'sks_semester',
        'total_sks_akumulasi',
        'catatan_admin',     // ← TAMBAHAN
        'status_dokumen',    // ← TAMBAHAN
        'catatan_dokumen',   // ← TAMBAHAN
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'nim_nid');
    }

    public function dokumens()
    {
        return $this->hasMany(DokumenSeminar::class, 'pengajuan_id');
    }

    public function getProgressPersenAttribute(): int
    {
        if ($this->total_dokumen == 0) return 0;
        return (int) round(($this->progress_dokumen / $this->total_dokumen) * 100);
    }
}