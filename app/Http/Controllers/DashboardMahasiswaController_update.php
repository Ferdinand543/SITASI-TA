<?php
// Tambahkan bagian ini ke method index() DashboardMahasiswaController
// di bagian pengambilan data, sebelum return view()

// ── Jadwal / Tanggal Penting ──
// Ambil semua jadwal yang relevan untuk mahasiswa
$jadwalList = DB::table('jadwal')
    ->orderBy('tanggal', 'asc')
    ->get();

// Cari deadline terdekat yang belum lewat
$deadlineDekat = DB::table('jadwal')
    ->where('tanggal', '>=', now()->toDateString())
    ->orderBy('tanggal', 'asc')
    ->first();

// Kalau semua sudah lewat, ambil yang paling terakhir
if (!$deadlineDekat) {
    $deadlineDekat = DB::table('jadwal')
        ->orderBy('tanggal', 'desc')
        ->first();
}

// Return view — tambahkan variabel ini ke compact()
// compact('jadwalList', 'deadlineDekat', ...)
