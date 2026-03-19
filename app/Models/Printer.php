<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Printer extends Model
{
    protected $fillable = [
        'name',
        'model',
        'status',
    ];

    const STATUSES = [
        'spenta'       => 'Spenta',
        'accesa'       => 'Accesa',
        'in_uso'       => 'In Uso',
        'manutenzione' => 'Manutenzione',
    ];

    const STATUS_COLORS = [
        'spenta'       => 'status-off',
        'accesa'       => 'status-on',
        'in_uso'       => 'status-busy',
        'manutenzione' => 'status-maintenance',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'status-off';
    }

    public function lavoroAttivo(): HasOne
    {
        return $this->hasOne(Lavoro::class, 'printer_id');
    }
}
