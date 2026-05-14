@extends('layouts.admin')

@section('title', 'Denda')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manajemen Denda</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.denda.export-pdf') }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('admin.denda.export-excel') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableDenda" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Booking ID</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Buku</th>
                                <th>Hari Terlambat</th>
                                <th>Total Denda</th>
                                <th>Status Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dendas as $i => $denda)
                            <tr class="{{ $denda->status_bayar == 'belum_bayar' ? 'table-danger' : 'table-success' }}">
                                <td>{{ $i+1 }}</td>
                                <td><code>{{ $denda->peminjaman->booking_id ?? '-' }}</code></td>
                                <td>{{ $denda->peminjaman->mahasiswa->nama ?? '-' }}</td>
                                <td>{{ $denda->peminjaman->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $denda->peminjaman->buku->nama_buku ?? '-' }}</td>
                                <td>{{ $denda->hari_terlambat }} hari</td>
                                <td>Rp {{ number_format($denda->total_denda, 0, ',', '.') }}</td>
                                <td>
                                    @if($denda->status_bayar == 'belum_bayar')
                                        <span class="badge badge-danger">Belum Bayar</span>
                                    @else
                                        <span class="badge badge-success">Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    @if($denda->status_bayar == 'belum_bayar')
                                        <button onclick="konfirmasiLunas({{ $denda->id }})" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Tandai Lunas
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada denda
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

{{-- Modal Konfirmasi Tandai Lunas --}}
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" role="dialog" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalKonfirmasiLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menandai denda ini sebagai <strong>Lunas</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="button" class="btn btn-success" id="btnKonfirmasiLunas">
                    <i class="fas fa-check mr-1"></i> Ya, Tandai Lunas
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Sukses --}}
<div class="modal fade" id="modalSukses" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i> Berhasil
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="pesanSukses"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnSuksesOk">
                    <i class="fas fa-check mr-1"></i> OK
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Error --}}
<div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i> Terjadi Kesalahan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="pesanError"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#tableDenda').DataTable();

    // Tombol OK pada modal sukses → reload halaman
    $('#btnSuksesOk').on('click', function() {
        $('#modalSukses').modal('hide');
        location.reload();
    });
});

var selectedDendaId = null;

function konfirmasiLunas(id) {
    selectedDendaId = id;
    $('#modalKonfirmasi').modal('show');
}

$('#btnKonfirmasiLunas').on('click', function() {
    if (!selectedDendaId) return;

    $('#modalKonfirmasi').modal('hide');

    $.ajax({
        url: '/admin/denda/' + selectedDendaId + '/lunas',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(res) {
            if (res.success) {
                $('#pesanSukses').text(res.message);
                $('#modalSukses').modal('show');
            }
        },
        error: function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message)
                ? xhr.responseJSON.message
                : 'Terjadi kesalahan yang tidak diketahui.';
            $('#pesanError').text(msg);
            $('#modalError').modal('show');
        }
    });
});
</script>
@endpush