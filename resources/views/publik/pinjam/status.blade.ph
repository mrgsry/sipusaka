@extends('layouts.publik')

@section('title', 'Detail Status Peminjaman')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle"></i> Detail Status Peminjaman
                    </h4>
                </div>
                <div class="card-body">
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
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>{{ $peminjaman->status }}</td>
                        </tr>
                    </table>

                    <a href="{{ route('publik.cek-status') }}" class="btn btn-secondary mt-2">
                        <i class="fas fa-arrow-left"></i> Cek Ulang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection