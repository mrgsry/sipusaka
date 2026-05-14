@extends('layouts.admin')

@section('title', 'Pengembalian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manajemen Pengembalian Buku</h3>
                    <a href="{{ route('admin.pengembalian.scan-qr') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-qrcode"></i> Scan QR Pengembalian
                    </a>
                </div>
                <div class="card-body">
                    <table id="tablePengembalian" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Booking ID</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pinjamans as $i => $p)
                            @php
                                $batas    = \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->startOfDay();
                                $sisaHari = (int) now()->startOfDay()->diffInDays($batas, false);
                            @endphp
                            <tr class="{{ $sisaHari < 0 ? 'table-danger' : ($sisaHari <= 2 ? 'table-warning' : '') }}">
                                <td>{{ $i+1 }}</td>
                                <td><code>{{ $p->booking_id }}</code></td>
                                <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                                <td>{{ $p->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $p->buku->nama_buku ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                <td>{{ $batas->format('d/m/Y') }}</td>
                                <td>
                                    @if($sisaHari < 0)
                                        <span class="badge badge-danger">Terlambat {{ abs($sisaHari) }} hari</span>
                                    @elseif($sisaHari == 0)
                                        <span class="badge badge-warning text-dark">Hari ini</span>
                                    @else
                                        <span class="badge badge-success">{{ $sisaHari }} hari lagi</span>
                                    @endif
                                </td>
                                <td>
                                    <button onclick="konfirmasiKembali({{ $p->id }}, '{{ $p->booking_id }}', '{{ $p->mahasiswa->nama ?? '' }}')"
                                            class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Konfirmasi Kembali
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Semua buku sudah dikembalikan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pengembalian -->
<div class="modal fade" id="modalKembali" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> Konfirmasi Pengembalian Buku</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Yakin buku dengan Booking ID <strong id="kembali_booking_id"></strong> sudah dikembalikan?</p>
                <p>Mahasiswa: <strong id="kembali_mahasiswa"></strong></p>
                <input type="hidden" id="kembali_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="prosesPengembalian()">
                    <i class="fas fa-check"></i> Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#tablePengembalian').DataTable();
});

function konfirmasiKembali(id, bookingId, mahasiswa) {
    $('#kembali_id').val(id);
    $('#kembali_booking_id').text(bookingId);
    $('#kembali_mahasiswa').text(mahasiswa);
    $('#modalKembali').modal('show');
}

function prosesPengembalian() {
    let id = $('#kembali_id').val();
    $.ajax({
        url: '/admin/pengembalian/' + id + '/approve',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(res) {
            if (res.success) {
                $('#modalKembali').modal('hide');
                alert(res.message);
                location.reload();
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
        }
    });
}
</script>
@endpush