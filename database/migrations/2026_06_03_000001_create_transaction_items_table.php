<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services');
            $table->decimal('quantity', 8, 2);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index(['transaction_id', 'service_id']);
        });

        DB::table('transactions')
            ->join('services', 'transactions.service_id', '=', 'services.id')
            ->select([
                'transactions.id as transaction_id',
                'transactions.service_id',
                'transactions.weight',
                'transactions.total_price',
                'services.price as service_price',
                'transactions.created_at',
                'transactions.updated_at',
            ])
            ->orderBy('transactions.id')
            ->chunk(500, function ($transactions): void {
                $now = now();
                $rows = $transactions->map(fn ($transaction) => [
                    'transaction_id' => $transaction->transaction_id,
                    'service_id' => $transaction->service_id,
                    'quantity' => $transaction->weight ?? 1,
                    'unit_price' => $transaction->service_price,
                    'subtotal' => $transaction->total_price,
                    'created_at' => $transaction->created_at ?? $now,
                    'updated_at' => $transaction->updated_at ?? $now,
                ])->all();

                if ($rows) {
                    DB::table('transaction_items')->insert($rows);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
