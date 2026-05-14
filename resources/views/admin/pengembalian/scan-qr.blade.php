@extends('layouts.admin')

@section('title', 'Scan QR Pengembalian')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Scan QR Pengembalian</h1>
    </div>
</div>

<div class="content">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10">

    <div class="card card-primary card-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold">
                <i class="fas fa-qrcode me-2"></i>Scan QR Code Pengembalian
            </h3>
            <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-list me-1"></i>Daftar Pengembalian
            </a>
        </div>
        <div class="card-body">

            {{-- ── AREA KAMERA ── --}}
            <div style="max-width:500px;margin:0 auto;">

                {{-- Kotak video --}}
                <div id="scanner-wrap" style="
                    position:relative; width:100%; padding-top:75%;
                    background:#1a1a2e; border-radius:16px;
                    overflow:hidden; border:3px solid #0d6efd;">

                    <video id="qr-video" playsinline muted autoplay style="
                        position:absolute;inset:0;
                        width:100%;height:100%;
                        object-fit:cover;display:none;"></video>

                    {{-- Overlay saat kamera belum/gagal aktif --}}
                    <div id="scan-overlay" style="
                        position:absolute;inset:0;
                        display:flex;flex-direction:column;
                        align-items:center;justify-content:center;
                        background:rgba(10,15,40,0.85);">

                        {{-- Sudut finder --}}
                        <div style="position:relative;width:190px;height:190px;">
                            <div style="position:absolute;inset:0;border:2px solid rgba(255,255,255,0.1);border-radius:8px;"></div>
                            <div style="position:absolute;top:0;left:0;width:26px;height:26px;border-top:4px solid #0d6efd;border-left:4px solid #0d6efd;border-radius:5px 0 0 0;"></div>
                            <div style="position:absolute;top:0;right:0;width:26px;height:26px;border-top:4px solid #0d6efd;border-right:4px solid #0d6efd;border-radius:0 5px 0 0;"></div>
                            <div style="position:absolute;bottom:0;left:0;width:26px;height:26px;border-bottom:4px solid #0d6efd;border-left:4px solid #0d6efd;border-radius:0 0 0 5px;"></div>
                            <div style="position:absolute;bottom:0;right:0;width:26px;height:26px;border-bottom:4px solid #0d6efd;border-right:4px solid #0d6efd;border-radius:0 0 5px 0;"></div>
                            {{-- Garis laser --}}
                            <div id="laser-line" style="
                                position:absolute;left:4px;right:4px;height:2px;
                                background:linear-gradient(90deg,transparent 0%,#0d6efd 30%,#60a5fa 50%,#0d6efd 70%,transparent 100%);
                                box-shadow:0 0 8px #0d6efd;
                                top:0;animation:laserScan 2s ease-in-out infinite;"></div>
                        </div>

                        <p id="overlay-text" style="color:rgba(255,255,255,0.8);margin-top:18px;font-size:13px;text-align:center;padding:0 20px;line-height:1.5;">
                            Memuat kamera...
                        </p>
                    </div>

                    {{-- Canvas tersembunyi --}}
                    <canvas id="qr-canvas" style="display:none;"></canvas>
                </div>

                {{-- Status bar --}}
                <div id="scan-status" class="alert alert-info mt-3 mb-0 d-flex align-items-start gap-2" style="border-radius:10px;font-size:13px;">
                    <i class="fas fa-circle-notch fa-spin fa-fw mt-1"></i>
                    <span>Menginisialisasi kamera...</span>
                </div>

                {{-- Tombol retry --}}
                <div class="text-center mt-2" id="retry-wrap" style="display:none!important;">
                    <button onclick="startCamera()" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-redo me-1"></i>Coba Lagi
                    </button>
                </div>
            </div>

            {{-- ── INPUT MANUAL ── --}}
            <div class="mt-4 pt-3 border-top">
                <label class="fw-bold mb-2 d-block" style="font-size:14px;">
                    <i class="fas fa-keyboard me-1 text-muted"></i>Input Booking ID Manual
                </label>
                <div class="d-flex gap-2" style="max-width:480px;">
                    <input type="text" id="manual-booking-id" class="form-control"
                           placeholder="Contoh: PJM-AS8MAYFP"
                           style="font-family:monospace;letter-spacing:0.5px;"
                           onkeydown="if(event.key==='Enter'){ event.preventDefault(); handleManualInput(); }">
                    <button class="btn btn-primary px-4" type="button" onclick="handleManualInput()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <small class="text-muted mt-1 d-block">Masukkan Booking ID lalu tekan Enter atau klik 🔍</small>
            </div>

        </div>
    </div>

</div>
</div>
</div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL DETAIL PEMINJAMAN & PENGEMBALIAN
═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalPinjaman" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">

            {{-- Header --}}
            <div class="modal-header border-0 text-white" style="background:linear-gradient(135deg,#1a3c6b,#0f2444);padding:18px 24px;">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="fas fa-book-open me-2"></i>Detail Peminjaman & Pengembalian
                    </h5>
                    <div id="modal-booking-label" style="font-size:11px;opacity:0.6;margin-top:4px;font-family:monospace;letter-spacing:0.5px;">—</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="onModalClose()"></button>
            </div>

            <div class="modal-body p-0">
                <input type="hidden" id="pinjaman-id">

                {{-- ══ BARIS 1: Mahasiswa | Buku ══ --}}
                <div class="row g-0" style="border-bottom:1px solid #f0f0f0;">

                    {{-- Mahasiswa --}}
                    <div class="col-md-6 p-4" style="border-right:1px solid #f0f0f0;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:32px;height:32px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-user-graduate text-primary" style="font-size:13px;"></i>
                            </div>
                            <span class="fw-bold" style="color:#1d4ed8;font-size:13px;">Data Mahasiswa</span>
                        </div>
                        <table style="width:100%;border-collapse:collapse;font-size:13px;">
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;width:38%;vertical-align:top;">Nama</td>
                                <td style="font-weight:600;padding:5px 0;" id="mhs-nama">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">NIM</td>
                                <td style="font-family:monospace;padding:5px 0;" id="mhs-nim">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Jurusan</td>
                                <td style="padding:5px 0;" id="mhs-jurusan">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Telepon</td>
                                <td style="padding:5px 0;" id="mhs-telp">—</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Buku --}}
                    <div class="col-md-6 p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:32px;height:32px;background:#d1fae5;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-book text-success" style="font-size:13px;"></i>
                            </div>
                            <span class="fw-bold" style="color:#065f46;font-size:13px;">Data Buku</span>
                        </div>
                        <table style="width:100%;border-collapse:collapse;font-size:13px;">
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;width:38%;vertical-align:top;">Judul</td>
                                <td style="font-weight:600;padding:5px 0;" id="buku-judul">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Pengarang</td>
                                <td style="padding:5px 0;" id="buku-pengarang">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Penerbit</td>
                                <td style="padding:5px 0;" id="buku-penerbit">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Kategori</td>
                                <td style="padding:5px 0;" id="buku-kategori">—</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- ══ BARIS 2: Info Pinjaman | Kalkulasi Denda ══ --}}
                <div class="row g-0">

                    {{-- Info Pinjaman --}}
                    <div class="col-md-5 p-4" style="border-right:1px solid #f0f0f0;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:32px;height:32px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-calendar-alt" style="font-size:13px;color:#d97706;"></i>
                            </div>
                            <span class="fw-bold" style="color:#92400e;font-size:13px;">Info Peminjaman</span>
                        </div>
                        <table style="width:100%;border-collapse:collapse;font-size:13px;">
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;width:48%;">Booking ID</td>
                                <td style="font-family:monospace;font-weight:700;color:#1a3c6b;font-size:11px;padding:5px 0;" id="detail-booking-id">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Tgl Pinjam</td>
                                <td style="padding:5px 0;" id="detail-tanggal-pinjam">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Batas Kembali</td>
                                <td style="font-weight:600;padding:5px 0;" id="detail-tanggal-batas">—</td>
                            </tr>
                            <tr>
                                <td style="color:#9ca3af;padding:5px 0;">Status</td>
                                <td style="padding:5px 0;" id="pinjaman-status-badge">—</td>
                            </tr>
                        </table>
                    </div>

                    {{-- ══ KALKULASI DENDA ══ --}}
                    <div class="col-md-7 p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:32px;height:32px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-money-bill-wave" style="font-size:13px;color:#dc2626;"></i>
                            </div>
                            <span class="fw-bold" style="color:#991b1b;font-size:13px;">Kalkulasi Denda</span>
                        </div>

                        {{-- Tidak ada denda --}}
                        <div id="denda-ok" style="display:none;">
                            <div style="background:#d1fae5;border:1.5px solid #6ee7b7;border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;">
                                <i class="fas fa-check-circle text-success fa-xl"></i>
                                <div>
                                    <div style="font-weight:700;color:#065f46;font-size:13px;">Tidak Ada Denda</div>
                                    <div style="font-size:12px;color:#059669;margin-top:2px;">Buku dikembalikan tepat waktu ✓</div>
                                </div>
                            </div>
                        </div>

                        {{-- Ada denda --}}
                        <div id="denda-detail" style="display:none;">

                            {{-- Warning banner --}}
                            <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-radius:10px;padding:12px 14px;margin-bottom:12px;">
                                <div style="font-weight:700;color:#991b1b;font-size:13px;margin-bottom:4px;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Keterlambatan Terdeteksi!
                                </div>
                                <div style="font-size:12px;color:#7f1d1d;">
                                    Mahasiswa wajib membayar denda sebelum pengembalian diproses.
                                </div>
                            </div>

                            {{-- Tabel rincian --}}
                            <div style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;font-size:13px;">
                                <table style="width:100%;border-collapse:collapse;">
                                    <tbody>
                                        <tr style="background:#fafafa;">
                                            <td style="padding:9px 14px;color:#6b7280;border-bottom:1px solid #f3f4f6;">Batas Kembali</td>
                                            <td style="padding:9px 14px;text-align:right;font-weight:600;border-bottom:1px solid #f3f4f6;" id="denda-batas-tgl">—</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:9px 14px;color:#6b7280;border-bottom:1px solid #f3f4f6;">Tanggal Hitung</td>
                                            <td style="padding:9px 14px;text-align:right;border-bottom:1px solid #f3f4f6;" id="denda-hitung-tgl">—</td>
                                        </tr>
                                        <tr style="background:#fafafa;">
                                            <td style="padding:9px 14px;color:#6b7280;border-bottom:1px solid #f3f4f6;">Hari Terlambat</td>
                                            <td style="padding:9px 14px;text-align:right;font-weight:700;color:#dc2626;border-bottom:1px solid #f3f4f6;" id="denda-hari-val">—</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:9px 14px;color:#6b7280;border-bottom:1px solid #f3f4f6;">Tarif / hari</td>
                                            <td style="padding:9px 14px;text-align:right;border-bottom:1px solid #f3f4f6;">Rp 10.000</td>
                                        </tr>
                                        <tr style="background:#fff1f2;">
                                            <td style="padding:11px 14px;font-weight:700;color:#991b1b;font-size:14px;">Total Denda</td>
                                            <td style="padding:11px 14px;text-align:right;font-weight:700;color:#dc2626;font-size:16px;" id="denda-total-val">—</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Rincian per hari (collapsible) --}}
                            <div class="mt-2">
                                <button class="btn btn-link btn-sm p-0 text-muted text-decoration-none" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#dendaBreakdown" style="font-size:12px;">
                                    <i class="fas fa-chevron-down me-1"></i>Lihat rincian per hari
                                </button>
                                <div class="collapse" id="dendaBreakdown">
                                    <div id="denda-breakdown-list" style="
                                        margin-top:8px;max-height:130px;overflow-y:auto;
                                        background:#1e1e1e;color:#4ade80;
                                        font-family:monospace;font-size:11.5px;
                                        border-radius:8px;padding:10px 14px;
                                        line-height:1.8;white-space:pre;"></div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /col denda --}}
                </div>{{-- /row --}}

                {{-- ── NOTE STATUS ── --}}
                <div id="detail-note-wrap" class="px-4 pb-3" style="display:none;">
                    <div id="detail-note-inner" class="alert mb-0" style="font-size:13px;border-radius:10px;"></div>
                </div>

            </div>{{-- /modal-body --}}

            <div class="modal-footer border-top" style="padding:14px 20px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="onModalClose()">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-success fw-bold" id="btn-return" onclick="prosesPengembalian()">
                    <i class="fas fa-check me-1"></i>Proses Pengembalian
                </button>
            </div>

        </div>
    </div>
