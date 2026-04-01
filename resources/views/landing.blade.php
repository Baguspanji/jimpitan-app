<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="SiJimbar membantu pengurus mencatat angsuran, merekap pesanan, dan memantau tabungan warga hanya dalam hitungan detik.">

        <title>{{ config('app.name', 'SiJimbar') }} – Bebas Pusing Urus Jimpitan Lebaran</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-gray-800 font-sans antialiased">

        {{-- ===================== NAVBAR ===================== --}}
        <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
            <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="/assets/logo-square.png" alt="{{ config('app.name', 'SiJimbar') }}" class="h-9 w-9 object-contain">
                    <span class="font-bold text-lg text-emerald-700">{{ config('app.name', 'SiJimbar') }}</span>
                </a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                    <a href="#fitur" class="hover:text-emerald-600 transition-colors">Fitur</a>
                    <a href="#cara-kerja" class="hover:text-emerald-600 transition-colors">Cara Kerja</a>
                    <a href="#kalkulator" class="hover:text-amber-500 transition-colors">Kalkulator</a>
                    <a href="#testimoni" class="hover:text-emerald-600 transition-colors">Testimoni</a>
                    <a href="#kontak" class="hover:text-emerald-600 transition-colors">Hubungi Kami</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-emerald-600 transition-colors hidden sm:inline">Masuk</a>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- ===================== SECTION 1: HERO ===================== --}}
        <section class="relative overflow-hidden bg-linear-to-br from-emerald-50 via-white to-amber-50 pt-20 pb-24 md:pt-28 md:pb-32">
            {{-- Decorative blobs --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-200 rounded-full opacity-30 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-amber-200 rounded-full opacity-30 blur-3xl pointer-events-none"></div>

            <div class="relative max-w-6xl mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="flex flex-col gap-6">
                        <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 text-sm font-semibold px-4 py-1.5 rounded-full w-fit">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                            Digitalisasi Jimpitan Lebaran
                        </div>
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                            Bebas Pusing Urus Jimpitan Lebaran.
                            <span class="text-emerald-600">Cepat, Tepat, dan Transparan.</span>
                        </h1>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            Tinggalkan buku catatan manual Anda. SiJimbar membantu pengurus mencatat angsuran, merekap pesanan, dan memantau tabungan warga hanya dalam hitungan detik.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <a href="#video-promo" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3.5 rounded-xl border border-gray-200 shadow-sm transition-colors">
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                Tonton Cara Kerjanya
                            </a>
                        </div>
                        <p class="text-sm text-gray-500">Gratis selamanya untuk 1 komunitas. Tidak perlu kartu kredit.</p>
                    </div>
                    <div class="relative flex justify-center items-center">
                        <div class="absolute inset-0 bg-linear-to-tr from-emerald-100 to-amber-100 rounded-3xl opacity-50 blur-xl scale-95"></div>
                        <img
                            src="/assets/banner.png"
                            alt="SiJimbar App Preview"
                            class="relative w-full max-w-md rounded-2xl shadow-2xl"
                        >
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== SECTION 2: PROBLEM & SOLUTION ===================== --}}
        <section class="py-20 md:py-28 bg-white">
            <div class="max-w-6xl mx-auto px-6">
                <div class="text-center mb-14">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Kenapa Masih Repot Kalau Bisa Praktis?</h2>
                    <p class="text-gray-500 max-w-xl mx-auto">Bandingkan cara lama dengan cara baru yang jauh lebih efisien.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Masalah --}}
                    <div class="bg-red-50 border border-red-100 rounded-2xl p-8 flex flex-col gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-red-700">Cara Lama (Manual)</h3>
                        </div>
                        <ul class="flex flex-col gap-4">
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 text-red-500 text-lg font-bold shrink-0">✗</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Buku Hilang/Rusak</p>
                                    <p class="text-sm text-gray-500 mt-0.5">Data setoran rawan hilang atau terselip.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 text-red-500 text-lg font-bold shrink-0">✗</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Salah Hitung</p>
                                    <p class="text-sm text-gray-500 mt-0.5">Rawan selisih saat merekap total uang atau sisa angsuran.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 text-red-500 text-lg font-bold shrink-0">✗</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Rekap Bikin Pusing</p>
                                    <p class="text-sm text-gray-500 mt-0.5">Menghitung total barang yang harus dibeli (kulakan) memakan waktu berhari-hari.</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Solusi --}}
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-8 flex flex-col gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-emerald-700">Solusi SiJimbar (Digital)</h3>
                        </div>
                        <ul class="flex flex-col gap-4">
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 text-emerald-600 text-lg font-bold shrink-0">✓</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Aman di Cloud</p>
                                    <p class="text-sm text-gray-500 mt-0.5">Data tersimpan aman dan bisa diakses kapan saja, dari perangkat mana saja.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 text-emerald-600 text-lg font-bold shrink-0">✓</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Otomatis & Akurat</p>
                                    <p class="text-sm text-gray-500 mt-0.5">Sistem menghitung total setoran, sisa angsuran, dan nilai paket secara instan.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 text-emerald-600 text-lg font-bold shrink-0">✓</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Rekap Kulakan 1-Klik</p>
                                    <p class="text-sm text-gray-500 mt-0.5">Langsung tahu total Beras, Minyak, atau Snack yang harus dibeli tanpa hitung manual.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== SECTION 3: FITUR UTAMA ===================== --}}
        <section id="fitur" class="py-20 md:py-28 bg-linear-to-b from-gray-50 to-white">
            <div id="cara-kerja" class="max-w-6xl mx-auto px-6">
                <div class="text-center mb-14">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan SiJimbar</h2>
                    <p class="text-gray-500 max-w-xl mx-auto">Semua yang Anda butuhkan untuk mengelola jimpitan secara modern dan profesional.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Fitur 1 --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">01</span>
                            <h3 class="text-base font-bold text-gray-900 mt-1">Katalog Master Fleksibel</h3>
                            <p class="text-sm text-gray-500 mt-2 leading-relaxed">Buat daftar barang (Sembako, Snack, Minuman) beserta harga angsuran mingguan. Buat "Paket Spesial" lengkap dengan bonusnya.</p>
                        </div>
                    </div>

                    {{-- Fitur 2 --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">02</span>
                            <h3 class="text-base font-bold text-gray-900 mt-1">Kartu Peserta Digital</h3>
                            <p class="text-sm text-gray-500 mt-2 leading-relaxed">Daftarkan warga dan pilihkan paket sesuai keinginan mereka. Sistem otomatis memunculkan total tagihan per minggu.</p>
                        </div>
                    </div>

                    {{-- Fitur 3 --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">03</span>
                            <h3 class="text-base font-bold text-gray-900 mt-1">Dasbor Angsuran Cerdas</h3>
                            <p class="text-sm text-gray-500 mt-2 leading-relaxed">Tandai pembayaran warga cukup dengan satu ketukan. Pantau siapa yang sudah lunas dan yang masih menunggak dengan indikator warna.</p>
                        </div>
                    </div>

                    {{-- Fitur 4 --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-purple-600 uppercase tracking-wider">04</span>
                            <h3 class="text-base font-bold text-gray-900 mt-1">Laporan Otomatis Siap Cetak</h3>
                            <p class="text-sm text-gray-500 mt-2 leading-relaxed">Hasilkan laporan keuangan dan rekapitulasi daftar belanjaan (kulakan) dalam format PDF atau Excel. Persiapan lebaran jadi jauh lebih tenang.</p>
                        </div>
                    </div>
                </div>

                {{-- Video promo --}}
                <div id="video-promo" class="mt-16 rounded-2xl overflow-hidden shadow-xl border border-gray-100">
                    <video
                        class="w-full rounded-2xl"
                        controls
                        preload="metadata"
                        poster="/assets/banner.png"
                    >
                        <source src="/assets/video-promo.mp4" type="video/mp4">
                        Browser Anda tidak mendukung video HTML5.
                    </video>
                </div>
            </div>
        </section>

        {{-- ===================== SECTION KALKULATOR ===================== --}}
        <section id="kalkulator" class="py-20 md:py-28 bg-white">
            <div class="max-w-6xl mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-12 items-center">

                    {{-- Teks --}}
                    <div class="flex flex-col gap-6">
                        <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 text-sm font-semibold px-4 py-1.5 rounded-full w-fit">
                            🧮 Tools Gratis untuk Warga
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
                            Simulasikan Angsuranmu <span class="text-amber-500">Sebelum Mendaftar</span>
                        </h2>
                        <p class="text-gray-600 leading-relaxed">
                            Tidak yakin mau pilih barang apa? Gunakan <strong>Kalkulator Jimpitan</strong> kami — jelajahi katalog lengkap (Sembako, Snack, Minuman, Paket Spesial), pilih item yang diinginkan, dan lihat estimasi total angsuran mingguanmu secara instan.
                        </p>

                        {{-- Highlights --}}
                        <ul class="flex flex-col gap-3">
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center shrink-0 mt-0.5 text-sm">🛒</span>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">49 Pilihan Barang</p>
                                    <p class="text-xs text-gray-500">Sembako, Snack, Minuman, hingga Paket Spesial dengan bonus.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center shrink-0 mt-0.5 text-sm">📊</span>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">Estimasi Otomatis</p>
                                    <p class="text-xs text-gray-500">Total angsuran mingguan & nilai total setelah 45× pembayaran dihitung langsung.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center shrink-0 mt-0.5 text-sm">🆓</span>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">Sepenuhnya Gratis</p>
                                    <p class="text-xs text-gray-500">Tidak perlu login. Langsung akses dan simulasikan kapan saja.</p>
                                </div>
                            </li>
                        </ul>

                        <a
                            href="https://kalkulator.kodebagus.com/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 bg-amber-400 hover:bg-amber-500 text-gray-900 font-bold px-6 py-3.5 rounded-xl shadow-md shadow-amber-100 transition-all hover:shadow-lg hover:-translate-y-0.5 w-fit"
                        >
                            Buka Kalkulator Jimpitan
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </a>
                        <p class="text-xs text-gray-400">Dibuka di tab baru. Cocok untuk warga Dsn. Tegalan – Bakalan – Purwosari.</p>
                    </div>

                    {{-- Preview card --}}
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-tr from-amber-50 to-emerald-50 rounded-3xl blur-xl scale-95 opacity-70"></div>
                        <div class="relative bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden">
                            {{-- Header card --}}
                            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-5 py-4 text-white">
                                <p class="font-bold text-base">🧮 Kalkulator Jimpitan</p>
                                <p class="text-xs text-emerald-100 mt-0.5">Periode 2026 – 2027 · Dsn. Tegalan</p>
                            </div>
                            {{-- Simulasi items --}}
                            <div class="px-5 py-4 flex flex-col gap-3">
                                @foreach ([['Beras 25 Kg', 'Sembako', 'Rp 8.300'], ['Bimoli 5L', 'Sembako', 'Rp 3.500'], ['Gula 5 Kg', 'Sembako', 'Rp 4.500'], ['Kongguan Besar', 'Snack', 'Rp 3.000'], ['Teh Gelas 2 Dos', 'Minuman', 'Rp 1.300']] as [$nama, $kat, $harga])
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full {{ $kat === 'Sembako' ? 'bg-emerald-400' : ($kat === 'Snack' ? 'bg-amber-400' : 'bg-blue-400') }}"></span>
                                            <span class="text-gray-700">{{ $nama }}</span>
                                            <span class="text-xs text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded">{{ $kat }}</span>
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $harga }}/mgg</span>
                                    </div>
                                @endforeach
                                <div class="border-t border-dashed border-gray-100 pt-3 mt-1">
                                    <div class="flex justify-between text-sm font-bold">
                                        <span class="text-gray-600">Total Angsuran</span>
                                        <span class="text-emerald-600">Rp 20.600/minggu</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                                        <span>Estimasi total (45×)</span>
                                        <span>Rp 927.000</span>
                                    </div>
                                </div>
                            </div>
                            {{-- CTA di dalam card --}}
                            <div class="px-5 pb-5">
                                <a href="https://kalkulator.kodebagus.com/" target="_blank" rel="noopener noreferrer" class="block w-full text-center bg-amber-400 hover:bg-amber-500 text-gray-900 font-semibold text-sm py-2.5 rounded-xl transition-colors">
                                    Coba Simulasi Saya →
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===================== SECTION 4: TESTIMONI ===================== --}}
        <section id="testimoni" class="py-20 md:py-28 bg-emerald-700">
            <div class="max-w-6xl mx-auto px-6">
                <div class="text-center mb-14">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Apa Kata Mereka?</h2>
                    <p class="text-emerald-200 max-w-xl mx-auto">Ribuan pengurus sudah merasakan manfaat SiJimbar.</p>
                </div>
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-2xl p-8 shadow-xl">
                        <div class="flex gap-1 mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <blockquote class="text-gray-700 text-lg leading-relaxed mb-6">
                            "Dulu tiap mau lebaran saya stres merekap buku jimpitan warga Dsn. Tegalan. Sejak pakai SiJimbar, laporan selesai dalam hitungan menit. Warga juga senang karena perhitungannya transparan."
                        </blockquote>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Mbk Izzah</p>
                                <p class="text-sm text-gray-500">Pengurus Jimpitan, Dsn. Tegalan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== SECTION 5: FINAL CTA ===================== --}}
        <section class="py-20 md:py-28 bg-linear-to-br from-amber-50 via-white to-emerald-50">
            <div class="max-w-3xl mx-auto px-6 text-center flex flex-col items-center gap-8">
                <img src="/assets/logo-square.png" alt="{{ config('app.name', 'SiJimbar') }}" class="h-20 w-20 object-contain drop-shadow-md">
                <div class="flex flex-col gap-3">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Siap Mengubah Cara Anda Mengurus Jimpitan?</h2>
                    <p class="text-lg text-gray-600">Jadilah pengurus yang modern, efisien, dan dipercaya warga. Mulai digitalisasi jimpitan Anda hari ini.</p>
                </div>
            </div>
        </section>

        {{-- ===================== SECTION 6: FOOTER ===================== --}}
        <footer id="kontak" class="bg-gray-900 text-gray-400 py-12">
            <div class="max-w-6xl mx-auto px-6">
                <div class="grid md:grid-cols-3 gap-8 pb-10 border-b border-gray-800">
                    {{-- Brand --}}
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <img src="/assets/logo-square.png" alt="{{ config('app.name', 'SiJimbar') }}" class="h-9 w-9 object-contain">
                            <span class="font-bold text-lg text-white">{{ config('app.name', 'SiJimbar') }}</span>
                        </a>
                        <p class="text-sm leading-relaxed">Solusi digital untuk pengurus jimpitan lebaran yang modern, efisien, dan transparan.</p>
                    </div>

                    {{-- Menu --}}
                    <div>
                        <p class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Navigasi</p>
                        <ul class="flex flex-col gap-2 text-sm">
                            <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Beranda</a></li>
                            <li><a href="#fitur" class="hover:text-emerald-400 transition-colors">Fitur</a></li>
                            <li><a href="#cara-kerja" class="hover:text-emerald-400 transition-colors">Cara Kerja</a></li>
                            <li><a href="#kalkulator" class="hover:text-amber-400 transition-colors">Kalkulator Jimpitan</a></li>
                            <li><a href="#kontak" class="hover:text-emerald-400 transition-colors">Hubungi Kami</a></li>
                        </ul>
                    </div>

                    {{-- Kontak --}}
                    <div>
                        <p class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Kontak</p>
                        <ul class="flex flex-col gap-2 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:halo@sijimbar.id" class="hover:text-emerald-400 transition-colors">halo@sijimbar.id</a>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span>WhatsApp: <a href="https://wa.me/" class="hover:text-emerald-400 transition-colors">[Nomor WA]</a></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
                    <p>© {{ date('Y') }} {{ config('app.name', 'SiJimbar') }}. Dibuat dengan ❤ untuk komunitas.</p>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors">Masuk</a>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
