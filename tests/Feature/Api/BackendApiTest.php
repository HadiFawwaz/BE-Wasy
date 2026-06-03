<?php

use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

function makeAdminUser(): User
{
    return User::factory()->create([
        'role' => 'admin',
    ]);
}

function makeCustomerProfile(): array
{
    $user = User::factory()->create([
        'role' => 'customer',
        'email' => fake()->unique()->safeEmail(),
    ]);

    $customer = Customer::create([
        'user_id' => $user->id,
        'phone' => '081234567890',
        'address' => 'Jl. Mawar 123',
    ]);

    return [$user, $customer];
}

it('can perform full service crud from admin api', function () {
    $admin = makeAdminUser();
    Sanctum::actingAs($admin);

    $createResponse = $this->postJson('/api/services', [
        'service_name' => 'kiloan',
        'price' => 9000,
        'unit' => 'Kg',
    ]);

    $createResponse
        ->assertCreated()
        ->assertJsonPath('message', 'Layanan berhasil ditambahkan');

    $serviceId = $createResponse->json('data.id');

    $this->getJson("/api/services/{$serviceId}")
        ->assertOk()
        ->assertJsonPath('data.service_name', 'kiloan');

    $this->putJson("/api/services/{$serviceId}", [
        'service_name' => 'satuan',
        'price' => 12000,
        'unit' => 'Pcs',
    ])
        ->assertOk()
        ->assertJsonPath('data.service_name', 'satuan');

    $this->deleteJson("/api/services/{$serviceId}")
        ->assertOk()
        ->assertJsonPath('message', 'Layanan berhasil dihapus');
});

it('supports api login profile and logout flow', function () {
    $admin = User::factory()->create([
        'email' => 'admin-api@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $loginResponse = $this->postJson('/api/login', [
        'email' => 'admin-api@example.com',
        'password' => 'password',
    ]);

    $loginResponse
        ->assertOk()
        ->assertJsonPath('message', 'Login berhasil');

    $token = $loginResponse->json('access_token');

    $this->getJson('/api/profile', [
        'Authorization' => "Bearer {$token}",
    ])
        ->assertOk()
        ->assertJsonPath('user.id', $admin->id);

    $this->postJson('/api/logout', [], [
        'Authorization' => "Bearer {$token}",
    ])
        ->assertOk()
        ->assertJsonPath('message', 'Logout berhasil');
});

it('allows customer to update their own profile from api', function () {
    [$user, $customer] = makeCustomerProfile();
    Sanctum::actingAs($user);

    $this->putJson('/api/profile', [
        'name' => 'Hadi Update',
        'email' => 'hadi-update@example.com',
        'password' => 'newpassword',
        'phone' => '082210420548',
        'address' => 'Jl. Wasy Baru No. 10',
    ])
        ->assertOk()
        ->assertJsonPath('message', 'Profil berhasil diperbarui.')
        ->assertJsonPath('user.name', 'Hadi Update')
        ->assertJsonPath('user.email', 'hadi-update@example.com')
        ->assertJsonPath('user.customer.phone', '082210420548')
        ->assertJsonPath('user.customer.address', 'Jl. Wasy Baru No. 10');

    expect($user->fresh()->email)->toBe('hadi-update@example.com')
        ->and($customer->fresh()->phone)->toBe('082210420548')
        ->and(Hash::check('newpassword', $user->fresh()->password))->toBeTrue();
});

it('can create update and delete customer from admin api', function () {
    $admin = makeAdminUser();
    Sanctum::actingAs($admin);

    $createResponse = $this->postJson('/api/customers', [
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'password' => 'password',
        'phone' => '081298765432',
        'address' => 'Jl. Melati 1',
    ]);

    $createResponse
        ->assertCreated()
        ->assertJsonPath('message', 'Pelanggan berhasil ditambahkan');

    $customerId = $createResponse->json('data.id');

    $this->getJson('/api/customers?search=Budi')
        ->assertOk()
        ->assertJsonPath('data.0.user.name', 'Budi');

    $this->putJson("/api/customers/{$customerId}", [
        'name' => 'Budi Update',
        'email' => 'budi-update@example.com',
        'phone' => '081200000001',
        'address' => 'Jl. Melati 2',
    ])
        ->assertOk()
        ->assertJsonPath('data.user.name', 'Budi Update');

    $this->deleteJson("/api/customers/{$customerId}")
        ->assertOk()
        ->assertJsonPath('message', 'Pelanggan berhasil dihapus');
});

