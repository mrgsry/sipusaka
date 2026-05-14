@extends('layouts.admin')

@section('title', 'History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Aktivitas</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.history.export-pdf') }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('admin.history.export-excel') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableHistory" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Booking ID</th>
                                <th>Mahasiswa</th>
                                <th>Buku</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                                <th>Dilakukan Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $i => $h)
                            <tr>
                                <td>{{ $histories->firstItem() + $i }}</td>
                                <td>{{ $h->created_at->format('d/m/Y H:i') }}</td>
                                <td><code>{{ $h->peminjaman->booking_id ?? '-' }}</code></td>
                                <td>
                                    <strong>{{ $h->peminjaman->mahasiswa->nama ?? '-' }}</strong><br>
                                    <small>({{ $h->peminjaman->mahasiswa->nim ?? '-' }})</small>
                                </td>
                                <td>{{ $h->peminjaman->buku->nama_buku ?? '-' }}</td>
                                <td>
                                    @if($h->aksi == 'Dipinjam')
                                        <span class="badge badge-info">{{ $h->aksi }}</span>
                                    @elseif($h->aksi == 'Dikembalikan')
                                        <span class="badge badge-success">{{ $h->aksi }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $h->aksi }}</span>
                                    @endif
                                </td>
                                <td>{{ $h->keterangan ?? '-' }}</td>
                                <td>{{ $h->dilakukan_oleh ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada history
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#tableHistory').DataTable({
        "paging": false,
        "ordering": true,
        "info": false
    });
});
</script>
@endpush
