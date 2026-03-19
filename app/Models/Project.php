<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'filament_type',
        'filament_grams',
        'print_hours',
        'print_minutes',
    ];

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    public function getPrintTimeAttribute(): string
    {
        $parts = [];
        if ($this->print_hours > 0)   $parts[] = $this->print_hours . 'h';
        if ($this->print_minutes > 0) $parts[] = $this->print_minutes . 'min';
        return $parts ? implode(' ', $parts) : '—';
    }
}
