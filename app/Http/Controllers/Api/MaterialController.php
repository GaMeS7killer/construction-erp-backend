<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialRequest;
use App\Models\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = Material::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->when($request->filled('unit'), fn ($q) => $q->where('unit', $request->string('unit')))
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }

    public function store(MaterialRequest $request): JsonResponse
    {
        $item = Material::query()->create($request->validated());
        return response()->json(['message' => 'تم إضافة المادة بنجاح.', 'data' => $item], 201);
    }

    public function show(Material $material): JsonResponse
    {
        return response()->json(['data' => $material]);
    }

    public function update(MaterialRequest $request, Material $material): JsonResponse
    {
        $material->update($request->validated());
        return response()->json(['message' => 'تم تحديث المادة بنجاح.', 'data' => $material]);
    }

    public function destroy(Material $material): JsonResponse
    {
        $material->delete();
        return response()->json(['message' => 'تم حذف المادة بنجاح.']);
    }
}
