# Target Pengembangan - SiJimbar

## Minggu 1 — Setup & Foundation

### Setup Project
- [ ] Setup Laravel project & konfigurasi environment
- [ ] Konfigurasi database (MySQL/PostgreSQL)
- [ ] Setup Livewire & Flux UI
- [ ] Setup autentikasi (Fortify)

### Database Migrations & Models
- [ ] Migration `categories`
- [ ] Migration `items`
- [ ] Migration `item_packages` (pivot)
- [ ] Migration `participants`
- [ ] Migration `orders`
- [ ] Migration `order_items`
- [ ] Migration `installments`
- [ ] Migration `savings`
- [ ] Migration `saving_transactions`
- [ ] Model & relasi `Category`
- [ ] Model & relasi `Item`
- [ ] Model & relasi `Participant`
- [ ] Model & relasi `Order`
- [ ] Model & relasi `OrderItem`
- [ ] Model & relasi `Installment`
- [ ] Model & relasi `Saving`
- [ ] Model & relasi `SavingTransaction`

### Modul Autentikasi
- [ ] Halaman Login
- [ ] Logout & manajemen sesi
- [ ] Proteksi route (middleware auth)

### Modul Peserta
- [ ] Halaman daftar peserta
- [ ] Form tambah peserta (Nama, No HP, Alamat, Catatan)
- [ ] Form edit peserta
- [ ] Fitur hapus peserta
- [ ] Validasi data peserta

---

## Minggu 2 — Katalog & Order

### Modul Kategori
- [ ] Halaman daftar kategori
- [ ] Form tambah & edit kategori
- [ ] Fitur hapus kategori

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
