<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFile extends Model
{
    protected $fillable = ['project_id', 'original_name', 'path', 'size'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 0)    . ' KB';
        return $bytes . ' B';
    }

    public function getExtensionAttribute(): string
    {
        return strtoupper(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }
}