it('supports transaction filters and report statistics', function () {
    $admin = makeAdminUser();
    [$customerUser, $customer] = makeCustomerProfile();
    $service = Service::create([
        'service_name' => 'kiloan',
        'price' => 7000,
        'unit' => 'Kg',
    ]);
    $pcsService = Service::create([
        'service_name' => 'bed cover',
        'price' => 18000,
        'unit' => 'Pcs',
    ]);

    Sanctum::actingAs($admin);

    Storage::fake('public');

    $this->postJson('/api/transactions', [
        'customer_id' => $customer->id,
        'service_id' => $pcsService->id,
        'weight' => 1.5,
        'payment_method' => 'cash',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('weight');

    $createResponse = $this->postJson('/api/transactions', [
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'weight' => 2.5,
        'payment_method' => 'cash',
        'condition_photo' => UploadedFile::fake()->image('condition.jpg'),
    ]);

    $createResponse
        ->assertCreated()
        ->assertJsonPath('data.weight', '2.50')
        ->assertJsonPath('data.total_price', '17500.00');

    $createdTransaction = Transaction::findOrFail($createResponse->json('data.id'));

    expect($createdTransaction->weight)->toBe('2.50')
        ->and($createdTransaction->condition_photo)->not->toBeNull()
        ->and($createResponse->json('data.condition_photo_url'))->toStartWith('/storage/conditions/');

    Storage::disk('public')->assertExists($createdTransaction->condition_photo);

    $conditionUpdateResponse = $this->postJson("/api/transactions/{$createdTransaction->id}/condition-photo", [
        'condition_photo' => UploadedFile::fake()->image('condition-updated.jpg'),
    ]);

    $conditionUpdateResponse
        ->assertOk()
        ->assertJsonPath('message', 'Foto kondisi baju berhasil diperbarui.');

    $createdTransaction->refresh();

    expect($createdTransaction->condition_photo)->not->toBeNull()
        ->and($conditionUpdateResponse->json('data.condition_photo_url'))->toStartWith('/storage/conditions/');

    Storage::disk('public')->assertExists($createdTransaction->condition_photo);

    Transaction::create([
        'invoice_code' => 'LND-INV-001',
        'admin_id' => $admin->id,
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'weight' => 2,
        'total_price' => 14000,
        'status' => 'antrian',
        'payment_method' => 'cash',
        'payment_status' => 'pending',
    ]);

    Transaction::create([
        'invoice_code' => 'LND-INV-002',
        'admin_id' => $admin->id,
        'customer_id' => $customer->id,
        'service_id' => $service->id,
        'weight' => 3,
        'total_price' => 21000,
        'status' => 'dicuci',
        'payment_method' => 'transfer',
        'payment_status' => 'paid',
        'paid_at' => now(),
    ]);

    $this->getJson('/api/transactions?status=dicuci&search=LND-INV-002&per_page=5')
        ->assertOk()
        ->assertJsonPath('data.0.invoice_code', 'LND-INV-002')
        ->assertJsonPath('data.0.weight', '3.00')
        ->assertJsonPath('data.0.status', 'dicuci');

    $this->getJson('/api/reports/stats?month=' . now()->month . '&year=' . now()->year)
        ->assertOk()
        ->assertJsonStructure([
            'summary' => ['total_income', 'transactions_today', 'total_transactions'],
            'filters' => ['month', 'year'],
            'transactions_by_day',
            'transactions_by_month',
        ]);

    Sanctum::actingAs($customerUser);

    $this->getJson('/api/status-laundry?status=dicuci')
        ->assertOk()
        ->assertJsonPath('data.0.invoice_code', 'LND-INV-002');
});
