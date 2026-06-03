<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['created_at', 'id'], 'transactions_created_at_id_index');
            $table->index(['status', 'payment_status', 'created_at'], 'transactions_status_payment_created_index');
            $table->index(['customer_id', 'created_at'], 'transactions_customer_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('transactions_created_at_id_index');
            $table->dropIndex('transactions_status_payment_created_index');
            $table->dropIndex('transactions_customer_created_index');
        });
    }
};
