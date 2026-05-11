<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanJudul extends Model
{
    protected $table = 'pengajuan_judul';

    protected $fillable = [
        'nim_nid',
        'nama',              // ← tambah ini kalau pakai kolom nama
        'topik_1', 'judul_1',
        'topik_2', 'judul_2',
        'topik_3', 'judul_3',
        'mitra_penelitian',
        'tanggal_pengajuan',
        'status',
    ];
}