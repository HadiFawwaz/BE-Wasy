<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $services = collect([
            ['service_name' => 'Cuci Kering', 'price' => 7000, 'unit' => 'Kg'],
            ['service_name' => 'Cuci Setrika', 'price' => 10000, 'unit' => 'Kg'],
            ['service_name' => 'Setrika Saja', 'price' => 5000, 'unit' => 'Kg'],
            ['service_name' => 'Bed Cover', 'price' => 18000, 'unit' => 'Pcs'],
        ])->map(fn (array $payload) => Service::create($payload))->values();

        $customers = collect([
            ['name' => 'Alya Putri', 'email' => 'alya@example.com', 'phone' => '081234567801', 'address' => 'Jl. Melati No. 12'],
            ['name' => 'Bima Saputra', 'email' => 'bima@example.com', 'phone' => '081234567802', 'address' => 'Jl. Kenanga No. 7'],
            ['name' => 'Citra Lestari', 'email' => 'citra@example.com', 'phone' => '081234567803', 'address' => 'Jl. Mawar No. 4'],
            ['name' => 'Doni Pratama', 'email' => 'doni@example.com', 'phone' => '081234567804', 'address' => 'Jl. Anggrek No. 9'],
            ['name' => 'Eka Ramadhan', 'email' => 'eka@example.com', 'phone' => '081234567805', 'address' => 'Jl. Cempaka No. 18'],
            ['name' => 'Hadi Fawwaz Sudewo', 'email' => 'hadisudewo@gmail.com', 'phone' => '082210420548', 'address' => 'Alamat Hadi Fawwaz Sudewo'],
        ])->map(function (array $payload) {
            $user = User::create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]);

            return Customer::create([
                'user_id' => $user->id,
                'phone' => $payload['phone'],
                'address' => $payload['address'],
            ]);
        })->values();

        $statusSequence = ['antrian', 'dicuci', 'disetrika', 'siap diambil', 'diambil'];
        $invoiceNumber = 1;
        $dailyTransactionCounts = [2, 4, 1, 5, 3, 0, 6, 2, 4, 3, 5, 1, 4, 6];

        foreach ($dailyTransactionCounts as $dayOffset => $count) {
            $date = Carbon::today()->subDays(13 - $dayOffset);

            for ($index = 0; $index < $count; $index++) {
                $service = $services[($dayOffset + $index) % $services->count()];
                $customer = $customers[($dayOffset + $index) % $customers->count()];
                $quantity = $service->unit === 'Pcs'
                    ? (($index % 2) + 1)
                    : (1.5 + (($dayOffset + $index) % 5) * 0.5);
                $paymentStatus = ($dayOffset + $index) % 3 === 0 ? 'pending' : 'paid';
                $createdAt = $date->copy()->setTime(8 + ($index % 9), ($index * 11) % 60);

                Transaction::create([
                    'invoice_code' => 'LND-' . $createdAt->format('Ymd') . '-' . str_pad((string) $invoiceNumber, 4, '0', STR_PAD_LEFT),
                    'admin_id' => $admin->id,
                    'customer_id' => $customer->id,
                    'service_id' => $service->id,
                    'weight' => $quantity,
                    'total_price' => $service->price * $quantity,
                    'status' => $statusSequence[($dayOffset + $index) % count($statusSequence)],
                    'payment_method' => ($dayOffset + $index) % 2 === 0 ? 'cash' : 'transfer',
                    'payment_status' => $paymentStatus,
                    'payment_proof' => null,
                    'condition_photo' => null,
                    'paid_at' => $paymentStatus === 'paid' ? $createdAt->copy()->addMinutes(12) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $invoiceNumber++;
            }
        }

        $hadiCustomer = $customers->first(
            fn (Customer $customer) => $customer->user->email === 'hadisudewo@gmail.com'
        );

        $hadiTransactions = [
            ['days_ago' => 0, 'service' => 0, 'quantity' => 3.5, 'status' => 'antrian', 'payment_method' => 'cash', 'payment_status' => 'pending'],
            ['days_ago' => 0, 'service' => 1, 'quantity' => 2.0, 'status' => 'dicuci', 'payment_method' => 'transfer', 'payment_status' => 'paid'],
            ['days_ago' => 1, 'service' => 2, 'quantity' => 4.0, 'status' => 'disetrika', 'payment_method' => 'cash', 'payment_status' => 'paid'],
            ['days_ago' => 1, 'service' => 3, 'quantity' => 1.0, 'status' => 'siap diambil', 'payment_method' => 'transfer', 'payment_status' => 'paid'],
            ['days_ago' => 2, 'service' => 0, 'quantity' => 2.5, 'status' => 'siap diambil', 'payment_method' => 'cash', 'payment_status' => 'pending'],
            ['days_ago' => 3, 'service' => 1, 'quantity' => 5.0, 'status' => 'diambil', 'payment_method' => 'transfer', 'payment_status' => 'paid'],
            ['days_ago' => 4, 'service' => 3, 'quantity' => 2.0, 'status' => 'diambil', 'payment_method' => 'cash', 'payment_status' => 'paid'],
            ['days_ago' => 5, 'service' => 2, 'quantity' => 1.5, 'status' => 'dicuci', 'payment_method' => 'transfer', 'payment_status' => 'pending'],
        ];

        foreach ($hadiTransactions as $index => $payload) {
            $service = $services[$payload['service']];
            $createdAt = Carbon::today()
                ->subDays($payload['days_ago'])
                ->setTime(9 + ($index % 8), ($index * 7) % 60);

            Transaction::create([
                'invoice_code' => 'LND-HADI-' . $createdAt->format('Ymd') . '-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'admin_id' => $admin->id,
                'customer_id' => $hadiCustomer->id,
                'service_id' => $service->id,
                'weight' => $payload['quantity'],
                'total_price' => $service->price * $payload['quantity'],
                'status' => $payload['status'],
                'payment_method' => $payload['payment_method'],
                'payment_status' => $payload['payment_status'],
                'payment_proof' => null,
                'condition_photo' => null,
                'paid_at' => $payload['payment_status'] === 'paid' ? $createdAt->copy()->addMinutes(15) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->call(ReportDemoSeeder::class);
    }
}
