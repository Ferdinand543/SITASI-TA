#!/usr/bin/env python3
"""
scripts/generate_surat.py
Taruh di: {laravel_root}/scripts/generate_surat.py
Install: pip install reportlab qrcode Pillow
"""

import sys, json, io, os
from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.pdfgen import canvas
from reportlab.lib.styles import ParagraphStyle
from reportlab.platypus import Paragraph
from reportlab.lib.enums import TA_JUSTIFY
from reportlab.lib import colors
from reportlab.lib.utils import ImageReader
import qrcode

W, H = A4
ML = 18*mm; MR = 18*mm; MT = 20*mm; MB = 18*mm
body_w = W - ML - MR


def make_qr(url):
    """Generate QR code image dari URL, return ImageReader."""
    qr = qrcode.QRCode(
        version=2,
        error_correction=qrcode.constants.ERROR_CORRECT_M,
        box_size=4,
        border=1
    )
    qr.add_data(url)
    qr.make(fit=True)
    img = qr.make_image(fill_color="black", back_color="white")
    buf = io.BytesIO()
    img.save(buf, format="PNG")
    buf.seek(0)
    return ImageReader(buf)


def sf(c, sz, bold=False):
    """Set font Times-Roman atau Times-Bold."""
    c.setFont("Times-Bold" if bold else "Times-Roman", sz)


def para(c, txt, x, y, w, style):
    """Render Paragraph dan return tinggi yang dipakai."""
    p = Paragraph(txt, style)
    pw, ph = p.wrap(w, 9999)
    p.drawOn(c, x, y - ph)
    return ph


def resolve_bool(val):
    """
    FIX UTAMA: Terima boolean native (True/False dari JSON)
    maupun string 'true'/'false' untuk kompatibilitas.
    """
    if isinstance(val, bool):
        return val
    if isinstance(val, str):
        return val.lower() == 'true'
    return bool(val)


