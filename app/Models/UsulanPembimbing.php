<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsulanPembimbing extends Model
{
    protected $table = 'usulan_pembimbing';

    public $timestamps = false; 

    protected $fillable = [
        'proposal_id',
        'nim_nid_dosen',
        'urutan',
        'status',
        'tanggal_usulan',
    ];
}