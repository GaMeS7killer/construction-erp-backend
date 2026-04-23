<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaborTypeRequest;
use App\Models\LaborType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaborTypeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = LaborType::query()
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('pay_type'), fn ($q) => $q->where('pay_type', $request->string('pay_type')))
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }

    public function store(LaborTypeRequest $request): JsonResponse
    {
        $item = LaborType::query()->create($request->validated());
        return response()->json(['message' => 'تم إضافة نوع العمالة بنجاح.', 'data' => $item], 201);
    }

    public function show(LaborType $laborType): JsonResponse
    {
        return response()->json(['data' => $laborType]);
    }

    public function update(LaborTypeRequest $request, LaborType $laborType): JsonResponse
    {
        $laborType->update($request->validated());
        return response()->json(['message' => 'تم تحديث نوع العمالة.', 'data' => $laborType]);
    }

    public function destroy(LaborType $laborType): JsonResponse
    {
        $laborType->delete();
        return response()->json(['message' => 'تم حذف نوع العمالة.']);
    }
}
