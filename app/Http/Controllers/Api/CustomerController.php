<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $request->merge([
            'search' => is_string($request->input('search')) ? trim($request->input('search')) : $request->input('search'),
        ]);

        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $search = $validated['search'] ?? null;
        $perPage = $validated['per_page'] ?? 10;

        $customers = Customer::query()
            ->with('user')
            ->when($search, function ($query, $keyword) {
                $query->where(function ($nestedQuery) use ($keyword) {
                    $nestedQuery->where('phone', 'like', "%{$keyword}%")
                        ->orWhereHas('user', function ($userQuery) use ($keyword) {
                            $userQuery->where('name', 'like', "%{$keyword}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $validated = $request->validated();

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'customer',
                ]);

                $customer = Customer::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                ]);

                return response()->json([
                    'message' => 'Pelanggan berhasil ditambahkan',
                    'data' => $customer->load('user'),
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan pelanggan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'data' => $customer->load('user'),
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            DB::transaction(function () use ($request, $customer) {
                $validated = $request->validated();

                $userPayload = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ];

                if (!empty($validated['password'])) {
                    $userPayload['password'] = Hash::make($validated['password']);
                }

                $customer->user->update($userPayload);

                $customer->update([
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                ]);
            });

            return response()->json([
                'message' => 'Data pelanggan berhasil diubah',
                'data' => $customer->fresh()->load('user'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah data pelanggan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Customer $customer)
    {
        if ($customer->transactions()->exists()) {
            return response()->json([
                'message' => 'Pelanggan tidak bisa dihapus karena masih memiliki transaksi.',
            ], 422);
        }

        $customer->user->delete();

        return response()->json([
            'message' => 'Pelanggan berhasil dihapus',
        ]);
    }
}
