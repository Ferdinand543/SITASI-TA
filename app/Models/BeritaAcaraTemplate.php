<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcaraTemplate extends Model
{
    protected $table = 'berita_acara_templates';

    protected $fillable = [
        'file_path',
        'nama_file_asli',
    ];
}