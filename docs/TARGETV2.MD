# Target Pengembangan V2 — SiJimbar Fitur Warga

---

## Sprint 1 — Rekap per Peserta (Cetak Slip PDF)

### Database & Model
- [ ] Tidak ada migration baru (memanfaatkan relasi yang sudah ada)

### Backend
- [ ] Route `GET /participants/{participant}/slip` — pilih order/periode jika lebih dari satu
- [ ] Route `GET /participants/{participant}/slip/{order}` — generate PDF slip
- [ ] Controller `SlipExportController` dengan method `show()` (pilih periode) dan `download()`
- [ ] Blade view `resources/views/exports/slip.blade.php` (layout PDF, A5)
- [ ] Logic: hitung total yang sudah terbayar, sisa tagihan, progress angsuran

### UI Admin
- [ ] Tombol "Cetak Slip" di halaman daftar peserta (kolom aksi, ikon `printer`)
- [ ] Tombol "Cetak Slip" di halaman detail angsuran peserta (`/installments?participant=X`)
- [ ] Jika peserta memiliki >1 order aktif: tampilkan dropdown pilih periode sebelum download

### Testing
- [ ] Feature test: PDF slip dapat diunduh oleh admin (assertion `assertDownload`)
- [ ] Feature test: route slip mengembalikan 403 untuk guest
- [ ] Feature test: slip berisi data peserta yang benar

---

## Sprint 2 — Dashboard Warga (Token-Based Access)

### Database & Model
- [ ] Migration `create_participant_tokens_table` (`id`, `participant_id`, `token`, `expires_at`, `timestamps`)
- [ ] Index unique pada kolom `token`, index pada `participant_id`
- [ ] Model `ParticipantToken` dengan relasi `belongsTo(Participant::class)`
- [ ] Relasi `hasOne(ParticipantToken::class)` di model `Participant`
- [ ] Cast `expires_at` ke `datetime`

### Backend — Token Management (Admin)
- [ ] Route `POST /participants/{participant}/token` → `ParticipantTokenController@generate`
- [ ] Route `DELETE /participants/{participant}/token` → `ParticipantTokenController@revoke`
- [ ] Logic `generate`: hapus token lama, buat token baru (`Str::random(64)`), simpan `expires_at` sesuai pilihan durasi (1/7/30 hari)
- [ ] Logic `revoke`: hapus token aktif peserta
- [ ] Validasi durasi: hanya menerima nilai `1`, `7`, `30`

### Backend — Dashboard Publik
- [ ] Route `GET /w/{token}` → `WargaDashboardController@show` (tanpa middleware `auth`)
- [ ] Controller: temukan token, validasi belum kedaluwarsa, load relasi (`participant.orders.orderItems.item`, `participant.savings.transactions`)
- [ ] Jika token tidak ditemukan atau kedaluwarsa → return view `pages::warga.expired`
- [ ] Masking nomor HP: `0812****3456` (tampilkan 4 digit pertama + 4 digit terakhir)

### UI Admin — Token Management
- [ ] Di halaman daftar peserta: kolom/badge status token (Aktif + sisa waktu / Kedaluwarsa / Belum Ada)
- [ ] Tombol "Bagikan Akses" membuka modal dengan:
  - [ ] Pilihan durasi (radio: 1 hari, 7 hari, 30 hari)
  - [ ] Tombol "Generate Link"
  - [ ] Setelah generate: tampilkan URL lengkap + tombol "Salin Link"
  - [ ] Tombol "Cabut Akses" (revoke) jika token masih aktif

### UI Warga — Dashboard Publik
- [ ] Layout baru `resources/views/layouts/warga.blade.php` (guest, mobile-first, tanpa sidebar)
- [ ] Halaman `resources/views/pages/warga/dashboard.blade.php`
  - [ ] Kartu identitas peserta (nama, HP masked, alamat)
  - [ ] Kartu ringkasan order aktif (periode, list barang, total/minggu)
  - [ ] Kartu progress angsuran (progress bar, X/45 minggu, visual minggu lunas vs belum)
  - [ ] Kartu saldo tabungan + 5 transaksi terakhir
  - [ ] Footer: watermark & tanggal kedaluwarsa link
- [ ] Halaman `resources/views/pages/warga/expired.blade.php` (link tidak valid / kedaluwarsa)
- [ ] Responsif mobile (warga membuka dari HP)

### Testing
- [ ] Feature test: generate token berhasil → token tersimpan di DB
- [ ] Feature test: generate token baru otomatis menghapus token lama
- [ ] Feature test: revoke token → token terhapus dari DB
- [ ] Feature test: `GET /w/{valid-token}` mengembalikan 200 dan menampilkan data peserta yang benar
- [ ] Feature test: `GET /w/{expired-token}` menampilkan halaman kedaluwarsa (200, bukan 404)
- [ ] Feature test: `GET /w/{invalid-token}` mengembalikan 404
- [ ] Feature test: token tidak bisa diakses untuk melihat data peserta lain
- [ ] Feature test: nomor HP di-mask dengan benar

### Finalisasi
- [ ] Jalankan `vendor/bin/pint --dirty`
- [ ] Semua test passing
- [ ] Update `docs/TARGET.MD` (atau file ini) dengan status akhir