def generate(d):
    c = canvas.Canvas(d['output_path'], pagesize=A4)
    logo_sz = 22*mm
    logo_y  = H - MT - logo_sz

    # ── Logo kiri ──
    if d.get('logo_kartika') and os.path.exists(d['logo_kartika']):
        c.drawImage(d['logo_kartika'], ML, logo_y,
                    width=logo_sz, height=logo_sz,
                    preserveAspectRatio=True, mask='auto')
    else:
        c.setLineWidth(0.5)
        c.rect(ML, logo_y, logo_sz, logo_sz)
        sf(c, 7)
        c.drawCentredString(ML + logo_sz / 2, logo_y + logo_sz / 2 - 3, "LOGO KARTIKA")

    # ── Logo kanan ──
    rx = W - MR - logo_sz
    if d.get('logo_unjani') and os.path.exists(d['logo_unjani']):
        c.drawImage(d['logo_unjani'], rx, logo_y,
                    width=logo_sz, height=logo_sz,
                    preserveAspectRatio=True, mask='auto')
    else:
        c.setLineWidth(0.5)
        c.rect(rx, logo_y, logo_sz, logo_sz)
        sf(c, 7)
        c.drawCentredString(rx + logo_sz / 2, logo_y + logo_sz / 2 - 3, "LOGO UNJANI")

    # ── Teks institusi (tengah antara dua logo) ──
    inst_cx = ML + logo_sz + 3*mm + (body_w - 2*(logo_sz + 3*mm)) / 2
    lines = [
        ("YAYASAN KARTIKA EKA PAKSI (YKEP)",                                              9,  True,  12),
        ("UNIVERSITAS JENDERAL ACHMAD YANI (UNJANI)",                                     9,  True,  12),
        ("FAKULTAS SAINS DAN INFORMATIKA (FSI)",                                          9,  True,  12),
        ("PROGRAM STUDI S1 SISTEM INFORMASI",                                             11, True,  14),
        ("Kampus Cimahi : Jl. Terusan Jenderal Sudirman PO. BOX 148 Telp. (022) 6631556 Fax. (022) 6631556",
                                                                                          7,  False,  9),
    ]
    total_h = sum(lh for *_, lh in lines)
    cy = logo_y + logo_sz / 2 + total_h / 2
    for txt, sz, bold, lh in lines:
        sf(c, sz, bold)
        c.drawCentredString(inst_cx, cy, txt)
        cy -= lh

    # ── Garis header ──
    line_y = logo_y - 3*mm
    c.setLineWidth(2.5)
    c.line(ML, line_y, W - MR, line_y)

    # ── Judul surat ──
    cy = line_y - 10*mm
    sf(c, 13, True)
    c.drawCentredString(W / 2, cy, "SURAT PERNYATAAN DOSEN PEMBIMBING \u00bd")
    cy -= 7*mm
    c.drawCentredString(W / 2, cy, "MAHASISWA TUGAS AKHIR ( TA ) \u2013 1")

    st_j = ParagraphStyle(
        "j",
        fontName="Times-Roman",
        fontSize=11,
        leading=15,
        alignment=TA_JUSTIFY
    )

    # ── Kalimat pembuka ──
    cy -= 10*mm
    ph = para(c,
              ("KAMI YANG BERTANDATANGAN DI BAWAH INI SEBAGAI DOSEN PEMBIMING \u00bd "
               "MAHASISWA PESERTA PENELITIAN TUGAS AKHIR \u2013 1, BERIKUT DI BAWAH INI :"),
              ML, cy, body_w, st_j)
    cy -= ph + 5*mm

    # ── Tabel data mahasiswa ──
    col1 = 43*mm; col2 = 6*mm; col3 = body_w - col1 - col2
    rh   = 5.5*mm

    def row(lbl, val, y):
        sf(c, 11)
        c.drawString(ML, y, lbl)
        c.drawString(ML + col1, y, ":")
        c.drawString(ML + col1 + col2, y, val)

    row("NAMA MAHASISWA",
        f"{d['nama_mhs']}   NIM : {d['nim']}", cy)
    cy -= rh
    row("",
        f"NILAI IPK : {d['ipk']} / SKS : {d['sks']}   NILAI D/E : {d['nilai_d']} / {d['nilai_e']}", cy)
    cy -= rh
    row("", f"NO. KONTAK : {d['kontak']}", cy)
    cy -= rh + 1*mm

    # Judul TA (bisa multi-baris)
    sf(c, 11)
    c.drawString(ML, cy, "JUDUL PENELITIAN")
    c.drawString(ML + col1, cy, ":")
    p_jd = Paragraph(
        d['judul'],
        ParagraphStyle("jd", fontName="Times-Roman", fontSize=11, leading=14, alignment=TA_JUSTIFY)
    )
    pw, ph = p_jd.wrap(col3, 9999)
    p_jd.drawOn(c, ML + col1 + col2, cy - ph + 12)
    cy -= max(ph + 2*mm, 2*rh)

    # ── Badan surat ──
    cy -= 4*mm
    ph = para(c,
              ("DENGAN INI MENERANGKAN, BAHWA BERDASARKAN PENILAIAN TERHADAP HASIL PROSES "
               "BIMBINGAN YANG TELAH DILAKUKAN DAN ISI LAPORAN PENELITIAN TUGAS AKHIR, MAKA "
               "MATERI/LAPORAN TA1 MAHASISWA PESERTA TERSEBUT DI ATAS, KAMI NYATAKAN"),
              ML, cy, body_w, st_j)
    cy -= ph + 4*mm

    # ── Box pernyataan ──
    box_h = 16*mm
    c.setLineWidth(1.5)
    c.rect(ML, cy - box_h, body_w, box_h)
    sf(c, 11, True)
    c.drawCentredString(W / 2, cy - 6*mm,
                        "TELAH MEMENUHI PERSYARATAN DAN LAYAK UNTUK DI SEMINARKAN")
    c.drawCentredString(W / 2, cy - 12*mm,
                        f"PADA SEMINAR TUGAS AKHIR \u2013 1 TAHUN AKADEMIK : {d['tahun_akademik']}")
    cy -= box_h + 5*mm

    # ── Kalimat penutup ──
    ph = para(c,
              ("DEMIKIAN SURAT PERSYARATAN INI KAMI BUAT UNTUK DIPERGUNAKAN SEBAGAIMANA "
               "MESTINYA. ATAS SEGALA PERHATIAN DAN KERJASAMANYA DIUCAPKAN TERIMA KASIH."),
              ML, cy, body_w, st_j)
    cy -= ph + 6*mm

    # ── Tanggal ──
    sf(c, 11)
    c.drawRightString(W - MR, cy, f"Cimahi, {d['tanggal_surat']}")
    cy -= 6*mm

    # ── Area tanda tangan ──
    cw  = body_w / 2
    lcx = ML + cw / 2          # tengah kolom kiri  → Dosen Pembimbing 2
    rcx = ML + cw + cw / 2     # tengah kolom kanan → Dosen Pembimbing 1

    sf(c, 11)
    c.drawCentredString(lcx, cy, "Mengetahui,")
    cy -= 4*mm
    c.drawCentredString(lcx, cy, "Dosen Pembimbing 2")
    c.drawCentredString(rcx, cy, "Dosen Pembimbing 1")
    cy -= 4*mm

    # ── FIX: Resolve show_qr sebagai boolean (handle True/False maupun 'true'/'false') ──
    show1 = resolve_bool(d.get('show_qr1', False))
    show2 = resolve_bool(d.get('show_qr2', False))

    qr_sz = 24*mm
    qr_y  = cy - qr_sz

    # DP2 → kolom kiri (lcx)
    if show2 and d.get('qr2_url'):
        try:
            c.drawImage(make_qr(d['qr2_url']),
                        lcx - qr_sz / 2, qr_y,
                        width=qr_sz, height=qr_sz, mask='auto')
        except Exception as e:
            print(f"[WARN] Gagal generate QR dospem2: {e}", file=sys.stderr)

    # DP1 → kolom kanan (rcx)
    if show1 and d.get('qr1_url'):
        try:
            c.drawImage(make_qr(d['qr1_url']),
                        rcx - qr_sz / 2, qr_y,
                        width=qr_sz, height=qr_sz, mask='auto')
        except Exception as e:
            print(f"[WARN] Gagal generate QR dospem1: {e}", file=sys.stderr)

    cy = qr_y - 3*mm

    # ── Nama & garis TTD ──
    lw = 55*mm
    c.setLineWidth(0.8)

    # Kolom kiri — DP2
    c.line(lcx - lw / 2, cy, lcx + lw / 2, cy)
    sf(c, 11, True)
    c.drawCentredString(lcx, cy - 4*mm,  d['nama_dosen2'])
    sf(c, 11)
    c.drawCentredString(lcx, cy - 9*mm,  f"NID. {d['nid_dosen2']}")
    if not show2:
        sf(c, 9)
        c.setFillColor(colors.grey)
        c.drawCentredString(lcx, cy - 14*mm, "Menunggu tanda tangan")
        c.setFillColor(colors.black)

    # Kolom kanan — DP1
    c.line(rcx - lw / 2, cy, rcx + lw / 2, cy)
    sf(c, 11, True)
    c.drawCentredString(rcx, cy - 4*mm,  d['nama_dosen1'])
    sf(c, 11)
    c.drawCentredString(rcx, cy - 9*mm,  f"NID. {d['nid_dosen1']}")
    if not show1:
        sf(c, 9)
        c.setFillColor(colors.grey)
        c.drawCentredString(rcx, cy - 14*mm, "Menunggu tanda tangan")
        c.setFillColor(colors.black)

    cy -= 15*mm

    # ── Catatan ──
    st_cat = ParagraphStyle(
        "cat",
        fontName="Times-Bold",
        fontSize=8.5,
        leading=12,
        alignment=TA_JUSTIFY
    )
    p_cat = Paragraph(
        ("CATT.: KEPADA DOSEN PEMBIMBING HARAP UNTUK TIDAK MENANDATANGANI SURAT INI APABILA "
         "MAHASISWA TIDAK/BELUM MENYRAHKAN DRAFT LAPORAN PENELITIAN YANG AKAN DISEMINARKAN. "
         "TERIMAKASIH"),
        st_cat
    )
    pw, ph = p_cat.wrap(body_w - 8, 9999)
    cat_h  = ph + 8
    c.setLineWidth(1)
    c.rect(ML, cy - cat_h, body_w, cat_h)
    p_cat.drawOn(c, ML + 4, cy - cat_h + 4)

    c.save()
    print("OK")


if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python3 generate_surat.py <path_to_json>", file=sys.stderr)
        sys.exit(1)

    json_path = sys.argv[1]
    if not os.path.exists(json_path):
        print(f"ERROR: File tidak ditemukan: {json_path}", file=sys.stderr)
        sys.exit(1)

    with open(json_path, 'r', encoding='utf-8') as f:
        data = json.load(f)

    generate(data)

    