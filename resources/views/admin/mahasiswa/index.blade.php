@extends('layouts.admin')
@section('title', 'Manajemen Mahasiswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Manajemen Mahasiswa</h1>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Mahasiswa</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
                </button>
            </div>
            <div class="card-body">
                <table id="tableMahasiswa" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $i => $mhs)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $mhs->nama }}</td>
                            <td><code>{{ $mhs->nim }}</code></td>
                            <td>{{ $mhs->jurusan }}</td>
                            <td>{{ $mhs->no_telepon }}</td>
                            <td>
                                <button onclick="editMahasiswa({{ $mhs->id }}, '{{ $mhs->nama }}', '{{ $mhs->nim }}', '{{ $mhs->jurusan }}', '{{ $mhs->no_telepon }}')"
                                        class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusMahasiswa({{ $mhs->id }})"
                                        class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alertTambah"></div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="tambah_nama" class="form-control" placeholder="Nama lengkap">
                </div>
                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" id="tambah_nim" class="form-control" placeholder="Nomor Induk Mahasiswa">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <input type="text" id="tambah_jurusan" class="form-control" placeholder="Jurusan">
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" id="tambah_telepon" class="form-control" placeholder="08xx">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanMahasiswa()">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="edit_nama" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" id="edit_nim" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <input type="text" id="edit_jurusan" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" id="edit_telepon" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="updateMahasiswa()">Update</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p>Yakin ingin menghapus mahasiswa ini?</p>
                <input type="hidden" id="hapus_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="konfirmasiHapus()">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
// Aktifkan DataTable
$('#tableMahasiswa').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.10.x/i18n/Indonesian.json' }
});

// Simpan mahasiswa baru
function simpanMahasiswa() {
    $.ajax({
        url: '{{ route("admin.mahasiswa.store") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            nama: $('#tambah_nama').val(),
            nim: $('#tambah_nim').val(),
            jurusan: $('#tambah_jurusan').val(),
            no_telepon: $('#tambah_telepon').val(),
        },
        success: function(res) {
            if (res.success) {
                location.reload();
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            let msg = Object.values(errors).flat().join('<br>');
            $('#alertTambah').html('<div class="alert alert-danger">'+msg+'</div>');
        }
    });
}

// Buka modal edit dengan data
function editMahasiswa(id, nama, nim, jurusan, telepon) {
    $('#edit_id').val(id);
    $('#edit_nama').val(nama);
    $('#edit_nim').val(nim);
    $('#edit_jurusan').val(jurusan);
    $('#edit_telepon').val(telepon);
    $('#modalEdit').modal('show');
}

// Update mahasiswa
function updateMahasiswa() {
    let id = $('#edit_id').val();
    $.ajax({
        url: '/admin/mahasiswa/' + id,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            nama: $('#edit_nama').val(),
            nim: $('#edit_nim').val(),
            jurusan: $('#edit_jurusan').val(),
            no_telepon: $('#edit_telepon').val(),
        },
        success: function(res) {
            if (res.success) location.reload();
        }
    });
}

// Buka modal hapus
function hapusMahasiswa(id) {
    $('#hapus_id').val(id);
    $('#modalHapus').modal('show');
}

// Konfirmasi hapus
function konfirmasiHapus() {
    let id = $('#hapus_id').val();
    $.ajax({
        url: '/admin/mahasiswa/' + id,
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function(res) {
            if (res.success) location.reload();
        }
    });
}
</script>
@endpush