<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'telefono',
        'indirizzo',
        'citta',
        'cap',
        'provincia',
        'origine',
        'note',
    ];

    public function getFullNameAttribute(): string
    {
        return trim($this->nome . ' ' . $this->cognome);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->indirizzo,
            $this->citta ? ($this->cap ? $this->cap . ' ' . $this->citta : $this->citta) : null,
            $this->provincia ? '(' . strtoupper($this->provincia) . ')' : null,
        ]);
        return implode(', ', $parts);
    }
}
