<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections';
    protected $connection = 'brick';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'organization_id',
        'title',
        'url'
    ];
}
