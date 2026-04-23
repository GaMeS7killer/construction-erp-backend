<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $clients = Client::query()
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->latest()
            ->paginate(15);

        return response()->json($clients);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = Client::query()->create($request->validated());
        return response()->json(['message' => 'تم إنشاء العميل بنجاح.', 'data' => $client], 201);
    }

    public function show(Client $client): JsonResponse
    {
        return response()->json(['data' => $client]);
    }

    public function update(ClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());
        return response()->json(['message' => 'تم تحديث بيانات العميل.', 'data' => $client]);
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();
        return response()->json(['message' => 'تم حذف العميل بنجاح.']);
    }
}
