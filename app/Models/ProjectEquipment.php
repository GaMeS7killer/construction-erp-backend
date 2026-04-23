<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectEquipment extends Model
{
    protected $table = 'project_equipment';

    protected $fillable = [
        'phase_id',
        'equipment_id',
        'qty_used',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'qty_used' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProjectEquipment $projectEquipment): void {
            $rate = (float) ($projectEquipment->equipment?->rate_per_unit ?? Equipment::query()->whereKey($projectEquipment->equipment_id)->value('rate_per_unit') ?? 0);
            $projectEquipment->total_cost = $projectEquipment->qty_used * $rate;
        });
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(ProjectPhase::class, 'phase_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
}