</div>

<style>
@keyframes laserScan {
    0%   { top:4px;   opacity:1; }
    50%  { top:calc(100% - 6px); opacity:0.7; }
    100% { top:4px;   opacity:1; }
}
</style>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
/* ════════════════════════════════════
   KONSTANTA & STATE
════════════════════════════════════ */
const DENDA_PER_HARI = 10000;
let scanActive          = false;
let videoStream         = null;
let rafId               = null;
let currentPeminjamanId = null;

/* ════════════════════════════════════
   HELPERS UI
════════════════════════════════════ */
function setStatus(type, html) {
    const el = document.getElementById('scan-status');
    el.className = `alert alert-${type} mt-3 mb-0 d-flex align-items-start gap-2`;
    el.style.borderRadius = '10px';
    el.style.fontSize = '13px';
    el.innerHTML = html;
}

function setOverlay(msg) {
    const el = document.getElementById('overlay-text');
    if (el) el.textContent = msg;
}

function fmtRupiah(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

/**
 * Ekstrak Booking ID dari berbagai format:
 * - URL penuh : http://127.0.0.1:8000/pinjam/konfirmasi/PJM-AS8MAYFP
 * - Teks biasa: Booking ID: PJM-AS8MAYFP
 * - Sudah bersih: PJM-AS8MAYFP
 */
function cleanBookingId(raw) {
    raw = raw.trim();

    // Jika berisi URL, ambil segment terakhir
    try {
        const url = new URL(raw);
        const segments = url.pathname.split('/').filter(s => s.length > 0);
        if (segments.length > 0) {
            return segments[segments.length - 1].trim();
        }
    } catch (_) {
        // Bukan URL, lanjut ke pengecekan berikutnya
    }

    // Jika ada prefix "Booking ID: "
    raw = raw.replace(/^Booking\s*ID\s*[:\-]\s*/i, '').trim();

    return raw;
}

/**
 * Parse tanggal dari berbagai format yang Laravel kirim:
 *  - "2026-04-22"          (Y-m-d)
 *  - "22/04/2026"          (d/m/Y)
 *  - "2026-04-22 00:00:00" (Y-m-d H:i:s)
 *  - "22 Apr 2026"         (d M Y)
 */
function parseDate(str) {
    if (!str || str === '-') return null;
    str = str.trim();

    // dd/mm/yyyy
    const dmy = str.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})/);
    if (dmy) return new Date(+dmy[3], +dmy[2] - 1, +dmy[1]);

    // yyyy-mm-dd (dengan atau tanpa jam)
    const ymd = str.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (ymd) return new Date(+ymd[1], +ymd[2] - 1, +ymd[3]);

    // fallback
    const d = new Date(str);
    return isNaN(d) ? null : d;
}

