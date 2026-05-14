<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4472C4; color: white; }
    </style>
</head>
<body>
    <h1>Laporan Denda</h1>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>

    <table>
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
            </tr>
        </thead>
        <tbody>
            @forelse($dendas as $i => $denda)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $denda->peminjaman->booking_id ?? '-' }}</td>
                <td>{{ $denda->peminjaman->mahasiswa->nama ?? '-' }}</td>
                <td>{{ $denda->peminjaman->mahasiswa->nim ?? '-' }}</td>
                <td>{{ $denda->peminjaman->buku->nama_buku ?? '-' }}</td>
                <td>{{ $denda->hari_terlambat }} hari</td>
                <td>Rp {{ number_format($denda->total_denda, 0, ',', '.') }}</td>
                <td>{{ $denda->status_bayar }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada denda</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
