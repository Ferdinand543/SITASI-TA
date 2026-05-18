<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanJudul extends Model
{
    protected $table = 'pengajuan_judul';

    protected $fillable = [
        'nim_nid',
        'nama',
        'tanggal_pengajuan',
        'topik_1', 'judul_1', 'mitra_1',
        'topik_2', 'judul_2', 'mitra_2',
        'topik_3', 'judul_3', 'mitra_3',
        'status',
        'judul_disetujui',
    ];
}