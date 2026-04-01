# Product Requirements Document (PRD)
**Nama Produk:** Sistem Manajemen Jimpitan Lebaran (SiJimbar - *Opsional*)
**Platform:** Aplikasi Web (Responsive)
**Tech Stack Utama:** Laravel (Backend/Framework), MySQL/PostgreSQL (Database), Tailwind CSS + Alpine.js/Livewire (Frontend)

## 1. Latar Belakang & Tujuan
**Masalah:** Pencatatan jimpitan lebaran (sembako, snack, minuman) saat ini dilakukan secara manual menggunakan buku. Hal ini rentan terhadap kesalahan perhitungan, kehilangan data, dan menyulitkan proses rekapitulasi di akhir periode (terutama untuk 45 kali angsuran).

**Tujuan:**
* Mendigitalkan proses pencatatan master katalog barang dan peserta.
* Mengotomatiskan perhitungan total angsuran per minggu dan total nilai jimpitan.
* Mempermudah *tracking* (pelacakan) pembayaran angsuran setiap peserta per minggunya.
* Menghasilkan laporan rekapitulasi barang (kulakan) secara instan di akhir periode.

## 2. Pengguna Sasaran (User Persona)
Saat ini, aplikasi dirancang untuk *Single Role*:
* **Admin / Pengurus (Misal: Mbk Izzah):** Bertanggung jawab mendaftarkan barang, mendata warga, dan mencatat setoran uang setiap minggunya. Membutuhkan antarmuka yang cepat untuk input data massal.

## 3. Fitur Utama (Minimum Viable Product - MVP)

### 3.1. Modul Autentikasi
* **Login:** Akses aman menggunakan Email/Username dan Password.
* **Logout & Sesi:** Manajemen sesi standar Laravel.

### 3.2. Modul Master Katalog (Barang & Paket)
* **CRUD Kategori Barang:** Admin dapat mengelola kategori (Sembako, Snack, Minuman, Paket Spesial).
* **CRUD Barang Reguler:**
    * Input Nama Barang.
    * Input Satuan/Kuantitas (Kg, Dos, Blek, dll).
    * Input Harga Angsuran per Minggu.
* **CRUD Paket Spesial:**
    * Fitur untuk membuat 'Paket' yang merelasikan beberapa barang reguler sekaligus (Misal: Paket 1 berisi Beras, Bimoli, dll).
    * Input field khusus untuk 'Bonus' paket (Misal: Handuk Tebal, Panci).

### 3.3. Modul Manajemen Peserta
* **CRUD Peserta:** Mendaftarkan warga yang mengikuti jimpitan.
    * Data wajib: Nama Lengkap, Nomor HP.
    * Data opsional: Alamat (RT/RW), Catatan.

### 3.4. Modul Pendaftaran Keranjang Jimpitan (Order)
* **Pemetaan Peserta ke Katalog:** Admin memilih peserta, lalu menambahkan barang atau paket apa saja yang ingin mereka ikuti pada periode tersebut.
* **Perhitungan Otomatis:** Sistem akan menampilkan estimasi total yang harus dibayar peserta tersebut setiap minggunya.

### 3.5. Modul Pelacakan Angsuran (Buku Kas Digital)
* **Tampilan Grid Pembayaran:** Antarmuka berupa tabel (mirip Excel) yang menampilkan daftar peserta beserta keranjang belanja mereka. Terdapat kolom untuk Minggu 1 hingga Minggu 45.
* **Fitur Input Setoran:** Admin dapat memberikan tanda centang (atau input nominal jika bayar lebih) pada kolom minggu tertentu jika warga menyetor uang.
* **Status Lunas/Tunggakan:** Sistem otomatis menghitung berapa kali warga sudah membayar dan berapa sisa angsurannya.

### 3.6. Modul Laporan & Rekapitulasi
* **Rekap Keuangan:** Laporan total uang yang masuk per minggu dan total keseluruhan.
* **Rekap Belanja (Kulakan):** Laporan otomatis yang menjumlahkan kuantitas setiap barang yang dipesan oleh seluruh peserta (Misal: Total Beras 64 yang harus dibeli = 1.000 Kg). Dapat diekspor ke PDF/Excel.

---

## 4. Struktur Database (Schema Concept)

Berikut bayangan *Migration* dan relasinya dalam Laravel:

* `users` (Standar otentikasi admin)
* `categories`
    * `id`, `name` (Sembako, Snack, dll)
* `items`
    * `id`, `category_id` (FK), `name`, `unit` (Kg, Dos), `weekly_price`, `type` (regular / package), `bonus_desc` (nullable)
* `item_package` (Pivot table untuk mendefinisikan isi paket - *Opsional jika paket tidak perlu direlasikan ke stok barang individual*)
    * `package_id` (FK items), `item_id` (FK items)
* `participants`
    * `id`, `name`, `phone`, `address`
* `orders` (Keranjang jimpitan warga per periode)
    * `id`, `participant_id` (FK), `period_name` (Misal: 2026-2027)
* `order_items` (Detail barang yang diambil warga)
    * `id`, `order_id` (FK), `item_id` (FK), `qty`
* `installments` (Catatan pembayaran)
    * `id`, `order_id` (FK), `week_number` (1-45), `amount_paid`, `payment_date`

## 5. Timeline Pengembangan (Estimasi Kasar untuk 1 Developer)

* **Minggu 1:** Setup Laravel, Pembuatan Database Migrations, Models, dan Relasi (Eloquent). Pembuatan Auth dan CRUD Kategori & Peserta.
* **Minggu 2:** Pembuatan CRUD Katalog Barang & Paket. Pembuatan fitur Pendaftaran Keranjang (Order).
* **Minggu 3:** Pengembangan Antarmuka *Grid* Pelacakan Angsuran (Bagian paling kompleks, disarankan menggunakan Livewire untuk interaktivitas tanpa *reload* halaman).
* **Minggu 4:** Pembuatan Laporan Rekapitulasi (Keuangan & Kulakan), ekspor ke PDF, *Testing*, dan *Bug Fixing*.
