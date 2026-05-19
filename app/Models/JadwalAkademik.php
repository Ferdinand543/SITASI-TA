<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalAkademik extends Model
{
    protected $table = 'jadwal_akademik';

    protected $fillable = [
        'nama_kegiatan',
        'sub_judul',
        'kategori',
        'status',
        'tanggal',
        'waktu',
        'lokasi',
        'deskripsi'
    ];

    public $timestamps = false;
}