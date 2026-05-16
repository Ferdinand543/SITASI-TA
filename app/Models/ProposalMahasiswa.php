<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalMahasiswa extends Model
{
    protected $table = 'proposal';

    protected $fillable = [
        'nim_nid',
        'judul',
        'file_proposal',
        'tanggal_pengajuan',
        'status',
    ];

    public function usulanPembimbing()
    {
        return $this->hasMany(UsulanPembimbing::class, 'proposal_id');
    }

    public function dosenPembimbing()
    {
        return $this->hasMany(DosenPembimbing::class, 'proposal_id');
    }

    public function tinjauanProposal()
    {
        return $this->hasMany(TinjauanProposal::class, 'proposal_id');
    }
}