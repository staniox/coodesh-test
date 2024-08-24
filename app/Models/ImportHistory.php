<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ImportHistory extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'filename', 'imported_at', 'status', 'imported_products', 'memory', 'peak_memory',
    ];

    protected $casts = [
        'imported_at' => 'datetime',
        'imported_products' => 'array',
        'memory' => 'integer',
        'peak_memory' => 'integer',
    ];
}
