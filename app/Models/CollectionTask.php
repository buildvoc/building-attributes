<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionTask extends Model
{
    use HasFactory;

    protected $table = 'collections_tasks';
    protected $connection = 'brick';
    public $timestamps = false;

    protected $fillable = [
        'organization_id',
        'collection_id',
        'task_id',
        'submissions_needed'
    ];
}
