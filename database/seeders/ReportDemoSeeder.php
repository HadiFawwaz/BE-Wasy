<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReportDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Admin Laundry',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $services = collect([
            ['service_name' => 'Cuci Kering', 'price' => 7000, 'unit' => 'Kg'],
            ['service_name' => 'Cuci Setrika', 'price' => 10000, 'unit' => 'Kg'],
            ['service_name' => 'Setrika Saja', 'price' => 5000, 'unit' => 'Kg'],
            ['service_name' => 'Bed Cover', 'price' => 18000, 'unit' => 'Pcs'],
        ])->map(
            fn (array $payload) => Service::updateOrCreate(
                ['service_name' => $payload['service_name']],
                ['price' => $payload['price'], 'unit' => $payload['unit']]
            )
        )->values();

        $customers = collect([
            ['name' => 'Alya Putri', 'email' => 'alya@example.com', 'phone' => '081234567801', 'address' => 'Jl. Melati No. 12'],
            ['name' => 'Bima Saputra', 'email' => 'bima@example.com', 'phone' => '081234567802', 'address' => 'Jl. Kenanga No. 7'],
            ['name' => 'Citra Lestari', 'email' => 'citra@example.com', 'phone' => '081234567803', 'address' => 'Jl. Mawar No. 4'],
            ['name' => 'Doni Pratama', 'email' => 'doni@example.com', 'phone' => '081234567804', 'address' => 'Jl. Anggrek No. 9'],
            ['name' => 'Eka Ramadhan', 'email' => 'eka@example.com', 'phone' => '081234567805', 'address' => 'Jl. Cempaka No. 18'],
            ['name' => 'Hadi Fawwaz Sudewo', 'email' => 'hadisudewo@gmail.com', 'phone' => '082210420548', 'address' => 'Alamat Hadi Fawwaz Sudewo'],
        ])->map(function (array $payload) {
            $user = User::firstOrCreate(
                ['email' => $payload['email']],
                [
                    'name' => $payload['name'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                ]
            );

            return Customer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $payload['phone'],
                    'address' => $payload['address'],
                ]
            );
        })->values();

        $statusSequence = ['antrian', 'dicuci', 'disetrika', 'siap diambil', 'diambil'];
        $reportYear = Carbon::today()->year;

        foreach (CarbonPeriod::create(
            Carbon::create($reportYear, 1, 1),
            Carbon::create($reportYear, 12, 31),
        ) as $date) {
            $transactionCount = 2 + (($date->dayOfYear + $date->month) % 3);

            for ($index = 0; $index < $transactionCount; $index++) {
                $service = $services[($date->month + $date->day + $index) % $services->count()];
                $customer = $customers[($date->dayOfYear + $index) % $customers->count()];
                $quantity = $service->unit === 'Pcs'
                    ? (($index % 3) + 1)
                    : (1.5 + (($date->day + $index) % 6) * 0.5);
                $paymentStatus = $index === 0 || ($date->day + $index) % 4 !== 0 ? 'paid' : 'pending';
                $createdAt = $date->copy()->setTime(8 + ($index * 3), ($date->day + $index * 11) % 60);

                $transaction = Transaction::updateOrCreate(
                    [
                        'invoice_code' => 'LND-RPT-' . $createdAt->format('Ymd') . '-' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    ],
                    [
                        'admin_id' => $admin->id,
                        'customer_id' => $customer->id,
                        'service_id' => $service->id,
                        'weight' => $quantity,
                        'total_price' => $service->price * $quantity,
                        'status' => $statusSequence[($date->month + $index) % count($statusSequence)],
                        'payment_method' => ($date->month + $index) % 2 === 0 ? 'cash' : 'transfer',
                        'payment_status' => $paymentStatus,
                        'payment_proof' => null,
                        'condition_photo' => null,
                        'paid_at' => $paymentStatus === 'paid' ? $createdAt->copy()->addMinutes(18) : null,
                    ]
                );

                $transaction->forceFill([
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ])->save();
            }
        }
    }
}
