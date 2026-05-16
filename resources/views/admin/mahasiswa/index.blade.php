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
            <div class="card-header">
                <h3 class="card-title">Daftar Mahasiswa</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                                <th>Jurusan</th>
                                <th>No. Telepon</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Token Referral</th>
                                <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswas as $i => $mahasiswa)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $mahasiswa->nama }}</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ $mahasiswa->jurusan }}</td>
                                <td>{{ $mahasiswa->no_telepon ?? '-' }}</td>
                                <td>{{ $mahasiswa->email }}</td>
                                <td>
                                    @if($mahasiswa->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($mahasiswa->status === 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mahasiswa->referral_token)
                                        <code class="bg-light p-1">{{ $mahasiswa->referral_token }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($mahasiswa->status === 'pending')
                                        <button type="button" class="btn btn-success btn-xs btn-action" data-url="{{ route('admin.mahasiswa.approve', $mahasiswa->id) }}" data-action="Approve" data-nama="{{ $mahasiswa->nama }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs btn-action" data-url="{{ route('admin.mahasiswa.reject', $mahasiswa->id) }}" data-action="Reject" data-nama="{{ $mahasiswa->nama }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-outline-danger btn-xs btn-delete" data-url="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" data-nama="{{ $mahasiswa->nama }}">
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmMessage">
                Apakah Anda yakin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize Bootstrap modal
const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
let currentAction = null;
let currentUrl = null;
let currentNama = null;

// Handle Approve/Reject buttons
document.querySelectorAll('.btn-action').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const action = this.getAttribute('data-action');
        const nama = this.getAttribute('data-nama');
        
        currentAction = action;
        currentUrl = url;
        currentNama = nama;
        
        // Set modal content
        document.getElementById('confirmTitle').textContent = 'Konfirmasi ' + action;
        document.getElementById('confirmMessage').textContent = 
            'Apakah Anda yakin ingin ' + action.toLowerCase() + ' mahasiswa "' + nama + '"?';
        document.getElementById('confirmBtn').className = 'btn ' + 
            (action === 'Approve' ? 'btn-success' : 'btn-danger');
        document.getElementById('confirmBtn').textContent = action;
        
        // Show modal
        confirmModal.show();
    });
});

// Handle Delete button
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const nama = this.getAttribute('data-nama');
        
        currentAction = 'Delete';
        currentUrl = url;
        currentNama = nama;
        
        // Set modal content
        document.getElementById('confirmTitle').textContent = 'Konfirmasi Hapus';
        document.getElementById('confirmMessage').textContent = 
            'Apakah Anda yakin ingin menghapus mahasiswa "' + nama + '"?\n\n' +
            'Data yang dihapus tidak dapat dikembalikan.';
        document.getElementById('confirmBtn').className = 'btn btn-danger';
        document.getElementById('confirmBtn').textContent = 'Hapus';
        
        // Show modal
        confirmModal.show();
    });
});

// Handle confirm button click
document.getElementById('confirmBtn').addEventListener('click', function() {
    const method = currentAction === 'Delete' ? 'DELETE' : 'POST';
    
    fetch(currentUrl, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        confirmModal.hide();
        
        if (data.success) {
            // Show success message
            let successMessage = currentAction === 'Approve' ? 
                'Mahasiswa "' + currentNama + '" berhasil disetujui.' :
                currentAction === 'Reject' ?
                'Mahasiswa "' + currentNama + '" berhasil ditolak.' :
                'Mahasiswa "' + currentNama + '" berhasil dihapus.';
            
            // Add token info if approve action
            let tokenInfo = '';
            if (currentAction === 'Approve' && data.referral_token) {
                tokenInfo = `
                    <div class="alert alert-info mt-3 mb-0" style="text-align: left;">
                        <strong><i class="fas fa-key me-2"></i>Token Referral:</strong>
                        <div class="mt-2">
                            <code style="font-size: 1.2rem; background: #fff; padding: 8px 16px; border-radius: 6px; display: inline-block; letter-spacing: 2px;">${data.referral_token}</code>
                        </div>
                        <small class="d-block mt-2 text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Token ini diperlukan mahasiswa untuk akses ebook dan peminjaman buku.
                        </small>
                    </div>
                `;
            }
            
            // Create and show success modal
            const successModal = new bootstrap.Modal(document.createElement('div'));
            const modalHtml = `
                <div class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">Berhasil</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                                    <p>${successMessage}</p>
                                </div>
                                ${tokenInfo}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="window.location.reload()">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = modalHtml;
            document.body.appendChild(tempDiv.firstChild);
            
            const newModal = new bootstrap.Modal(tempDiv.firstChild);
            newModal.show();
            
            // Auto reload after modal is hidden
            tempDiv.firstChild.addEventListener('hidden.bs.modal', function () {
                window.location.reload();
            });
        } else {
            // Show error message
            alert('Gagal melakukan aksi: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        confirmModal.hide();
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses permintaan.');
    });
});
</script>
@endpush
