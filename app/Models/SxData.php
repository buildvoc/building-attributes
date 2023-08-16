<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SxData extends Model
{
    use HasFactory;

    protected $fillable = [
        'os_topo_toid',
        'os_topo_toid_version',
        'bha_process_date',
        'tile_ref',
        'abs_min',
        'abs_h2',
        'abs_h_max',
        'rel_h2',
        'rel_h_max',
        'bha_conf',
    ];
}
