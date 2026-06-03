<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        DB::table('services')
            ->whereRaw('LOWER(unit) = ?', ['kg'])
            ->update(['unit' => 'Kg']);

        DB::table('services')
            ->whereRaw('LOWER(unit) = ?', ['pcs'])
            ->update(['unit' => 'Pcs']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE services MODIFY unit ENUM('Kg', 'Pcs') NOT NULL");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('services')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE services MODIFY unit VARCHAR(20) NOT NULL');
        }
    }
};
