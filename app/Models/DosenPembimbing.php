<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    protected $table = 'dosen_pembimbing';

    public $timestamps = false; 

    protected $fillable = [
        'proposal_id',
        'nim_nid_dosen',
        'urutan',
        'tanggal_penetapan',
    ];
}