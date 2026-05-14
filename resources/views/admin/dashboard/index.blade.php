@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Dashboard</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        {{-- Stats Cards --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalBuku }}</h3>
                        <p>Total Buku</p>
                    </div>
                    <div class="icon"><i class="fas fa-book"></i></div>
                    <a href="{{ route('admin.buku.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalMahasiswa }}</h3>
                        <p>Total Mahasiswa</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingRequest }}</h3>
                        <p>Request Pending</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <a href="{{ route('admin.peminjaman.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}</h3>
                        <p>Denda Belum Dibayar</p>
                    </div>
                    <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                    <a href="{{ route('admin.denda.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Tabel Peminjaman Terbaru --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list me-2"></i>Peminjaman Terbaru
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover m-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Mahasiswa</th>
                                    <th>Buku</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamanTerbaru as $p)
                                <tr>
                                    <td><code>{{ $p->booking_id }}</code></td>
                                    <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                                    <td>{{ $p->buku->nama_buku ?? '-' }}</td>
                                    <td>
                                        @if($p->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($p->status == 'dipinjam')
                                            <span class="badge bg-primary">Dipinjam</span>
                                        @elseif($p->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @else
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Belum ada data peminjaman
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-primary">
                            Lihat Semua Peminjaman
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stok Kritis --}}
            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>Stok Kritis
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($stokKritis as $buku)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span style="font-size:13px">{{ Str::limit($buku->nama_buku, 25) }}</span>
                                <span class="badge bg-danger rounded-pill">{{ $buku->stok_tersedia }} sisa</span>
                            </li>
                            @empty
                            <li class="list-group-item text-center text-muted py-3">
                                Semua stok aman ✅
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection