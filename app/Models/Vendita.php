<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendita extends Model
{
    protected $table = 'vendite';

    protected $fillable = [
        'project_id',
        'importo',
        'data_vendita',
        'note',
    ];

    protected $casts = [
        'importo'      => 'decimal:2',
        'data_vendita' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
