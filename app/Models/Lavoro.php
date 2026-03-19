<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lavoro extends Model
{
    protected $fillable = [
        'customer_id',
        'preventivo',
        'scadenza',
        'status',
        'note',
    ];

    protected $casts = [
        'scadenza'   => 'date',
        'preventivo' => 'decimal:2',
    ];

    const STATUS_LABELS = [
        'bozza'          => 'Bozza',
        'confermato'     => 'Confermato',
        'in_lavorazione' => 'In Lavorazione',
        'completato'     => 'Completato',
        'consegnato'     => 'Consegnato',
    ];

    const STATUS_COLORS = [
        'bozza'          => '#9ca3af',
        'confermato'     => '#023059',
        'in_lavorazione' => '#d97706',
        'completato'     => '#059669',
        'consegnato'     => '#7c3aed',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'lavoro_progetto')
                    ->withPivot('quantita');
    }

    public function getNumeroAttribute(): string
    {
        return 'LAV-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalFilamentAttribute(): int
    {
        return (int) $this->projects->sum(fn ($p) => ($p->filament_grams ?? 0) * $p->pivot->quantita);
    }

    public function getTotalMinutesAttribute(): int
    {
        return (int) $this->projects->sum(fn ($p) => (($p->print_hours ?? 0) * 60 + ($p->print_minutes ?? 0)) * $p->pivot->quantita);
    }

    public function getScadenzaFormattedAttribute(): ?string
    {
        return $this->scadenza?->format('d/m/Y');
    }
}
