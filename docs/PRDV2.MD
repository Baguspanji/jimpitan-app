# Product Requirements Document V2
**Nama Produk:** SiJimbar — Fitur Warga
**Versi:** 2.0
**Tanggal:** April 2026

---

## 1. Latar Belakang & Tujuan

V1 berfokus pada efisiensi kerja **admin/pengurus**. V2 bertujuan memperluas layanan langsung ke **warga/peserta**, sehingga warga dapat memantau status mereka sendiri tanpa harus menghubungi pengurus, dan pengurus dapat mencetak bukti tagihan individual untuk arsip fisik.

---

## 2. Fitur yang Dikembangkan

### 2.1. Rekap per Peserta (Cetak Slip)

**Deskripsi:**
Admin dapat mencetak atau mengunduh slip individual satu peserta dalam format PDF yang berisi ringkasan lengkap jimpitan peserta tersebut untuk satu periode.

**Aktor:** Admin / Pengurus

**Konten Slip:**
- Nama peserta, nomor HP, alamat
- Nama periode (mis. 2025-2026)
- Daftar barang/paket yang diambil beserta qty dan harga angsuran per minggu
- Total angsuran per minggu
- Progress pembayaran: sudah bayar X kali dari 45, sisa Y minggu
- Total nilai order keseluruhan (45 minggu × total/minggu)
- Total yang sudah terbayar
- Sisa tagihan
- Saldo tabungan aktif
- Footer: tanggal cetak, nama pengurus

**Trigger Aksi:**
- Tombol "Cetak Slip" di halaman detail angsuran peserta (`/installments?participant=X`)
- Tombol "Cetak Slip" di halaman daftar peserta (kolom aksi)

**Filter Periode:**
- Slip dicetak berdasarkan periode yang sedang aktif/dipilih
- Jika peserta memiliki lebih dari satu order di periode berbeda, admin memilih periode mana yang dicetak

**Output:** PDF yang dapat langsung dicetak atau diunduh, format A5 atau A4 (bisa dua slip per lembar A4)

---

### 2.2. Dashboard Warga (Akses Berbasis Token)

**Deskripsi:**
Admin menghasilkan sebuah URL unik bertanda waktu (*expiring link*) yang dapat dibagikan ke peserta (mis. via WhatsApp). Peserta membuka URL tersebut tanpa perlu login, dan dapat melihat data pribadi mereka secara *read-only*.

**Aktor:**
- Admin (generate & share URL)
- Warga/Peserta (akses URL)

#### 2.2.1. Manajemen Token (Sisi Admin)

**Generate Token:**
- Tombol "Bagikan Akses" di halaman daftar peserta dan halaman detail angsuran peserta
- Satu peserta memiliki satu token aktif sekaligus
- Admin memilih durasi kedaluwarsa saat generate: 1 hari, 7 hari, 30 hari
- Setelah token dibuat, URL siap dicopy (tombol copy) untuk dikirim ke warga
- Admin dapat me-*revoke* (mencabut) token sebelum kedaluwarsa
- Indikator status token di halaman peserta: Aktif (+ sisa waktu), Kedaluwarsa, Belum Dibuat

**Keamanan Token:**
- Token berupa string acak kriptografis (minimal 32 karakter — `Str::random(64)`)
- Token disimpan di tabel `participant_tokens` (bukan di URL sebagai plain ID)
- Akses melalui route publik (tanpa middleware `auth`)
- Jika token kedaluwarsa atau tidak ditemukan → tampil halaman "Link ini telah kedaluwarsa"
- Token hanya berlaku untuk satu peserta (tidak bisa digunakan untuk melihat data peserta lain)

#### 2.2.2. Tampilan Dashboard Warga (Sisi Warga)

**URL Pattern:** `GET /w/{token}`

**Konten yang Ditampilkan:**
1. **Identitas Peserta** — Nama, No HP (*masked*, mis. `0812****3456`), Alamat
2. **Ringkasan Order Aktif** — Periode aktif, daftar barang/paket, total angsuran per minggu
3. **Status Angsuran** — Progress bar visual (X dari 45 minggu sudah dibayar), daftar minggu yang sudah/belum dibayar
4. **Saldo Tabungan** — Saldo terkini + 5 transaksi terakhir (tanggal, jenis, nominal, keterangan)
5. **Footer** — Watermark "Hanya untuk peserta yang bersangkutan", tanggal kedaluwarsa link

**Tampilan:**
- Desain responsif (mobile-first, warga membuka dari HP)
- Tidak menggunakan navigasi/sidebar admin — layout sendiri (guest layout)
- Tidak ada aksi/tombol yang mengubah data (read-only)
- Tidak menampilkan data peserta lain

---

## 3. Struktur Database Tambahan

### Tabel `participant_tokens`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint PK | |
| `participant_id` | bigint FK | Referensi ke `participants.id`, cascade delete |
| `token` | string(64) | Unique, random, kriptografis |
| `expires_at` | timestamp | Waktu kedaluwarsa |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

Index: `token` (unique), `participant_id` (index)

---

## 4. Struktur Route Baru

```
# Admin — Slip
GET  /participants/{participant}/slip          → Pilih periode (jika > 1 order)
GET  /participants/{participant}/slip/{order}  → Generate & download PDF slip

# Admin — Token management
POST   /participants/{participant}/token       → Generate token baru
DELETE /participants/{participant}/token       → Revoke token aktif

# Publik — Dashboard Warga
GET  /w/{token}  → Tampilkan dashboard warga (no auth)
```

---

## 5. Batasan & Asumsi

- Dashboard warga adalah **read-only** — tidak ada form input apapun
- Satu peserta hanya punya **satu token aktif** sekaligus; generate baru otomatis invalidasi lama
- Token yang sudah kedaluwarsa **tidak dihapus otomatis** dari DB (dibersihkan via scheduled command atau lazy-delete saat generate baru)
- Fitur ini tidak mengubah alur kerja admin yang sudah ada di V1
- Slip PDF menggunakan package `barryvdh/laravel-dompdf` yang sudah terpasang
