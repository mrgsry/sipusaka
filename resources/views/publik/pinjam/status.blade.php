@extends('layouts.publik')

@section('title', 'Status Peminjaman')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-info-circle"></i> Status Peminjaman</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Detail Peminjaman:</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Booking ID</strong></td>
                                    <td>:</td>
                                    <td><code>{{ $peminjaman->booking_id }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Mahasiswa</strong></td>
                                    <td>:</td>
                                    <td>{{ $peminjaman->mahasiswa->nama }} ({{ $peminjaman->mahasiswa->nim }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Buku</strong></td>
                                    <td>:</td>
                                    <td>{{ $peminjaman->buku->nama_buku }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pinjam</strong></td>
                                    <td>:</td>
                                    <td>{{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') : 'Belum disetujui' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tenggat Kembali</strong></td>
                                    <td>:</td>
                                    <td>
                                        {{ $peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Kembali</strong></td>
                                    <td>:</td>
                                    <td>{{ $peminjaman->tanggal_kembali_aktual ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->format('d/m/Y') : 'Belum dikembalikan' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>
                                        @if($peminjaman->status == 'pending')
                                            <span class="badge badge-warning">Menunggu Approval</span>
                                        @elseif($peminjaman->status == 'dipinjam')
                                            <span class="badge badge-success">Sedang Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge badge-info">Sudah Dikembalikan</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $peminjaman->status }}</span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- ✅ Denda realtime (buku masih dipinjam & terlambat) --}}
                                @if($dendaRealtime && $dendaRealtime['hari_terlambat'] > 0)
                                <tr>
                                    <td><strong>Keterlambatan</strong></td>
                                    <td>:</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            {{ $dendaRealtime['hari_terlambat'] }} hari
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Estimasi Denda</strong></td>
                                    <td>:</td>
                                    <td>
                                        <span class="text-danger font-weight-bold">
                                            Rp {{ number_format($dendaRealtime['total_denda'], 0, ',', '.') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">*denda akan terus bertambah Rp 10.000/hari</small>
                                    </td>
                                </tr>
                                @endif

                                {{-- ✅ Denda final (buku sudah dikembalikan, ada record denda) --}}
                                @if($peminjaman->denda)
                                <tr>
                                    <td><strong>Denda Terlambat</strong></td>
                                    <td>:</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            {{ $peminjaman->denda->hari_terlambat }} hari —
                                            Rp {{ number_format($peminjaman->denda->total_denda, 0, ',', '.') }}
                                        </span>
                                        <br>
                                        @if($peminjaman->denda->status_bayar == 'belum_bayar')
                                            <small class="text-danger"><i class="fas fa-times-circle"></i> Belum Dibayar</small>
                                        @else
                                            <small class="text-success"><i class="fas fa-check-circle"></i> Sudah Dibayar</small>
                                        @endif
                                    </td>
                                </tr>
                                @endif

                            </table>
                        </div>

                        <div class="col-md-6">
                            @if($peminjaman->qr_code_path)
                                <h6>QR Code Peminjaman:</h6>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $peminjaman->qr_code_path) }}"
                                         alt="QR Code" class="img-fluid" style="max-width:200px;">
                                    <br>
                                    <a href="{{ asset('storage/' . $peminjaman->qr_code_path) }}"
                                       download class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-download"></i> Download QR Code
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    QR Code akan tersedia setelah peminjaman disetujui admin.
                                </div>
                            @endif

                            {{-- ✅ Peringatan denda realtime --}}
                            @if($dendaRealtime && $dendaRealtime['hari_terlambat'] > 0)
                                <div class="alert alert-danger mt-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Perhatian!</strong><br>
                                    Buku Anda sudah melewati batas pengembalian <strong>{{ $dendaRealtime['hari_terlambat'] }} hari</strong>.
                                    Segera kembalikan ke perpustakaan untuk menghindari denda yang terus bertambah.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('publik.cek-status') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cek Status Lain
                        </a>
                        <a href="/" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal denda otomatis muncul jika ada keterlambatan --}}
@if(($dendaRealtime && $dendaRealtime['hari_terlambat'] > 0) || $peminjaman->denda)
<div class="modal fade" id="modalDenda" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Pemberitahuan Denda Keterlambatan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h6><i class="fas fa-clock"></i> Detail Keterlambatan</h6>
                    <p>Buku <strong>{{ $peminjaman->buku->nama_buku }}</strong> terlambat dikembalikan selama
                        <strong>
                            @if($peminjaman->denda)
                                {{ $peminjaman->denda->hari_terlambat }}
                            @else
                                {{ $dendaRealtime['hari_terlambat'] }}
                            @endif
                        </strong> hari.
                    </p>
                    <p>Batas kembali: <strong>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y') }}</strong></p>
                    @if($peminjaman->tanggal_kembali_aktual)
                        <p>Tanggal kembali: <strong>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->format('d/m/Y') }}</strong></p>
                    @endif
                </div>
                <div class="alert alert-danger">
                    <h6><i class="fas fa-money-bill-wave"></i> Tagihan Denda</h6>
                    <p>Total denda:
                        <strong>
                            Rp {{ number_format(
                                $peminjaman->denda
                                    ? $peminjaman->denda->total_denda
                                    : $dendaRealtime['total_denda'],
                                0, ',', '.'
                            ) }}
                        </strong>
                        @if(!$peminjaman->denda)
                            <small class="text-muted">(estimasi, bertambah Rp 10.000/hari)</small>
                        @endif
                    </p>
                    @if($peminjaman->denda)
                        <p>Status:
                            @if($peminjaman->denda->status_bayar == 'belum_bayar')
                                <span class="badge badge-danger">Belum Dibayar</span>
                            @else
                                <span class="badge badge-success">Sudah Dibayar</span>
                            @endif
                        </p>
                    @endif
                </div>
                <p class="text-muted">
                    <i class="fas fa-phone"></i>
                    Silakan hubungi admin perpustakaan untuk informasi pembayaran denda.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#modalDenda').modal('show');
});
</script>
@endif

@endsection