function startOfDay(d) {
    if (!d) return null;
    return new Date(d.getFullYear(), d.getMonth(), d.getDate());
}

function tglIndonesia(d) {
    if (!d) return '—';
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
}

/* ════════════════════════════════════
   KALKULASI DENDA
════════════════════════════════════ */
function hitungDenda(tanggalBatasStr) {
    const batas = startOfDay(parseDate(tanggalBatasStr));
    const hari  = startOfDay(new Date());
    if (!batas) return 0;
    const diff = Math.floor((hari - batas) / 86400000);
    return diff > 0 ? diff : 0;
}

function renderDenda(hariTerlambat, tanggalBatasStr) {
    const elOk     = document.getElementById('denda-ok');
    const elDetail = document.getElementById('denda-detail');

    if (hariTerlambat <= 0) {
        elOk.style.display     = 'block';
        elDetail.style.display = 'none';
        return;
    }

    elOk.style.display     = 'none';
    elDetail.style.display = 'block';

    const total  = hariTerlambat * DENDA_PER_HARI;
    const batas  = parseDate(tanggalBatasStr);
    const today  = new Date();

    document.getElementById('denda-batas-tgl').textContent  = batas ? tglIndonesia(batas) : tanggalBatasStr;
    document.getElementById('denda-hitung-tgl').textContent = tglIndonesia(today);
    document.getElementById('denda-hari-val').textContent   = hariTerlambat + ' hari';
    document.getElementById('denda-total-val').textContent  = fmtRupiah(total);

    // Breakdown per hari
    if (batas) {
        let lines = '';
        for (let i = 1; i <= hariTerlambat; i++) {
            const d = new Date(batas.getFullYear(), batas.getMonth(), batas.getDate() + i);
            const tgl = d.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' });
            const denda = fmtRupiah(i * DENDA_PER_HARI);
            lines += `Hari ke-${String(i).padStart(2,' ')}  (${tgl})  →  ${denda} (kumulatif)\n`;
        }
        document.getElementById('denda-breakdown-list').textContent = lines.trim();
    }
}

