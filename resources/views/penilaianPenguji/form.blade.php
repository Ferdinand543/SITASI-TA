@extends('layouts.app')

@section('content')

<div style="padding-bottom:40px;">

    {{-- BACK --}}
    <a href="{{ route('penilaian.index') }}" style="
        display:inline-flex;align-items:center;gap:6px;
        color:#92741A;font-size:0.85rem;font-weight:600;
        text-decoration:none;margin-bottom:22px;">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>

    {{-- HERO --}}
    <div style="
        background:linear-gradient(135deg,#FFFBEB,#FEF3C7,#FDE68A);
        border-radius:20px;padding:36px 32px;margin-bottom:28px;
        position:relative;overflow:hidden;min-height:150px;">

        <div style="position:absolute;left:24px;top:50%;transform:translateY(-50%);
                    display:grid;grid-template-columns:repeat(3,6px);gap:5px;opacity:0.25;">
            @for($i=0;$i<12;$i++)
            <div style="width:5px;height:5px;border-radius:50%;background:#92741A;"></div>
            @endfor
        </div>

        <svg style="position:absolute;right:0;top:0;width:220px;height:100%;"
             viewBox="0 0 220 150" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMaxYMid slice">
            <path d="M220 0 Q150 30 170 75 Q190 120 140 150 L220 150Z" fill="#FDE68A" opacity="0.6"/>
            <path d="M220 0 Q170 40 185 80 Q200 115 160 150 L220 150Z" fill="#FACC15" opacity="0.35"/>
            <path d="M220 10 Q180 50 195 95 Q205 125 175 150 L220 150Z" fill="#F59E0B" opacity="0.2"/>
            <line x1="140" y1="0" x2="220" y2="60" stroke="#FACC15" stroke-width="1.5" opacity="0.4"/>
            <line x1="160" y1="0" x2="220" y2="45" stroke="#FACC15" stroke-width="1.5" opacity="0.35"/>
            <line x1="180" y1="0" x2="220" y2="30" stroke="#FACC15" stroke-width="1.5" opacity="0.3"/>
            <line x1="155" y1="20" x2="220" y2="90" stroke="#FACC15" stroke-width="1" opacity="0.25"/>
        </svg>

        <div style="position:relative;z-index:2;padding-left:32px;max-width:68%;">
            <div style="font-size:1.6rem;font-weight:800;color:#735C00;margin-bottom:10px;line-height:1.2;">
                Form Penilaian Seminar TA-1
            </div>
            <div style="font-size:0.88rem;font-weight:400;color:#92741A;line-height:1.6;">
                Silakan berikan penilaian objektif berdasarkan performa mahasiswa dalam memaparkan
                progres penelitian Tugas Akhir tahap pertama.
            </div>
        </div>
    </div>

    <form action="{{ route('penilaian.store', $proposal->id) }}"
          method="POST" id="formPenilaian">
        @csrf

        {{-- INFO MAHASISWA --}}
        <div style="
            background:#fff;border-radius:16px;padding:28px;
            border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);
            margin-bottom:20px;">

            {{-- ✅ DIFIX: warna label header #574500 --}}
            <div style="font-size:0.75rem;font-weight:700;color:#574500;
                        text-transform:uppercase;letter-spacing:1px;
                        margin-bottom:18px;
                        border-left:3px solid #FACC15;padding-left:10px;">
                Informasi Mahasiswa
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label style="font-size:0.75rem;color:#9ca3af;font-weight:600;display:block;margin-bottom:6px;">NIM Mahasiswa</label>
                    <div style="padding:10px 14px;background:#FAFAFA;border:1px solid #E5E7EB;
                        border-radius:10px;font-size:0.88rem;font-weight:600;color:#374151;
                        display:flex;align-items:center;gap:8px;">
                        <i class="fa fa-id-card" style="color:#FACC15;"></i>
                        {{ $proposal->nim_nid }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.75rem;color:#9ca3af;font-weight:600;display:block;margin-bottom:6px;">Nama Mahasiswa</label>
                    <div style="padding:10px 14px;background:#FAFAFA;border:1px solid #E5E7EB;
                        border-radius:10px;font-size:0.88rem;font-weight:600;color:#374151;
                        display:flex;align-items:center;gap:8px;">
                        <i class="fa fa-user" style="color:#FACC15;"></i>
                        {{ $proposal->nama_mahasiswa }}
                    </div>
                </div>
                @if($proposal->judul_ta)
                <div class="col-12">
                    <label style="font-size:0.75rem;color:#9ca3af;font-weight:600;display:block;margin-bottom:6px;">Judul Tugas Akhir</label>
                    <div style="padding:10px 14px;background:#FAFAFA;border:1px solid #E5E7EB;
                        border-radius:10px;font-size:0.88rem;color:#374151;
                        display:flex;align-items:flex-start;gap:8px;line-height:1.5;">
                        <i class="fa fa-book-open" style="color:#FACC15;margin-top:2px;flex-shrink:0;"></i>
                        {{ $proposal->judul_ta }}
                    </div>
                </div>
                @endif
                <div class="col-md-6">
                    <label style="font-size:0.75rem;color:#9ca3af;font-weight:600;display:block;margin-bottom:6px;">Nama Dosen Penguji</label>
                    <div style="padding:10px 14px;background:#FAFAFA;border:1px solid #E5E7EB;
                        border-radius:10px;font-size:0.88rem;font-weight:600;color:#374151;
                        display:flex;align-items:center;gap:8px;">
                        <i class="fa fa-chalkboard-user" style="color:#FACC15;"></i>
                        {{ $dosenPenguji->nama ?? '-' }}
                    </div>
                </div>
                <div class="col-md-3">
                    <label style="font-size:0.75rem;color:#9ca3af;font-weight:600;display:block;margin-bottom:6px;">NID</label>
                    <div style="padding:10px 14px;background:#FAFAFA;border:1px solid #E5E7EB;
                        border-radius:10px;font-size:0.88rem;font-weight:600;color:#374151;">
                        {{ $dosenPenguji->nim_nid ?? '-' }}
                    </div>
                </div>
                <div class="col-md-3">
                    <label style="font-size:0.75rem;color:#9ca3af;font-weight:600;display:block;margin-bottom:6px;">Sebagai</label>
                    <div style="padding:10px 14px;background:#FAFAFA;border:1px solid #E5E7EB;
                        border-radius:10px;font-size:0.88rem;font-weight:600;color:#735C00;">
                        {{ $urutanPenguji }}
                    </div>
                </div>
            </div>
        </div>

        {{-- KOMPONEN PENILAIAN --}}
        <div style="
            background:#fff;border-radius:16px;padding:28px;
            border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);
            margin-bottom:20px;">

            {{-- ✅ DIFIX: warna label header #574500 --}}
            <div style="font-size:0.75rem;font-weight:700;color:#574500;
                        text-transform:uppercase;letter-spacing:1px;
                        margin-bottom:20px;
                        border-left:3px solid #FACC15;padding-left:10px;">
                Penilaian Seminar TA-1
            </div>

            @php
            $komponen = [
                ['key'=>'nilai_teknik_presentasi','label'=>'Teknik dan sikap presentasi','maks'=>15,
                 'desc'=>'Kualitas visual slide, artikulasi bicara, kepercayaan diri, dan manajemen waktu.'],
                ['key'=>'nilai_dokumentasi','label'=>'Dokumentasi dan tata cara penulisan','maks'=>20,
                 'desc'=>'Kesesuaian format penulisan, kerapihan sitasi, dan kelengkapan bab 1-3.'],
                ['key'=>'nilai_pemahaman_teori','label'=>'Pemahaman teori, metode penelitian, konsep hingga pengujian','maks'=>30,
                 'desc'=>'Kedalaman landasan teori dan ketepatan metodologi yang digunakan.'],
                ['key'=>'nilai_pemahaman_kebutuhan','label'=>'Pemahaman kebutuhan dan permasalahan penelitian','maks'=>35,
                 'desc'=>'Kemampuan mengidentifikasi gap penelitian dan urgensi solusi yang diajukan.'],
            ];
            @endphp

            @foreach($komponen as $k)
            <div style="padding:18px 20px;border:1px solid #F3F4F6;border-radius:12px;
                        margin-bottom:12px;background:#FAFAFA;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                    <div style="flex:1;">
                        <div style="font-size:0.9rem;font-weight:700;color:#374151;margin-bottom:3px;">
                            {{ $k['label'] }}
                        </div>
                        <div style="font-size:0.75rem;color:#9ca3af;">{{ $k['desc'] }}</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:0.75rem;color:#9ca3af;white-space:nowrap;">
                                Maks {{ $k['maks'] }} poin
                            </span>
                            <input type="number"
                                name="{{ $k['key'] }}"
                                id="inp-{{ $k['key'] }}"
                                min="0" max="{{ $k['maks'] }}" step="1"
                                value="{{ $penilaian ? (int)$penilaian->{$k['key']} : 0 }}"
                                oninput="hitungAkumulasi()"
                                style="width:68px;padding:8px 10px;
                                    border:1px solid #E5E7EB;border-radius:8px;
                                    font-size:0.95rem;font-weight:700;
                                    text-align:center;outline:none;
                                    color:#374151;background:#fff;
                                    font-family:'Hanken Grotesk',sans-serif;">
                        </div>
                        <div id="err-{{ $k['key'] }}" style="display:none;font-size:0.72rem;color:#dc2626;font-weight:600;">
                            Maksimal {{ $k['maks'] }} poin
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- AKUMULASI --}}
            <div style="margin-top:16px;padding:16px 20px;background:#FFFBEB;border-radius:12px;
                        border:1px solid #FDE68A;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <div style="font-size:0.8rem;font-weight:700;color:#92741A;">Akumulasi Nilai</div>
                    <div style="font-size:0.72rem;color:#9ca3af;margin-top:2px;">
                        Nilai otomatis dijumlahkan berdasarkan input di atas
                    </div>
                </div>
                <div style="display:flex;align-items:baseline;gap:6px;">
                    <div id="akumulasiPreview" style="font-size:2rem;font-weight:800;color:#735C00;line-height:1;">
                        {{ $penilaian ? (int)($penilaian->nilai_teknik_presentasi + $penilaian->nilai_dokumentasi + $penilaian->nilai_pemahaman_teori + $penilaian->nilai_pemahaman_kebutuhan) : 0 }}
                    </div>
                    <div style="font-size:0.85rem;color:#9ca3af;">/ 100</div>
                </div>
            </div>
        </div>

        {{-- KELAYAKAN --}}
        <div style="background:#fff;border-radius:16px;padding:28px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);
                    margin-bottom:20px;">
            <div class="row g-3">

                {{-- Layak --}}
                <div class="col-md-6">
                    <input type="radio" name="kelayakan" value="layak"
                        {{ ($penilaian && $penilaian->kelayakan === 'layak') ? 'checked' : '' }}
                        style="display:none;" id="radio-layak">
                    <div id="card-layak" onclick="pilihKelayakan('layak')" style="
                        padding:20px;border-radius:12px;cursor:pointer;
                        border:2px solid {{ ($penilaian && $penilaian->kelayakan === 'layak') ? '#16a34a' : '#E5E7EB' }};
                        background:{{ ($penilaian && $penilaian->kelayakan === 'layak') ? '#F0FDF4' : '#fff' }};
                        transition:all 0.2s;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                            <i id="icon-layak" class="fa fa-circle-check" style="font-size:1.4rem;
                                color:{{ ($penilaian && $penilaian->kelayakan === 'layak') ? '#16a34a' : '#D1D5DB' }};"></i>
                            <div id="dot-layak" style="
                                width:20px;height:20px;border-radius:50%;
                                border:2px solid {{ ($penilaian && $penilaian->kelayakan === 'layak') ? '#16a34a' : '#D1D5DB' }};
                                background:#fff;display:flex;align-items:center;justify-content:center;">
                                <div id="fill-layak" style="width:10px;height:10px;border-radius:50%;
                                    background:#16a34a;
                                    display:{{ ($penilaian && $penilaian->kelayakan === 'layak') ? 'block' : 'none' }};"></div>
                            </div>
                        </div>
                        <div style="font-size:0.95rem;font-weight:700;color:#111827;margin-bottom:4px;">
                            Layak Melanjutkan TA-2
                        </div>
                        <div style="font-size:0.75rem;color:#9ca3af;line-height:1.4;">
                            Mahasiswa dinyatakan memenuhi kelayakan untuk melanjutkan ke tahap tugas akhir berikutnya.
                        </div>
                    </div>
                </div>

                {{-- Tidak Layak --}}
                <div class="col-md-6">
                    <input type="radio" name="kelayakan" value="tidak_layak"
                        {{ ($penilaian && $penilaian->kelayakan === 'tidak_layak') ? 'checked' : '' }}
                        style="display:none;" id="radio-tidak">
                    <div id="card-tidak" onclick="pilihKelayakan('tidak_layak')" style="
                        padding:20px;border-radius:12px;cursor:pointer;
                        border:2px solid {{ ($penilaian && $penilaian->kelayakan === 'tidak_layak') ? '#dc2626' : '#E5E7EB' }};
                        background:{{ ($penilaian && $penilaian->kelayakan === 'tidak_layak') ? '#FEF2F2' : '#fff' }};
                        transition:all 0.2s;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                            <i id="icon-tidak" class="fa fa-circle-xmark" style="font-size:1.4rem;
                                color:{{ ($penilaian && $penilaian->kelayakan === 'tidak_layak') ? '#dc2626' : '#D1D5DB' }};"></i>
                            <div id="dot-tidak" style="
                                width:20px;height:20px;border-radius:50%;
                                border:2px solid {{ ($penilaian && $penilaian->kelayakan === 'tidak_layak') ? '#dc2626' : '#D1D5DB' }};
                                background:#fff;display:flex;align-items:center;justify-content:center;">
                                <div id="fill-tidak" style="width:10px;height:10px;border-radius:50%;
                                    background:#dc2626;
                                    display:{{ ($penilaian && $penilaian->kelayakan === 'tidak_layak') ? 'block' : 'none' }};"></div>
                            </div>
                        </div>
                        <div style="font-size:0.95rem;font-weight:700;color:#111827;margin-bottom:4px;">
                            Belum Layak Melanjutkan TA-2
                        </div>
                        <div style="font-size:0.75rem;color:#9ca3af;line-height:1.4;">
                            Mahasiswa belum memenuhi kelayakan untuk melanjutkan ke tahap tugas akhir berikutnya.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- CATATAN --}}
        <div style="background:#fff;border-radius:16px;padding:28px;
                    border:1px solid #f0f0f0;box-shadow:0 1px 6px rgba(0,0,0,0.05);
                    margin-bottom:24px;">
            {{-- ✅ DIFIX: warna label header #574500 --}}
            <div style="font-size:0.75rem;font-weight:700;color:#574500;
                        text-transform:uppercase;letter-spacing:1px;
                        margin-bottom:12px;
                        border-left:3px solid #FACC15;padding-left:10px;">
                Catatan Tambahan
            </div>
            <textarea name="catatan" rows="4"
                placeholder="Tuliskan masukan atau catatan perbaikan untuk mahasiswa..."
                style="width:100%;padding:12px 14px;border:1px solid #E5E7EB;border-radius:10px;
                    font-size:0.87rem;font-family:'Hanken Grotesk',sans-serif;
                    outline:none;resize:vertical;color:#374151;
                    line-height:1.6;background:#FAFAFA;">{{ $penilaian->catatan ?? '' }}</textarea>
        </div>

        {{-- TOMBOL --}}
        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap;">
            <button type="submit" name="aksi" value="draft"
                style="padding:12px 28px;border-radius:12px;
                    border:2px solid #E5E7EB;background:#fff;
                    color:#374151;font-size:0.9rem;font-weight:700;
                    cursor:pointer;font-family:'Hanken Grotesk',sans-serif;
                    display:flex;align-items:center;gap:8px;">
                <i class="fa fa-floppy-disk"></i> Simpan Draft
            </button>
            <button type="button" onclick="konfirmasiSubmit()"
                style="padding:12px 32px;border-radius:12px;
                    border:none;background:#FACC15;color:#735C00;
                    font-size:0.9rem;font-weight:700;cursor:pointer;
                    font-family:'Hanken Grotesk',sans-serif;
                    display:flex;align-items:center;gap:8px;">
                <i class="fa fa-paper-plane"></i> Submit Penilaian
            </button>
        </div>

        <input type="hidden" name="aksi" id="hiddenAksi" value="draft">

    </form>
</div>

<script>
function hitungAkumulasi() {
    const keys = ['nilai_teknik_presentasi','nilai_dokumentasi','nilai_pemahaman_teori','nilai_pemahaman_kebutuhan'];
    const maks = {nilai_teknik_presentasi:15,nilai_dokumentasi:20,nilai_pemahaman_teori:30,nilai_pemahaman_kebutuhan:35};
    let total = 0;
    keys.forEach(k => {
        const inp = document.getElementById('inp-' + k);
        const errEl = document.getElementById('err-' + k);
        let val = parseInt(inp.value) || 0;
        if (val > maks[k]) {
            inp.style.border = '1.5px solid #dc2626';
            errEl.style.display = 'block';
            val = maks[k];
            inp.value = val;
        } else if (val < 0) {
            val = 0;
            inp.value = val;
            inp.style.border = '1px solid #E5E7EB';
            errEl.style.display = 'none';
        } else {
            inp.style.border = '1px solid #E5E7EB';
            errEl.style.display = 'none';
        }
        total += val;
    });
    const el = document.getElementById('akumulasiPreview');
    el.innerText = total;
    el.style.color = total >= 80 ? '#16a34a' : (total >= 60 ? '#735C00' : '#dc2626');
}

function pilihKelayakan(val) {
    const isLayak = val === 'layak';

    document.getElementById('radio-layak').checked = isLayak;
    document.getElementById('radio-tidak').checked = !isLayak;

    const cL = document.getElementById('card-layak');
    cL.style.border    = isLayak ? '2px solid #16a34a' : '2px solid #E5E7EB';
    cL.style.background = isLayak ? '#F0FDF4' : '#fff';
    document.getElementById('icon-layak').style.color       = isLayak ? '#16a34a' : '#D1D5DB';
    document.getElementById('dot-layak').style.border       = isLayak ? '2px solid #16a34a' : '2px solid #D1D5DB';
    document.getElementById('fill-layak').style.display     = isLayak ? 'block' : 'none';

    const cT = document.getElementById('card-tidak');
    cT.style.border    = !isLayak ? '2px solid #dc2626' : '2px solid #E5E7EB';
    cT.style.background = !isLayak ? '#FEF2F2' : '#fff';
    document.getElementById('icon-tidak').style.color       = !isLayak ? '#dc2626' : '#D1D5DB';
    document.getElementById('dot-tidak').style.border       = !isLayak ? '2px solid #dc2626' : '2px solid #D1D5DB';
    document.getElementById('fill-tidak').style.display     = !isLayak ? 'block' : 'none';
}

function konfirmasiSubmit() {
    const kelayakan = document.querySelector('input[name="kelayakan"]:checked');
    if (!kelayakan) {
        Swal.fire({
            icon: 'warning',
            title: 'Kelayakan belum dipilih!',
            text: 'Silakan pilih status kelayakan mahasiswa terlebih dahulu.',
            confirmButtonColor: '#FACC15',
        });
        return;
    }
    const nilai = document.getElementById('akumulasiPreview').innerText;
    Swal.fire({
        title: 'Submit Penilaian?',
        html: `Nilai akhir mahasiswa ini adalah <strong style="color:#735C00;font-size:1.2rem;">${nilai}</strong>.<br><br>
            <span style="color:#6b7280;font-size:0.88rem;">Penilaian yang sudah disubmit masih dapat diedit selama periode seminar berlangsung.</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FACC15',
        cancelButtonColor: '#d1d5db',
        confirmButtonText: '<span style="color:#735C00;font-weight:700;">Ya, Submit</span>',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (result.isConfirmed) {
            document.querySelectorAll('button[name="aksi"]').forEach(b => b.remove());
            document.getElementById('hiddenAksi').value = 'submitted';
            document.getElementById('formPenilaian').submit();
        }
    });
}

hitungAkumulasi();
</script>

@endsection