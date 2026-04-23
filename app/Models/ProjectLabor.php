<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectLabor extends Model
{
    protected $table = 'project_labor';

    protected $fillable = [
        'phase_id',
        'labor_type_id',
        'workers_count',
        'days',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'total_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProjectLabor $projectLabor): void {
            $rate = (float) ($projectLabor->laborType?->rate ?? LaborType::query()->whereKey($projectLabor->labor_type_id)->value('rate') ?? 0);
            $projectLabor->total_cost = $projectLabor->workers_count * $projectLabor->days * $rate;
        });
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(ProjectPhase::class, 'phase_id');
    }

    public function laborType(): BelongsTo
    {
        return $this->belongsTo(LaborType::class);
    }
}
