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
- [ ] Halaman daftar barang (dengan filter kategori)
- [ ] Form tambah barang reguler (Nama, Satuan, Harga/Minggu, Kategori)
- [ ] Form tambah paket spesial (relasi ke barang reguler + field bonus)
- [ ] Form edit barang/paket
- [ ] Fitur hapus barang/paket

### Modul Pendaftaran Keranjang (Order)
- [ ] Halaman pilih peserta & tambah ke keranjang
- [ ] Form pilih barang/paket untuk peserta
- [ ] Kalkulasi otomatis total angsuran per minggu
- [ ] Form edit order peserta
- [ ] Fitur hapus order

---

## Minggu 3 — Pelacakan Angsuran

### Modul Angsuran
- [ ] Tampilan grid peserta vs Minggu 1–45 (tabel Excel-like)
- [ ] Fitur centang/input nominal setoran per minggu (Livewire, tanpa reload)
- [ ] Kalkulasi otomatis status lunas / tunggakan per peserta
- [ ] Indikator visual status pembayaran (lunas, sebagian, belum bayar)
- [ ] Filter/cari peserta di halaman grid

---

## Minggu 4 — Modul Tabungan

### Modul Tabungan Peserta
- [ ] Halaman ringkasan saldo tabungan seluruh peserta
- [ ] Halaman riwayat transaksi per peserta
- [ ] Form setoran tabungan (debit)
- [ ] Form penarikan tabungan (kredit) dengan validasi saldo tidak negatif
- [ ] Kalkulasi saldo terkini otomatis dari transaksi
- [ ] Pencatatan `created_by` (admin yang input)

---

## Minggu 5 — Laporan, Export & Finalisasi

### Modul Laporan
- [ ] Laporan rekap keuangan (total masuk per minggu & keseluruhan)
- [ ] Laporan rekap belanja/kulakan (total kuantitas per barang)
- [ ] Laporan rekap tabungan (saldo & riwayat per periode)
- [ ] Ekspor laporan ke PDF
- [ ] Ekspor laporan ke Excel

### Testing & Bug Fixing
- [ ] Feature test autentikasi
- [ ] Feature test CRUD peserta
- [ ] Feature test CRUD barang & katalog
- [ ] Feature test order & keranjang
- [ ] Feature test pelacakan angsuran
- [ ] Feature test tabungan (setoran, penarikan, validasi saldo)
- [ ] Feature test laporan
- [ ] Bug fixing & polish UI
