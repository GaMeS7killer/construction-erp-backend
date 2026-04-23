<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    protected $fillable = [
        'project_id',
        'materials_total',
        'labor_total',
        'equipment_total',
        'overhead_pct',
        'profit_pct',
        'contingency_pct',
        'grand_total',
        'status',
        'notes',
    ];

    protected $casts = [
        'materials_total' => 'decimal:2',
        'labor_total' => 'decimal:2',
        'equipment_total' => 'decimal:2',
        'overhead_pct' => 'decimal:2',
        'profit_pct' => 'decimal:2',
        'contingency_pct' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Quotation $quotation): void {
            $base = (float) $quotation->materials_total + (float) $quotation->labor_total + (float) $quotation->equipment_total;
            $factor = 1 + ((float) $quotation->overhead_pct + (float) $quotation->profit_pct + (float) $quotation->contingency_pct) / 100;
            $quotation->grand_total = $base * $factor;
        });
    }

    public static function generateFromProject(Project $project, array $percentages = []): static
    {
        $project->loadMissing('phases.materials', 'phases.labor', 'phases.equipment');

        $materialsTotal = (float) $project->phases->flatMap->materials->sum(
            fn (ProjectMaterial $item) => (float) $item->qty_estimated * (float) $item->unit_price
        );
        $laborTotal = (float) $project->phases->flatMap->labor->sum('total_cost');
        $equipmentTotal = (float) $project->phases->flatMap->equipment->sum('total_cost');

        return static::query()->create([
            'project_id' => $project->id,
            'materials_total' => $materialsTotal,
            'labor_total' => $laborTotal,
            'equipment_total' => $equipmentTotal,
            'overhead_pct' => $percentages['overhead_pct'] ?? 10,
            'profit_pct' => $percentages['profit_pct'] ?? 15,
            'contingency_pct' => $percentages['contingency_pct'] ?? 5,
            'status' => 'draft',
            'notes' => $percentages['notes'] ?? null,
        ]);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
