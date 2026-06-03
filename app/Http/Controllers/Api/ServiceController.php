<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $validated['per_page'] ?? 10;

        $services = Service::query()
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($services);
    }

    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());

        return response()->json([
            'message' => 'Layanan berhasil ditambahkan',
            'data' => $service,
        ], 201);
    }

    public function show(Service $service)
    {
        return response()->json(['data' => $service]);
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return response()->json([
            'message' => 'Layanan berhasil diubah',
            'data' => $service,
        ]);
    }

    public function destroy(Service $service)
    {
        if ($service->transactions()->exists()) {
            return response()->json([
                'message' => 'Layanan tidak bisa dihapus karena sudah dipakai transaksi.',
            ], 422);
        }

        $service->delete();

        return response()->json([
            'message' => 'Layanan berhasil dihapus',
        ]);
    }
}