/* ════════════════════════════════════
   KAMERA
════════════════════════════════════ */
async function startCamera() {
    document.getElementById('retry-wrap').style.setProperty('display', 'none', 'important');
    setStatus('info', '<i class="fas fa-circle-notch fa-spin fa-fw mt-1"></i><span>Memulai kamera...</span>');
    setOverlay('Memuat kamera...');

    if (!navigator.mediaDevices?.getUserMedia) {
        setStatus('danger', '<i class="fas fa-exclamation-triangle fa-fw mt-1"></i><span>Browser tidak mendukung kamera. Gunakan input manual.</span>');
        setOverlay('Browser tidak mendukung kamera.');
        document.getElementById('retry-wrap').style.setProperty('display', 'block', 'important');
        return;
    }

    try {
        let stream;
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }
            });
        } catch (_) {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
        }

        videoStream = stream;
        const video = document.getElementById('qr-video');
        video.srcObject = stream;
        await video.play();

        const overlay = document.getElementById('scan-overlay');
        overlay.style.background    = 'transparent';
        overlay.style.pointerEvents = 'none';
        video.style.display         = 'block';

        scanActive = true;
        setStatus('success', '<i class="fas fa-camera fa-fw mt-1"></i><span>Kamera aktif — arahkan QR Code ke dalam kotak pemindai.</span>');
        setOverlay('Arahkan QR Code ke dalam kotak');

        requestAnimationFrame(scanLoop);

    } catch (err) {
        console.error('[Camera]', err.name, err.message);
        let msg = 'Kamera tidak dapat diakses (' + err.message + '). Gunakan input manual.';
        if (err.name === 'NotAllowedError')
            msg = 'Akses kamera <strong>ditolak</strong>. Izinkan akses kamera di browser lalu muat ulang halaman.';
        if (err.name === 'NotFoundError')
            msg = 'Kamera tidak ditemukan pada perangkat ini. Gunakan input manual.';

        setStatus('danger', `<i class="fas fa-exclamation-triangle fa-fw mt-1"></i><span>${msg}</span>`);
        setOverlay('Kamera tidak dapat diakses');
        document.getElementById('retry-wrap').style.setProperty('display', 'block', 'important');
    }
}

