<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportMahasiswaController extends Controller
{
    // ── Download template Excel ──────────────────────────────────────
    public function template()
    {
        if (!session('user')) return redirect('/login');

        $path = public_path('templates/template_import_mahasiswa.xlsx');

        if (!file_exists($path)) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        return response()->download($path, 'template_import_mahasiswa.xlsx');
    }

    // ── Import dari file Excel ───────────────────────────────────────
    public function import(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file_excel.required' => 'File Excel wajib dipilih.',
            'file_excel.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            set_time_limit(300);
            $file        = $request->file('file_excel');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);

            // Ambil semua NIM dan email yang sudah ada di DB
            $existingNim   = DB::table('users')->pluck('nim_nid')->map(fn($v) => strtolower(trim($v)))->toArray();
            $existingEmail = DB::table('users')->pluck('email')->map(fn($v) => strtolower(trim($v)))->toArray();

            $berhasil = 0;
            $gagal    = [];

            foreach ($rows as $rowNum => $row) {
                // Skip header (baris 1)
                if ($rowNum === 1) continue;

                $nim         = trim($row['A'] ?? '');
                $nama        = trim($row['B'] ?? '');
                $email       = trim($row['C'] ?? '');
                $angkatan    = trim($row['D'] ?? '');
                $password    = trim($row['E'] ?? '');
                $noKontak    = trim($row['F'] ?? '');
                $ipkTerakhir = trim($row['G'] ?? '');

                // Skip baris kosong
                if (empty($nim) && empty($nama) && empty($email)) continue;

                // Validasi per baris
                $errors = [];

                if (empty($nim))      $errors[] = 'NIM kosong';
                if (empty($nama))     $errors[] = 'Nama kosong';
                if (empty($email))    $errors[] = 'Email kosong';
                if (empty($angkatan)) $errors[] = 'Angkatan kosong';
                if (empty($password)) $errors[] = 'Password kosong';

                if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Format email tidak valid';
                }

                if (!empty($password) && strlen($password) < 6) {
                    $errors[] = 'Password minimal 6 karakter';
                }

                if (!empty($password) && strlen($password) > 20) {
                    $errors[] = 'Password maksimal 20 karakter';
                }

                // Validasi IPK (opsional, tapi kalau diisi harus angka 0-4)
                if (!empty($ipkTerakhir) && (!is_numeric($ipkTerakhir) || $ipkTerakhir < 0 || $ipkTerakhir > 4)) {
                    $errors[] = 'IPK tidak valid (harus angka 0-4)';
                }

                if (in_array(strtolower(trim($nim)), $existingNim)) {
                    $errors[] = 'NIM sudah terdaftar';
                }

                if (!empty($email) && in_array(strtolower(trim($email)), $existingEmail)) {
                    $errors[] = 'Email sudah terdaftar';
                }

                if (!empty($errors)) {
                    $gagal[] = "Baris {$rowNum} ({$nim}): " . implode(', ', $errors);
                    continue;
                }

                // Insert ke DB
                DB::statement('SET @OLD_SQL_MODE=@@SQL_MODE');
                DB::table('users')->insert([
                    'nim_nid'      => $nim,
                    'nama'         => $nama,
                    'email'        => strtolower($email),
                    'angkatan'     => $angkatan,
                    'password'     => Hash::make($password, ['rounds' => 4]),
                    'role'         => 'mahasiswa',
                    'no_kontak'    => $noKontak !== '' ? $noKontak : null,
                    'ipk_terakhir' => $ipkTerakhir !== '' ? $ipkTerakhir : null,
                ]);

                // Tambah ke list existing biar baris berikutnya tidak duplikat dalam file yg sama
                $existingNim[]   = strtolower(trim($nim));
                $existingEmail[] = strtolower(trim($email));

                $berhasil++;
            }

            $message = "Import selesai. {$berhasil} mahasiswa berhasil ditambahkan.";

            if (!empty($gagal)) {
                $message .= ' ' . count($gagal) . ' baris gagal: ' . implode(' | ', array_slice($gagal, 0, 5));
                if (count($gagal) > 5) $message .= ' ... dan ' . (count($gagal) - 5) . ' lainnya.';
                return redirect()->back()->with('warning', $message);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }
}