<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateQuotationRequest;
use App\Http\Requests\QuotationStatusRequest;
use App\Models\Project;
use App\Models\ProjectMaterial;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;

class QuotationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Quotation::query()->with('project')->latest()->paginate(15));
    }

    public function show(Quotation $quotation): JsonResponse
    {
        $quotation->load('project.client');
        return response()->json(['data' => $quotation]);
    }

    public function generate(GenerateQuotationRequest $request, Project $project): JsonResponse
    {
        $data = $request->validated();

        $project->load(['phases.materials', 'phases.labor', 'phases.equipment']);

        $materialsTotal = (float) $project->phases->flatMap->materials->sum(
            fn (ProjectMaterial $item) => (float) $item->qty_estimated * (float) $item->unit_price
        );
        $laborTotal = (float) $project->phases->flatMap->labor->sum('total_cost');
        $equipmentTotal = (float) $project->phases->flatMap->equipment->sum('total_cost');

        $quotation = Quotation::query()->create([
            'project_id' => $project->id,
            'materials_total' => $materialsTotal,
            'labor_total' => $laborTotal,
            'equipment_total' => $equipmentTotal,
            'overhead_pct' => $data['overhead_pct'] ?? 10,
            'profit_pct' => $data['profit_pct'] ?? 15,
            'contingency_pct' => $data['contingency_pct'] ?? 5,
            'status' => 'draft',
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json(['message' => 'تم إنشاء عرض السعر تلقائياً.', 'data' => $quotation], 201);
    }

    public function updateStatus(QuotationStatusRequest $request, Quotation $quotation): JsonResponse
    {
        $quotation->update($request->validated());
        return response()->json(['message' => 'تم تحديث حالة عرض السعر.', 'data' => $quotation]);
    }
}
