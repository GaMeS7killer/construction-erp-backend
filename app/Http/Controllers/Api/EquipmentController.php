<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = Equipment::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('unit'), fn ($q) => $q->where('unit', $request->string('unit')))
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }

    public function store(EquipmentRequest $request): JsonResponse
    {
        $item = Equipment::query()->create($request->validated());
        return response()->json(['message' => 'تم إضافة المعدة بنجاح.', 'data' => $item], 201);
    }

    public function show(Equipment $equipment): JsonResponse
    {
        return response()->json(['data' => $equipment]);
    }

    public function update(EquipmentRequest $request, Equipment $equipment): JsonResponse
    {
        $equipment->update($request->validated());
        return response()->json(['message' => 'تم تحديث المعدة بنجاح.', 'data' => $equipment]);
    }

    public function destroy(Equipment $equipment): JsonResponse
    {
        $equipment->delete();
        return response()->json(['message' => 'تم حذف المعدة بنجاح.']);
    }
}
