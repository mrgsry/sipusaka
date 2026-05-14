<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10pt; }
        th { background-color: #4472C4; color: white; }
    </style>
</head>
<body>
    <h1>Laporan Riwayat Aktivitas</h1>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Booking ID</th>
                <th>Mahasiswa</th>
                <th>Buku</th>
                <th>Aksi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $i => $h)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $h->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $h->peminjaman->booking_id ?? '-' }}</td>
                <td>{{ $h->peminjaman->mahasiswa->nama ?? '-' }} ({{ $h->peminjaman->mahasiswa->nim ?? '-' }})</td>
                <td>{{ $h->peminjaman->buku->nama_buku ?? '-' }}</td>
                <td>{{ $h->aksi }}</td>
                <td>{{ $h->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada history</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
