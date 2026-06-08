## Laporan

Endpoint laporan digunakan untuk mengambil data statistik transaksi dan pendapatan.

### Query Laporan

```txt
GET /api/reports/stats?month=6&year=2026&date=2026-06-02
```

Parameter:

| Parameter | Keterangan |
| --- | --- |
| `month` | Bulan laporan, contoh `6` untuk Juni |
| `year` | Tahun laporan, contoh `2026` |
| `date` | Tanggal aktif untuk mode harian |

Response laporan berisi:

| Field | Keterangan |
| --- | --- |
| `summary` | Ringkasan global transaksi |
| `period_summary` | Ringkasan berdasarkan bulan dan tahun |
| `selected_day_summary` | Ringkasan tanggal yang dipilih |
| `transactions_by_week` | Data transaksi 14 hari terakhir |
| `transactions_by_day` | Data transaksi per hari dalam bulan |
| `transactions_by_hour` | Data transaksi per jam pada tanggal aktif |
| `transactions_by_month` | Data transaksi per bulan dalam tahun |

## Database

Tabel utama:

| Tabel | Fungsi |
| --- | --- |
| `users` | Menyimpan akun admin dan customer |
| `customers` | Menyimpan data profil customer |
| `services` | Menyimpan layanan laundry |
| `transactions` | Menyimpan transaksi laundry |
| `transaction_items` | Menyimpan item layanan dalam transaksi |
| `personal_access_tokens` | Menyimpan token autentikasi Sanctum |

Relasi utama:

- User dengan role `customer` memiliki satu customer profile.
- Customer dapat memiliki banyak transaksi.
- Service dapat digunakan di banyak transaksi.
- Transaction memiliki admin, customer, dan service utama.
- Transaction dapat memiliki banyak transaction items.

## Setup Project

Jalankan perintah berikut:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=ReportDemoSeeder
php artisan storage:link
php artisan serve
```

Jika ingin menjalankan setup default dari Composer:

```bash
composer run setup
```

## Akun Demo

Seeder membuat akun admin:

| Email | Password | Role |
| --- | --- | --- |
| `admin@laundry.com` | `password` | `admin` |

Seeder juga membuat beberapa akun customer demo dengan password:

```txt
password
```


## File Upload

Upload disimpan di storage public.

Jenis upload:

- Bukti pembayaran transfer
- Foto kondisi baju

Agar file bisa diakses dari browser, jalankan:

```bash
php artisan storage:link
```

File dapat diakses melalui path:

```txt
/storage/...
```


## Status Data

Status cucian:

- `antrian`
- `dicuci`
- `disetrika`
- `siap diambil`
- `diambil`

Status pembayaran:

- `pending`
- `paid`

Metode pembayaran:

- `cash`
- `transfer`

## Summary

BE Wasy adalah sistem backend dan web admin untuk operasional laundry. Sistem ini membantu admin mengelola layanan, pelanggan, transaksi, status cucian, pembayaran, upload bukti pembayaran, upload foto kondisi baju, serta laporan pendapatan. Customer dapat login dan mengecek status cucian miliknya sendiri. Aplikasi ini sudah menggunakan Laravel Sanctum, role-based access, validasi request, seed data demo, dan testing menggunakan Pest PHP.
