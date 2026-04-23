<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectPhase extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'order_num',
        'status',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ProjectMaterial::class, 'phase_id');
    }

    public function labor(): HasMany
    {
        return $this->hasMany(ProjectLabor::class, 'phase_id');
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(ProjectEquipment::class, 'phase_id');
    }
}
