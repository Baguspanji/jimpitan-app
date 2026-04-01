# Target Pengembangan - SiJimbar

## Minggu 1 — Setup & Foundation

### Setup Project
- [x] Setup Laravel project & konfigurasi environment
- [x] Konfigurasi database (MySQL/PostgreSQL)
- [x] Setup Livewire & Flux UI
- [x] Setup autentikasi (Fortify)

### Database Migrations & Models
- [x] Migration `categories`
- [x] Migration `items`
- [x] Migration `item_package` (pivot)
- [x] Migration `participants`
- [x] Migration `orders`
- [x] Migration `order_items`
- [x] Migration `installments`
- [x] Migration `savings`
- [x] Migration `saving_transactions`
- [x] Model & relasi `Category`
- [x] Model & relasi `Item`
- [x] Model & relasi `Participant`
- [x] Model & relasi `Order`
- [x] Model & relasi `OrderItem`
- [x] Model & relasi `Installment`
- [x] Model & relasi `Saving`
- [x] Model & relasi `SavingTransaction`

### Modul Autentikasi
- [x] Halaman Login
- [x] Logout & manajemen sesi
- [x] Proteksi route (middleware auth)

### Modul Peserta
- [x] Halaman daftar peserta
- [x] Form tambah peserta (Nama, No HP, Alamat, Catatan)
- [x] Form edit peserta
- [x] Fitur hapus peserta
- [x] Validasi data peserta

---

## Minggu 2 — Katalog & Order

### Modul Kategori
- [x] Halaman daftar kategori
- [x] Form tambah & edit kategori
- [x] Fitur hapus kategori

### Modul Barang
- [x] Halaman daftar barang (dengan filter kategori)
- [x] Form tambah barang reguler (Nama, Satuan, Harga/Minggu, Kategori)
- [x] Form tambah paket spesial (relasi ke barang reguler + field bonus)
- [x] Form edit barang/paket
- [x] Fitur hapus barang/paket

### Modul Pendaftaran Keranjang (Order)
- [x] Halaman pilih peserta & tambah ke keranjang
- [x] Form pilih barang/paket untuk peserta
- [x] Kalkulasi otomatis total angsuran per minggu
- [x] Form edit order peserta
- [x] Fitur hapus order

---

## Minggu 3 — Pelacakan Angsuran

### Modul Angsuran
- [x] Tampilan grid peserta vs Minggu 1–45 (tabel Excel-like)
- [x] Fitur centang/input nominal setoran per minggu (Livewire, tanpa reload)
- [x] Kalkulasi otomatis status lunas / tunggakan per peserta
- [x] Indikator visual status pembayaran (lunas, sebagian, belum bayar)
- [x] Filter/cari peserta di halaman grid

---

## Minggu 4 — Modul Tabungan

### Modul Tabungan Peserta
- [x] Halaman ringkasan saldo tabungan seluruh peserta
- [x] Halaman riwayat transaksi per peserta
- [x] Form setoran tabungan (debit)
- [x] Form penarikan tabungan (kredit) dengan validasi saldo tidak negatif
- [x] Kalkulasi saldo terkini otomatis dari transaksi
- [x] Pencatatan `created_by` (admin yang input)

---

## Minggu 5 — Laporan, Export & Finalisasi

### Modul Laporan
- [x] Laporan rekap keuangan (total masuk per minggu & keseluruhan)
- [x] Laporan rekap belanja/kulakan (total kuantitas per barang)
- [x] Laporan rekap tabungan (saldo & riwayat per periode)
- [ ] Ekspor laporan ke PDF
- [ ] Ekspor laporan ke Excel

### Testing & Bug Fixing
- [x] Feature test autentikasi
- [x] Feature test CRUD peserta
- [x] Feature test CRUD barang & katalog
- [x] Feature test order & keranjang
- [x] Feature test pelacakan angsuran
- [x] Feature test tabungan (setoran, penarikan, validasi saldo)
- [x] Feature test laporan
- [ ] Bug fixing & polish UI
