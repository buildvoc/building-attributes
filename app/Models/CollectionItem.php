<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $connection = 'brick';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'organization_id',
        'collection_id',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
