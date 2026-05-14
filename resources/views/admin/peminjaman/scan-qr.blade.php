@extends('layouts.admin')

@section('title', 'Scan QR Code')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Scan QR Code Peminjaman</h3>
                </div>
                <div class="card-body">
                    <div id="scanner" style="width: 100%; height: 400px; border: 2px dashed #ccc; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                        <div class="text-center">
                            <i class="fas fa-camera fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Kamera akan aktif dalam beberapa detik...</p>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3" id="scan-status">
                        <i class="fas fa-info-circle"></i> Arahkan kamera ke QR Code untuk melakukan scan
                    </div>

                    <div class="form-group mt-4">
                        <label class="font-weight-bold">Atau Input Booking ID Secara Manual:</label>
                        <div class="input-group">
                            <input type="text" id="manual-booking-id" class="form-control" placeholder="PJM-XXXXXXXX">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" onclick="handleManualInput()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peminjaman -->
<div class="modal fade" id="modalPinjaman" tabindex="-1" role="dialog" aria-labelledby="modalPeminjamanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalPeminjamanLabel">
                    <i class="fas fa-book"></i> Detail Peminjaman
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pinjaman-id">

                <!-- Data Mahasiswa -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-user-graduate"></i> Data Mahasiswa
                        </h6>
                        <table class="table table-sm">
                            <tr>
                                <td class="font-weight-bold" style="width: 40%;">Nama</td>
                                <td>: <span id="mhs-nama">-</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">NIM</td>
                                <td>: <span id="mhs-nim">-</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Jurusan</td>
                                <td>: <span id="mhs-jurusan">-</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Telepon</td>
                                <td>: <span id="mhs-telp">-</span></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Data Buku -->
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-success mb-3">
                            <i class="fas fa-book-open"></i> Data Buku
                        </h6>
                        <table class="table table-sm">
                            <tr>
                                <td class="font-weight-bold" style="width: 40%;">Judul</td>
                                <td>: <span id="buku-judul">-</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Pengarang</td>
                                <td>: <span id="buku-pengarang">-</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Penerbit</td>
                                <td>: <span id="buku-penerbit">-</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Kategori</td>
                                <td>: <span id="buku-kategori">-</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Status -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="font-weight-bold text-info mb-3">
                            <i class="fas fa-info-circle"></i> Status Peminjaman
                        </h6>
                        <div class="alert alert-warning" id="status-alert">
                            <strong>Status:</strong> <span id="pinjaman-status">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger" onclick="rejectPinjaman()">
                    <i class="fas fa-times"></i> Tolak
                </button>
                <button type="button" class="btn btn-success" onclick="approvePinjaman()">
                    <i class="fas fa-check"></i> Setujui
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<!-- QR Scanner Library -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
let cameraActive = true;
let currentBookingId = null;

// Mulai kamera
function startCamera() {
    const video = document.createElement('video');
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            video.srcObject = stream;
            video.play();

            const scanner = document.getElementById('scanner');
            scanner.innerHTML = '';
            scanner.appendChild(video);
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';

            // Proses QR scanning
            function scanFrame() {
                if (!cameraActive) return;

                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        // Ekstrak booking ID dari QR code
                        const bookingId = code.data.trim();
                        if (bookingId.match(/^PJM-[A-Z0-9]{8}$/)) {
                            cameraActive = false;
                            fetchPeminjamanData(bookingId);
                        }
                    }
                }

                if (cameraActive) {
                    requestAnimationFrame(scanFrame);
                }
            }

            scanFrame();
        })
        .catch(err => {
            console.error('Error accessing camera:', err);
            document.getElementById('scan-status').innerHTML = 
                '<i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong> Tidak dapat mengakses kamera. ' +
                'Silakan periksa izin akses atau gunakan input manual.';
            document.getElementById('scan-status').className = 'alert alert-danger';
        });
}

// Fetch data peminjaman
function fetchPeminjamanData(bookingId) {
    const status = document.getElementById('scan-status');
    status.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sedang memproses...';
    status.className = 'alert alert-info';

    fetch(`{{ route('publik.pinjam.get-qr') }}?booking_id=${bookingId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                displayPeminjamanModal(data.data);
                status.innerHTML = '<i class="fas fa-check-circle"></i> QR Code berhasil dipindai!';
                status.className = 'alert alert-success';
            } else {
                status.innerHTML = '<i class="fas fa-times-circle"></i> ' + data.message;
                status.className = 'alert alert-danger';
                cameraActive = true;
                startCamera(); // Restart camera
            }
        })
        .catch(err => {
            status.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error: ' + err.message;
            status.className = 'alert alert-danger';
            cameraActive = true;
            startCamera(); // Restart camera
        });
}

// Tampilkan modal
function displayPeminjamanModal(data) {
    currentBookingId = data.booking_id;
    document.getElementById('pinjaman-id').value = data.id;

    // Mahasiswa
    document.getElementById('mhs-nama').textContent = data.mahasiswa.nama;
    document.getElementById('mhs-nim').textContent = data.mahasiswa.nim;
    document.getElementById('mhs-jurusan').textContent = data.mahasiswa.jurusan;
    document.getElementById('mhs-telp').textContent = data.mahasiswa.no_telepon || '-';

    // Buku
    document.getElementById('buku-judul').textContent = data.buku.nama_buku;
    document.getElementById('buku-pengarang').textContent = data.buku.pengarang;
    document.getElementById('buku-penerbit').textContent = data.buku.penerbit;
    document.getElementById('buku-kategori').textContent = data.buku.kategori;

    // Status
    document.getElementById('pinjaman-status').textContent = data.status;

    $('#modalPinjaman').modal('show');
}

// Handle input manual
function handleManualInput() {
    const bookingId = document.getElementById('manual-booking-id').value.trim();
    if (!bookingId) {
        alert('Silakan masukkan Booking ID');
        return;
    }
    fetchPeminjamanData(bookingId);
}

// Approve peminjaman
function approvePinjaman() {
    const id = document.getElementById('pinjaman-id').value;
    if (!id) return;

    $.post(`/admin/peminjaman/${id}/approve`, {
        _token: '{{ csrf_token() }}'
    }, function(res) {
        if (res.success) {
            alert('Peminjaman berhasil disetujui!');
            $('#modalPinjaman').modal('hide');
            document.getElementById('manual-booking-id').value = '';
            document.getElementById('scan-status').innerHTML = '<i class="fas fa-info-circle"></i> Arahkan kamera ke QR Code untuk melakukan scan';
            document.getElementById('scan-status').className = 'alert alert-info';
            cameraActive = true;
            startCamera();
        }
    });
}

// Reject peminjaman
function rejectPinjaman() {
    const id = document.getElementById('pinjaman-id').value;
    if (!id) return;

    if (!confirm('Apakah Anda yakin ingin menolak peminjaman ini?')) return;

    $.post(`/admin/peminjaman/${id}/tolak`, {
        _token: '{{ csrf_token() }}'
    }, function(res) {
        if (res.success) {
            alert('Peminjaman berhasil ditolak!');
            $('#modalPinjaman').modal('hide');
            document.getElementById('manual-booking-id').value = '';
            document.getElementById('scan-status').innerHTML = '<i class="fas fa-info-circle"></i> Arahkan kamera ke QR Code untuk melakukan scan';
            document.getElementById('scan-status').className = 'alert alert-info';
            cameraActive = true;
            startCamera();
        }
    });
}

// Start camera on page load
document.addEventListener('DOMContentLoaded', function() {
    startCamera();
});
</script>
@endpush
