<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectEquipment;
use App\Models\ProjectLabor;
use App\Models\ProjectMaterial;
use App\Models\ProjectPhase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectPhaseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $phases = ProjectPhase::query()
            ->with('project')
            ->when($request->filled('project_id'), fn ($q) => $q->where('project_id', $request->integer('project_id')))
            ->orderBy('order_num')
            ->paginate(15);

        return response()->json($phases);
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'order_num' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:planning,in_progress,completed,cancelled'],            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ]);

        $phase = ProjectPhase::query()->create([
            ...$data,
            'project_id' => $project->id,
        ]);

        return response()->json(['message' => 'تم إنشاء المرحلة بنجاح.', 'data' => $phase], 201);
    }

    public function show(ProjectPhase $phase): JsonResponse
    {
        $phase->load('project', 'materials.material', 'labor.laborType', 'equipment.equipment');
        return response()->json(['data' => $phase]);
    }

    public function update(Request $request, ProjectPhase $phase): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'order_num' => ['sometimes', 'required', 'integer', 'min:1'],
            'status' => ['sometimes', 'required', 'in:planning,in_progress,completed,cancelled'],            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ]);

        $phase->update($data);
        return response()->json(['message' => 'تم تحديث المرحلة بنجاح.', 'data' => $phase]);
    }

    public function destroy(ProjectPhase $phase): JsonResponse
    {
        $phase->delete();
        return response()->json(['message' => 'تم حذف المرحلة بنجاح.']);
    }

    public function cost(ProjectPhase $phase): JsonResponse
    {
        $phase->load('materials', 'labor', 'equipment');
        $materials = (float) $phase->materials->sum(fn ($m) => $m->qty_estimated * $m->unit_price);
        $labor = (float) $phase->labor->sum('total_cost');
        $equipment = (float) $phase->equipment->sum('total_cost');

        return response()->json([
            'message' => 'تم احتساب تكلفة المرحلة.',
            'data' => [
                'phase_id' => $phase->id,
                'materials_total' => round($materials, 2),
                'labor_total' => round($labor, 2),
                'equipment_total' => round($equipment, 2),
                'total' => round($materials + $labor + $equipment, 2),
            ],
        ]);
    }

    public function storeMaterial(Request $request, ProjectPhase $phase): JsonResponse
    {
        $data = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty_estimated' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $item = ProjectMaterial::query()->create([
            ...$data,
            'phase_id' => $phase->id,
            'qty_actual' => 0,
        ]);

        return response()->json($item->load('material'), 201);
    }

    public function storeLabor(Request $request, ProjectPhase $phase): JsonResponse
    {
        $data = $request->validate([
            'labor_type_id' => 'required|exists:labor_types,id',
            'workers_count' => 'required|integer|min:1',
            'days' => 'required|numeric|min:0',
        ]);

        $item = ProjectLabor::query()->create([
            ...$data,
            'phase_id' => $phase->id,
        ]);

        return response()->json($item->load('laborType'), 201);
    }

    public function storeEquipment(Request $request, ProjectPhase $phase): JsonResponse
    {
        $data = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'qty_used' => 'required|numeric|min:0',
        ]);

        $item = ProjectEquipment::query()->create([
            ...$data,
            'phase_id' => $phase->id,
        ]);

        return response()->json($item->load('equipment'), 201);
    }

    public function destroyMaterial(ProjectPhase $phase, ProjectMaterial $material): JsonResponse
    {
        if ((int) $material->phase_id !== (int) $phase->id) {
            return response()->json(['message' => 'العنصر غير تابع لهذه المرحلة.'], 404);
        }

        $material->delete();
        return response()->json(['message' => 'تم الحذف']);
    }

    public function destroyLabor(ProjectPhase $phase, ProjectLabor $labor): JsonResponse
    {
        if ((int) $labor->phase_id !== (int) $phase->id) {
            return response()->json(['message' => 'العنصر غير تابع لهذه المرحلة.'], 404);
        }

        $labor->delete();
        return response()->json(['message' => 'تم الحذف']);
    }

    public function destroyEquipment(ProjectPhase $phase, ProjectEquipment $equipment): JsonResponse
    {
        if ((int) $equipment->phase_id !== (int) $phase->id) {
            return response()->json(['message' => 'العنصر غير تابع لهذه المرحلة.'], 404);
        }

        $equipment->delete();
        return response()->json(['message' => 'تم الحذف']);
    }
}
