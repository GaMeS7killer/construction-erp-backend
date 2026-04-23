<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $projects = Project::query()
            ->with('client')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->latest()
            ->paginate(15);

        return response()->json($projects);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $project = Project::query()->create($request->validated());
        return response()->json(['message' => 'تم إنشاء المشروع بنجاح.', 'data' => $project], 201);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load([
            'client',
            'phases.materials.material',
            'phases.labor.laborType',
            'phases.equipment.equipment',
        ]);

        $latestQuotation = $project->quotations()->latest()->first();

        return response()->json([
            'data' => array_merge($project->toArray(), [
                'latest_quotation' => $latestQuotation,
            ])
        ]);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'client_id' => ['sometimes', 'required', 'exists:clients,id'],
        'type' => ['sometimes', 'string'],
        'status' => ['sometimes', 'in:planning,in_progress,completed,cancelled'],
        'area_m2' => ['sometimes', 'nullable', 'numeric'],
        'start_date' => ['sometimes', 'nullable', 'date'],
        'end_date' => ['sometimes', 'nullable', 'date'],
        'notes' => ['sometimes', 'nullable', 'string'],
    ]);

    $project->update($data);
    return response()->json(['message' => 'تم تحديث المشروع بنجاح.', 'data' => $project]);
}

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'تم حذف المشروع بنجاح.']);
    }

    public function summary(Project $project): JsonResponse
    {
        $project->load('phases.materials', 'phases.labor', 'phases.equipment');

        $phases = $project->phases->map(function ($phase) {
            $materials = (float) $phase->materials->sum(fn ($m) => $m->qty_estimated * $m->unit_price);
            $labor = (float) $phase->labor->sum('total_cost');
            $equipment = (float) $phase->equipment->sum('total_cost');

            return [
                'phase_id' => $phase->id,
                'phase_name' => $phase->name,
                'materials_total' => round($materials, 2),
                'labor_total' => round($labor, 2),
                'equipment_total' => round($equipment, 2),
                'phase_total' => round($materials + $labor + $equipment, 2),
            ];
        });

        return response()->json([
            'message' => 'تم احتساب ملخص المشروع بنجاح.',
            'data' => [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'phases' => $phases,
                'total' => round($phases->sum('phase_total'), 2),
            ],
        ]);
    }
}
