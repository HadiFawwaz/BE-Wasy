<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('weight', 8, 2)->nullable()->after('service_id');
            $table->string('condition_photo', 255)->nullable()->after('payment_proof');
        });

        DB::table('transactions')
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->whereNull('transactions.weight')
            ->where('services.price', '>', 0)
            ->get(['transactions.id', 'transactions.total_price', 'services.price'])
            ->each(function (object $transaction): void {
                $weight = round($transaction->total_price / $transaction->price, 2);

                DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->update([
                        'weight' => $weight,
                    ]);
            });

        DB::table('transactions')
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->whereRaw('LOWER(services.unit) = ?', ['pcs'])
            ->get(['transactions.id', 'transactions.weight'])
            ->each(function (object $transaction): void {
                DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->update([
                        'weight' => max(1, round($transaction->weight)),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['weight', 'condition_photo']);
        });
    }
};
