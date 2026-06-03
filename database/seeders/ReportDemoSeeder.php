<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
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
        $monthlyTransactionCounts = [14, 18, 13, 21, 16, 19, 15, 22, 17, 20, 14, 18];
        $reportYear = Carbon::today()->year;
        $invoiceNumber = 1;

        foreach ($monthlyTransactionCounts as $monthIndex => $count) {
            $month = $monthIndex + 1;
            $monthStart = Carbon::create($reportYear, $month, 1);
            $daysInMonth = $monthStart->daysInMonth;

            for ($index = 0; $index < $count; $index++) {
                $service = $services[($month + $index) % $services->count()];
                $customer = $customers[($month + $index) % $customers->count()];
                $quantity = $service->unit === 'Pcs'
                    ? (($index % 3) + 1)
                    : (1.5 + (($month + $index) % 6) * 0.5);
                $paymentStatus = ($month + $index) % 4 === 0 ? 'pending' : 'paid';
                $day = (($index * 2) % $daysInMonth) + 1;
                $createdAt = $monthStart->copy()->day($day)->setTime(8 + ($index % 10), ($index * 13) % 60);

                Transaction::updateOrCreate(
                    [
                        'invoice_code' => 'LND-RPT-' . $createdAt->format('Ym') . '-' . str_pad((string) $invoiceNumber, 4, '0', STR_PAD_LEFT),
                    ],
                    [
                        'admin_id' => $admin->id,
                        'customer_id' => $customer->id,
                        'service_id' => $service->id,
                        'weight' => $quantity,
                        'total_price' => $service->price * $quantity,
                        'status' => $statusSequence[($month + $index) % count($statusSequence)],
                        'payment_method' => ($month + $index) % 2 === 0 ? 'cash' : 'transfer',
                        'payment_status' => $paymentStatus,
                        'payment_proof' => null,
                        'condition_photo' => null,
                        'paid_at' => $paymentStatus === 'paid' ? $createdAt->copy()->addMinutes(18) : null,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );

                $invoiceNumber++;
            }
        }
    }
}