function scanLoop() {
    if (!scanActive) return;

    const video  = document.getElementById('qr-video');
    const canvas = document.getElementById('qr-canvas');

    if (video.readyState < video.HAVE_ENOUGH_DATA) {
        rafId = requestAnimationFrame(scanLoop);
        return;
    }

    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    let imageData;
    try {
        imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    } catch (e) {
        rafId = requestAnimationFrame(scanLoop);
        return;
    }

    const code = jsQR(imageData.data, imageData.width, imageData.height, {
        inversionAttempts: 'attemptBoth'
    });

    if (code && code.data.trim().length > 0) {
        // ✅ Bersihkan & ekstrak Booking ID dari QR
        const bookingId = cleanBookingId(code.data.trim());
        console.log('[QR] Raw   :', code.data.trim());
        console.log('[QR] Bersih:', bookingId);

        scanActive = false;
        cancelAnimationFrame(rafId);

        setStatus('primary',
            `<i class="fas fa-qrcode fa-fw mt-1"></i><span>QR terdeteksi: <strong>${bookingId}</strong> — memuat data...</span>`);

        fetchPeminjamanData(bookingId);
        return;
    }

    rafId = requestAnimationFrame(scanLoop);
}

function stopCamera() {
    scanActive = false;
    cancelAnimationFrame(rafId);
    if (videoStream) {
        videoStream.getTracks().forEach(t => t.stop());
        videoStream = null;
    }
}

function resumeCamera() {
    if (videoStream) {
        const video = document.getElementById('qr-video');
        if (video.paused) video.play();
        scanActive = true;
        setStatus('success', '<i class="fas fa-camera fa-fw mt-1"></i><span>Kamera aktif — arahkan QR Code ke dalam kotak pemindai.</span>');
        requestAnimationFrame(scanLoop);
    } else {
        startCamera();
    }
}

