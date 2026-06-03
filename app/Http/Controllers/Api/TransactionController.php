<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionConditionPhotoRequest;
use App\Http\Requests\UpdateTransactionPaymentRequest;
use App\Http\Requests\UpdateTransactionStatusRequest;
use App\Models\Service;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    // List transaksi admin dengan filter dan pagination.
    public function index(Request $request)
    {
        $request->merge([
            'search' => is_string($request->input('search')) ? trim($request->input('search')) : $request->input('search'),
        ]);

        $validated = $request->validate([
            'status' => 'nullable|in:antrian,dicuci,disetrika,siap diambil,diambil',
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'nullable|in:cash,transfer',
            'payment_status' => 'nullable|in:pending,paid',
            'search' => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'per_page' => 'nullable|integer|min:1|max:100',
            'exclude_completed' => 'nullable|boolean',
        ]);

        $perPage = $validated['per_page'] ?? 10;

        $transactions = Transaction::query()
            ->select([
                'id',
                'invoice_code',
                'admin_id',
                'customer_id',
                'service_id',
                'weight',
                'total_price',
                'status',
                'payment_method',
                'payment_status',
                'payment_reason',
                'payment_proof',
                'condition_photo',
                'paid_at',
                'created_at',
                'updated_at',
            ])
            ->with([
                'customer:id,user_id,phone,address',
                'customer.user:id,name,email',
                'service:id,service_name,price,unit',
                'items:id,transaction_id,service_id,quantity,unit_price,subtotal',
                'items.service:id,service_name,price,unit',
                'admin:id,name,email',
            ])
            ->when($validated['status'] ?? null, fn($query, $status) => $query->where('status', $status))
            ->when($validated['customer_id'] ?? null, fn($query, $customerId) => $query->where('customer_id', $customerId))
            ->when($validated['payment_method'] ?? null, fn($query, $paymentMethod) => $query->where('payment_method', $paymentMethod))
            ->when($validated['payment_status'] ?? null, fn($query, $paymentStatus) => $query->where('payment_status', $paymentStatus))
            ->when($validated['date_from'] ?? null, fn($query, $dateFrom) => $query->where('created_at', '>=', Carbon::parse($dateFrom)->startOfDay()))
            ->when($validated['date_to'] ?? null, fn($query, $dateTo) => $query->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay()))
            ->when($validated['exclude_completed'] ?? null, fn($query) => $query->where(function ($q) {
                $q->whereNot(fn($subQuery) => $subQuery->where('payment_status', 'paid')->where('status', 'diambil'));
            }))
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery->where('invoice_code', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('phone', 'like', "%{$search}%")
                                ->orWhereHas('user', function ($userQuery) use ($search) {
                                    $userQuery->where('name', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($transactions, 200);
    }

    // Ringkasan cepat untuk kartu statistik transaksi.
    public function summary()
    {
        $todayStart = today()->startOfDay();
        $todayEnd = today()->endOfDay();

        $summary = Transaction::query()
            ->selectRaw('COUNT(*) as total_transactions')
            ->selectRaw('SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as transactions_today', [$todayStart, $todayEnd])
            ->selectRaw("SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as unpaid_transactions")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_transactions")
            ->selectRaw("SUM(CASE WHEN status = 'siap diambil' THEN 1 ELSE 0 END) as ready_transactions")
            ->first();

        return response()->json([
            'total_transactions' => (int) ($summary->total_transactions ?? 0),
            'transactions_today' => (int) ($summary->transactions_today ?? 0),
            'unpaid_transactions' => (int) ($summary->unpaid_transactions ?? 0),
            'paid_transactions' => (int) ($summary->paid_transactions ?? 0),
            'ready_transactions' => (int) ($summary->ready_transactions ?? 0),
        ]);
    }

    // Simpan transaksi baru dari form admin.
    public function store(StoreTransactionRequest $request)
    {
        try {
            $validated = $request->validated();
            $rawItems = $validated['items'] ?? [[
                'service_id' => $validated['service_id'],
                'quantity' => $validated['weight'],
            ]];

            $serviceIds = collect($rawItems)->pluck('service_id')->unique()->values();
            $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');
            $items = [];
            $totalPrice = 0;

            foreach ($rawItems as $item) {
                $service = $services->get((int) $item['service_id']);
                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $service->price;
                $subtotal = $unitPrice * $quantity;

                $items[] = [
                    'service_id' => $service->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];

                $totalPrice += $subtotal;
            }

            $primaryItem = $items[0];

            $path = null;
            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('payments', 'public');
            }

            $conditionPhotoPath = null;
            if ($request->hasFile('condition_photo')) {
                $conditionPhotoPath = $request->file('condition_photo')->store('conditions', 'public');
            }

            $transaction = DB::transaction(function () use ($request, $validated, $items, $primaryItem, $totalPrice, $path, $conditionPhotoPath) {
                $transaction = Transaction::create([
                    'invoice_code' => 'LND-' . date('Ymd') . strtoupper(substr(uniqid(), -6)),
                    'admin_id' => $request->user()->id,
                    'customer_id' => $validated['customer_id'],
                    'service_id' => $primaryItem['service_id'],
                    'weight' => $primaryItem['quantity'],
                    'total_price' => $totalPrice,
                    'status' => 'antrian',
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $path ? 'paid' : 'pending',
                    'payment_reason' => $path ? 'Bukti transfer diupload saat transaksi dibuat.' : null,
                    'payment_proof' => $path,
                    'condition_photo' => $conditionPhotoPath,
                    'paid_at' => $path ? now() : null,
                ]);

                $transaction->items()->createMany($items);

                return $transaction;
            });

            return response()->json([
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaction->load('customer.user', 'service', 'admin', 'items.service'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat transaksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Ubah status proses cucian.
    public function updateStatus(UpdateTransactionStatusRequest $request, int $id)
    {
        try {
            $validated = $request->validated();
            $transaction = Transaction::findOrFail($id);
            $transaction->update(['status' => $validated['status']]);

            return response()->json([
                'message' => 'Status transaksi berhasil diubah',
                'data' => $transaction->fresh()->load(['customer.user', 'service', 'admin', 'items.service']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Ubah status pembayaran dan bukti transfer.
    public function updatePayment(UpdateTransactionPaymentRequest $request, int $id)
    {
        try {
            $validated = $request->validated();
            $transaction = Transaction::findOrFail($id);

            $paymentProofPath = $transaction->payment_proof;
            if ($request->hasFile('payment_proof')) {
                if ($transaction->payment_proof) {
                    Storage::disk('public')->delete($transaction->payment_proof);
                }

                $paymentProofPath = $request->file('payment_proof')->store('payments', 'public');
            }

            $removePaymentProof = (bool) ($validated['remove_payment_proof'] ?? false);
            if ($removePaymentProof && $transaction->payment_proof && !$request->hasFile('payment_proof')) {
                Storage::disk('public')->delete($transaction->payment_proof);
                $paymentProofPath = null;
            }

            $paymentStatus = $validated['payment_status'];
            if ($transaction->payment_method === 'transfer' && $paymentProofPath) {
                $paymentStatus = 'paid';
            }

            if (
                $paymentStatus === 'paid'
                && $transaction->payment_method === 'transfer'
                && !$paymentProofPath
            ) {
                return response()->json([
                    'message' => 'Bukti pembayaran transfer wajib diupload sebelum ditandai lunas.',
                    'errors' => [
                        'payment_proof' => ['Bukti pembayaran wajib diupload untuk transaksi transfer.'],
                    ],
                ], 422);
            }

            if (
                $transaction->payment_method === 'transfer'
                && $validated['payment_status'] === 'pending'
                && !$request->hasFile('payment_proof')
                && $paymentProofPath
            ) {
                return response()->json([
                    'message' => 'Hapus bukti transfer dulu sebelum mengubah status ke pending.',
                    'errors' => [
                        'payment_proof' => ['Bukti transfer masih tersimpan. Hapus bukti jika pembayaran memang belum valid.'],
                    ],
                ], 422);
            }

            $transaction->update([
                'payment_status' => $paymentStatus,
                'payment_reason' => $validated['payment_reason'],
                'payment_proof' => $paymentProofPath,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
            ]);

            return response()->json([
                'message' => $paymentStatus === 'paid'
                    ? 'Transaksi berhasil ditandai lunas.'
                    : 'Status pembayaran berhasil diubah ke pending.',
                'data' => $transaction->fresh()->load(['customer.user', 'service', 'admin', 'items.service']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate status pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Perbarui foto kondisi baju pada transaksi lama.
    public function updateConditionPhoto(UpdateTransactionConditionPhotoRequest $request, int $id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->condition_photo) {
                Storage::disk('public')->delete($transaction->condition_photo);
            }

            $transaction->update([
                'condition_photo' => $request->file('condition_photo')->store('conditions', 'public'),
            ]);

            return response()->json([
                'message' => 'Foto kondisi baju berhasil diperbarui.',
                'data' => $transaction->fresh()->load(['customer.user', 'service', 'admin', 'items.service']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui foto kondisi baju.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Tampilkan status cucian untuk akun customer.
    public function statusLaundry(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'customer') {
            return response()->json(['message' => 'Unauthorized. Customer only.'], 403);
        }

        if (!$user->customer) {
            return response()->json([
                'message' => 'Profile pelanggan tidak ditemukan',
                'data' => []
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'nullable|in:antrian,dicuci,disetrika,siap diambil,diambil',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $validated['per_page'] ?? 10;

        $transactions = Transaction::query()
            ->with(['service', 'items.service', 'admin'])
            ->where('customer_id', $user->customer->id)
            ->when($validated['status'] ?? null, fn($query, $status) => $query->where('status', $status))
            ->when($validated['date_from'] ?? null, fn($query, $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($validated['date_to'] ?? null, fn($query, $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($transactions, 200);
    }

    // Hapus transaksi beserta file bukti dan foto kondisinya.
    public function destroy(int $id)
    {
        try {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }

            if ($transaction->condition_photo) {
                Storage::disk('public')->delete($transaction->condition_photo);
            }

            $transaction->delete();

            return response()->json([
                'message' => 'Transaksi berhasil dihapus.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}



