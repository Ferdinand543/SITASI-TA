<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $table = 'proposal';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nim_nid',
        'judul',
        'file_proposal',
        'tanggal_pengajuan',
        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI KE MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'nim_nid', 'nim_nid');
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI TINJAUAN
    |--------------------------------------------------------------------------
    */

    public function tinjauan()
    {
        return $this->hasMany(TinjauanProposal::class, 'proposal_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI DOSEN PEMBIMBING
    |--------------------------------------------------------------------------
    */

    public function dosenPembimbing()
    {
        return $this->hasMany(DosenPembimbing::class, 'proposal_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI USULAN PEMBIMBING
    |--------------------------------------------------------------------------
    */

    public function usulanPembimbing()
    {
        return $this->hasMany(UsulanPembimbing::class, 'proposal_id');
    }
}