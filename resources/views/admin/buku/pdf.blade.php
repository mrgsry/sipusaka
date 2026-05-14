<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; font-size: 12px; }
        h2 { text-align: center; color: #1a3c6b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #1a3c6b; color: white; padding: 8px; text-align: left; }
        td { padding: 7px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge-pending { color: #92400e; }
        .badge-dipinjam { color: #1e40af; }
        .badge-kembali { color: #065f46; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Buku</h2>
    <p>Tanggal cetak: {{ now()->format('d/m/Y H:i') }}</p>
    <table>
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
            </tr>
        </thead>
        <tbody>
            @foreach($pinjamans as $i => $p)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->booking_id }}</td>
                <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                <td>{{ $p->mahasiswa->nim ?? '-' }}</td>
                <td>{{ $p->buku->nama_buku ?? '-' }}</td>
                <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                <td>{{ $p->tanggal_kembali_rencana ? \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d/m/Y') : '-' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>