<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinjauanProposal extends Model
{
    protected $table = 'tinjauan_proposal';

    public $timestamps = false; 
    
    protected $fillable = [
        'proposal_id',
        'nim_nid_reviewer',
        'catatan',
        'file_tinjauan',
        'tanggal_tinjauan',
    ];
}