/* ════════════════════════════════════
   FETCH DATA DARI SERVER
════════════════════════════════════ */
function fetchPeminjamanData(bookingId) {
    fetch(`{{ route('admin.pengembalian.get-by-booking') }}?booking_id=${encodeURIComponent(bookingId)}`)
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) throw new Error(data.message || 'Data tidak ditemukan untuk Booking ID: ' + bookingId);
            displayModal(data.data);
            setStatus('success',
                `<i class="fas fa-check-circle fa-fw mt-1"></i><span>Data ditemukan untuk <strong>${bookingId}</strong></span>`);
        })
        .catch(err => {
            console.error('[Fetch]', err);
            setStatus('danger',
                `<i class="fas fa-times-circle fa-fw mt-1"></i><span>${err.message}</span>`);
            setTimeout(resumeCamera, 2000);
        });
}

/* ════════════════════════════════════
   ISI & TAMPILKAN MODAL
════════════════════════════════════ */
function displayModal(data) {
    currentPeminjamanId = data.id;
    document.getElementById('pinjaman-id').value = data.id;

    // Header
    document.getElementById('modal-booking-label').textContent = 'Booking ID: ' + data.booking_id;

    // Info peminjaman
    document.getElementById('detail-booking-id').textContent     = data.booking_id;
    document.getElementById('detail-tanggal-pinjam').textContent = data.tanggal_pinjam        || '—';
    document.getElementById('detail-tanggal-batas').textContent  = data.tanggal_kembali_rencana || '—';

    // Mahasiswa
    document.getElementById('mhs-nama').textContent    = data.mahasiswa?.nama       || '—';
    document.getElementById('mhs-nim').textContent     = data.mahasiswa?.nim        || '—';
    document.getElementById('mhs-jurusan').textContent = data.mahasiswa?.jurusan    || '—';
    document.getElementById('mhs-telp').textContent    = data.mahasiswa?.no_telepon || '—';

    // Buku
    document.getElementById('buku-judul').textContent     = data.buku?.nama_buku || '—';
    document.getElementById('buku-pengarang').textContent = data.buku?.pengarang  || '—';
    document.getElementById('buku-penerbit').textContent  = data.buku?.penerbit   || '—';
    document.getElementById('buku-kategori').textContent  = data.buku?.kategori   || data.buku?.jenis_buku || '—';

    // ── Kalkulasi denda ──
    let hariTerlambat = 0;
    if (data.denda && typeof data.denda.hari_terlambat === 'number') {
        hariTerlambat = data.denda.hari_terlambat;
    } else {
        hariTerlambat = hitungDenda(data.tanggal_kembali_rencana);
    }
    renderDenda(hariTerlambat, data.tanggal_kembali_rencana);

    // ── Status badge ──
    const badges = {
        dipinjam     : ['primary', 'Sedang Dipinjam'],
        menunggu     : ['warning', 'Menunggu Konfirmasi'],
        pending      : ['warning', 'Menunggu Konfirmasi'],
        disetujui    : ['info',    'Disetujui'],
        dikembalikan : ['success', 'Sudah Dikembalikan'],
        ditolak      : ['danger',  'Ditolak'],
    };
    const [bc, bl] = badges[data.status] || ['secondary', data.status];
    document.getElementById('pinjaman-status-badge').innerHTML =
        `<span class="badge bg-${bc}">${bl}</span>`;

    // ── Note & tombol ──
    const noteWrap  = document.getElementById('detail-note-wrap');
    const noteInner = document.getElementById('detail-note-inner');
    const btnReturn = document.getElementById('btn-return');

    noteWrap.style.display = 'block';

    if (data.status === 'dipinjam') {
        if (hariTerlambat > 0) {
            noteInner.className = 'alert alert-danger mb-0';
            noteInner.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i>
                Buku <strong>terlambat ${hariTerlambat} hari</strong>.
                Total denda: <strong>${fmtRupiah(hariTerlambat * DENDA_PER_HARI)}</strong>.
                Pastikan mahasiswa membayar denda sebelum pengembalian diproses.`;
        } else {
            noteInner.className = 'alert alert-warning mb-0';
            noteInner.innerHTML = '<i class="fas fa-info-circle me-1"></i>Pinjaman aktif. Tekan <strong>Proses Pengembalian</strong> untuk menyelesaikan.';
        }
        btnReturn.disabled  = false;
        btnReturn.className = 'btn btn-success fw-bold';
        btnReturn.innerHTML = '<i class="fas fa-check me-1"></i>Proses Pengembalian';
    } else if (data.status === 'dikembalikan') {
        noteInner.className = 'alert alert-success mb-0';
        noteInner.innerHTML = '<i class="fas fa-check-circle me-1"></i>Buku sudah dikembalikan sebelumnya.';
        btnReturn.disabled  = true;
        btnReturn.className = 'btn btn-secondary';
    } else {
        noteInner.className = 'alert alert-secondary mb-0';
        noteInner.innerHTML = `<i class="fas fa-info-circle me-1"></i>Status: <strong>${bl}</strong>. Pengembalian hanya bisa diproses untuk buku yang sedang dipinjam.`;
        btnReturn.disabled  = true;
        btnReturn.className = 'btn btn-secondary';
    }

    new bootstrap.Modal(document.getElementById('modalPinjaman')).show();
}

/* ════════════════════════════════════
   PROSES PENGEMBALIAN
════════════════════════════════════ */
function prosesPengembalian() {
    if (!currentPeminjamanId) return;

    const hariEl   = document.getElementById('denda-hari-val').textContent;
    const totalEl  = document.getElementById('denda-total-val').textContent;
    const adaDenda = document.getElementById('denda-detail').style.display !== 'none';

    let konfMsg = 'Yakin ingin memproses pengembalian buku ini?';
    if (adaDenda) {
        konfMsg = `⚠️ PERHATIAN: Ada denda keterlambatan!\n\n`
            + `• Hari terlambat : ${hariEl}\n`
            + `• Total denda    : ${totalEl}\n\n`
            + `Pastikan mahasiswa sudah membayar denda.\nLanjutkan proses pengembalian?`;
    }
    if (!confirm(konfMsg)) return;

    const btn = document.getElementById('btn-return');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';

    $.post(`/admin/pengembalian/${currentPeminjamanId}/proses`,
        { _token: '{{ csrf_token() }}' },
        function (res) {
            if (res.success) {
                const dendaInfo = (res.denda && res.denda.total_denda > 0)
                    ? `\n\nDenda dikenakan: ${fmtRupiah(res.denda.total_denda)}`
                    : '\n\nTidak ada denda.';
                alert('✅ ' + res.message + dendaInfo);
                bootstrap.Modal.getInstance(document.getElementById('modalPinjaman')).hide();
                onModalClose();
            } else {
                alert('❌ ' + (res.message || 'Terjadi kesalahan.'));
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Proses Pengembalian';
            }
        }
    ).fail(function (xhr) {
        alert('❌ ' + (xhr.responseJSON?.message || 'Terjadi kesalahan server.'));
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Proses Pengembalian';
    });
}

/* ════════════════════════════════════
   MANUAL INPUT
════════════════════════════════════ */
function handleManualInput() {
    // ✅ Bersihkan prefix / URL jika ada
    let val = document.getElementById('manual-booking-id').value.trim();
    val = cleanBookingId(val);

    if (!val) {
        document.getElementById('manual-booking-id').focus();
        setStatus('warning', '<i class="fas fa-exclamation-circle fa-fw mt-1"></i><span>Masukkan Booking ID terlebih dahulu.</span>');
        return;
    }
    scanActive = false;
    setStatus('info', `<i class="fas fa-search fa-fw mt-1"></i><span>Mencari data untuk: <strong>${val}</strong>...</span>`);
    fetchPeminjamanData(val);
}

/* ════════════════════════════════════
   MODAL CLOSE
════════════════════════════════════ */
function onModalClose() {
    currentPeminjamanId = null;
    document.getElementById('manual-booking-id').value = '';

    const bp = document.getElementById('dendaBreakdown');
    if (bp) bootstrap.Collapse.getInstance(bp)?.hide();

    setTimeout(resumeCamera, 400);
}

/* ════════════════════════════════════
   INIT
════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    startCamera();
    document.getElementById('modalPinjaman').addEventListener('hidden.bs.modal', onModalClose);
});
</script>
@endpush