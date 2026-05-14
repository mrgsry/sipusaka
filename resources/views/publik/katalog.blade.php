<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku | SiPusaka</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #0f2444;
            --navy-mid: #1a3c6b;
            --gold: #c9930a;
            --gold-light: #f0b429;
            --cream: #faf8f3;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e2e8f0;
            --green: #059669;
            --red: #dc2626;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            margin: 0;
        }

        /* NAVBAR */
        .navbar-sipusaka {
            background: var(--navy);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 24px rgba(15,36,68,0.25);
        }
        .navbar-brand-custom {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 900;
            color: white;
            text-decoration: none;
        }
        .navbar-brand-custom span { color: var(--gold-light); }
        .nav-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
            margin-left: 24px;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--gold-light); }
        .cart-btn {
            background: var(--gold);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            position: relative;
        }
        .cart-btn:hover { background: var(--gold-light); transform: translateY(-1px); }
        .cart-count {
            background: var(--red);
            color: white;
            width: 20px; height: 20px;
            border-radius: 50%;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* HERO */
        .hero-section {
            background: linear-gradient(135deg, var(--navy) 0%, #1a3c6b 50%, #0d3460 100%);
            padding: 64px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(201,147,10,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            color: white;
            line-height: 1.15;
            margin-bottom: 16px;
        }
        .hero-title span { color: var(--gold-light); font-style: italic; }
        .hero-sub { color: rgba(255,255,255,0.65); font-size: 16px; max-width: 480px; }
        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 32px;
        }
        .hero-stat strong {
            display: block;
            font-family: 'Fraunces', serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--gold-light);
        }
        .hero-stat span { color: rgba(255,255,255,0.6); font-size: 13px; }

        /* SEARCH & FILTER */
        .filter-bar {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 63px;
            z-index: 90;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .search-input {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 16px 10px 42px;
            font-size: 14px;
            width: 100%;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-input:focus { border-color: var(--navy-mid); }
        .search-wrap { position: relative; }
        .search-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
        }
        .filter-chip {
            padding: 7px 16px;
            border-radius: 20px;
            border: 1.5px solid var(--border);
            background: white;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .filter-chip:hover, .filter-chip.active {
            background: var(--navy);
            color: white;
            border-color: var(--navy);
        }
        .filter-chips { display: flex; gap: 8px; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 2px; }
        .filter-chips::-webkit-scrollbar { display: none; }

        /* BOOK GRID */
        .catalog-section { padding: 40px 0 80px; }
        .section-header {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 28px;
        }
        .section-title {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 700;
        }
        .section-count { color: var(--muted); font-size: 14px; }
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        /* BOOK CARD */
        .book-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(15,36,68,0.06);
            border: 2px solid transparent;
            transition: all 0.25s;
            cursor: pointer;
            position: relative;
        }
        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(15,36,68,0.14);
            border-color: var(--navy-mid);
        }
        .book-card.selected {
            border-color: var(--gold);
            box-shadow: 0 8px 28px rgba(201,147,10,0.2);
        }
        .book-card.out-of-stock {
            opacity: 0.65;
            cursor: not-allowed;
        }
        .book-card.out-of-stock:hover { transform: none; box-shadow: none; border-color: transparent; }

        .book-cover {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .book-cover-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }
        .book-info { padding: 14px; }
        .book-jenis {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--navy-mid);
            background: rgba(26,60,107,0.08);
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .book-title {
            font-family: 'Fraunces', serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .book-penerbit {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 10px;
        }
        .book-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stok-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
        }
        .stok-ada { background: #d1fae5; color: #065f46; }
        .stok-sedikit { background: #fef3c7; color: #92400e; }
        .stok-habis { background: #fee2e2; color: #991b1b; }
        .pick-btn {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 2px solid var(--navy);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--navy);
            transition: all 0.2s;
        }
        .book-card.selected .pick-btn {
            background: var(--gold);
            border-color: var(--gold);
            color: white;
        }
        .selected-badge {
            position: absolute;
            top: 10px; right: 10px;
            background: var(--gold);
            color: white;
            width: 28px; height: 28px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(201,147,10,0.4);
        }
        .book-card.selected .selected-badge { display: flex; }

        /* FLOATING CART */
        .floating-cart {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--navy);
            color: white;
            border-radius: 20px;
            padding: 16px 28px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 20px 60px rgba(15,36,68,0.4);
            z-index: 200;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            min-width: 380px;
        }
        .floating-cart.show { transform: translateX(-50%) translateY(0); }
        .cart-preview { display: flex; gap: 8px; flex: 1; }
        .cart-mini-cover {
            width: 36px; height: 48px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }
        .cart-mini-placeholder {
            width: 36px; height: 48px;
            border-radius: 6px;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .cart-info strong { display: block; font-size: 14px; font-weight: 700; }
        .cart-info span { font-size: 12px; color: rgba(255,255,255,0.6); }
        .pinjam-btn {
            background: var(--gold);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .pinjam-btn:hover { background: var(--gold-light); transform: scale(1.03); }

        /* MODAL */
        .modal-content { border-radius: 20px; border: none; overflow: hidden; }
        .modal-header-custom {
            background: linear-gradient(135deg, var(--navy), var(--navy-mid));
            padding: 24px 28px;
            color: white;
        }
        .modal-title-custom {
            font-family: 'Fraunces', serif;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .selected-books-list { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .selected-book-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 500;
        }
        .selected-book-chip img {
            width: 32px; height: 40px;
            border-radius: 4px;
            object-fit: cover;
        }
        .remove-chip {
            width: 20px; height: 20px;
            background: #fee2e2;
            color: var(--red);
            border: none;
            border-radius: 50%;
            font-size: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-label { font-weight: 600; font-size: 13.5px; color: #374151; }
        .form-control {
            border-radius: 10px;
            border: 1.5px solid var(--border);
            padding: 11px 14px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(26,60,107,0.08);
        }
        .btn-submit {
            background: linear-gradient(135deg, var(--navy), var(--navy-mid));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px 28px;
            font-size: 15px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        /* SUCCESS STATE */
        .success-state { text-align: center; padding: 20px 0; }
        .success-icon {
            width: 72px; height: 72px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
        }
        .booking-chips { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-top: 12px; }
        .booking-chip {
            background: #f1f5f9;
            border: 1px solid var(--border);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-family: monospace;
            font-weight: 600;
            color: var(--navy);
        }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 80px 0; color: var(--muted); }
        .empty-state i { font-size: 64px; margin-bottom: 16px; color: #cbd5e1; }

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .books-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .floating-cart { min-width: calc(100vw - 32px); }
            .hero-stats { gap: 20px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar-sipusaka">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="/" class="navbar-brand-custom">📚 Si<span>Pusaka</span></a>
        <div class="nav-links d-none d-md-flex align-items-center">
            <a href="/"><i class="fas fa-home me-1"></i> Beranda</a>
            <a href="/cek-status"><i class="fas fa-search me-1"></i> Cek Status</a>
            <a href="/admin/login" style="color:rgba(255,255,255,0.5);font-size:12px">Admin</a>
        </div>
        <button class="cart-btn" onclick="bukaModalPinjam()" id="cartBtn" style="display:none">
            <i class="fas fa-book-open"></i>
            <span id="cartLabel">Pinjam Buku</span>
            <div class="cart-count" id="cartCount">0</div>
        </button>
    </div>
</nav>

<!-- HERO -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-title">
                    Temukan Buku<br><span>Favoritmu</span> di Sini
                </div>
                <p class="hero-sub">
                    Jelajahi koleksi buku perpustakaan digital kami. Pilih hingga 3 buku sekaligus dan ajukan peminjaman dalam satu langkah.
                </p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>{{ $bukus->count() }}</strong>
                        <span>Total Buku</span>
                    </div>
                    <div class="hero-stat">
                        <strong>{{ $bukus->where('stok_tersedia', '>', 0)->count() }}</strong>
                        <span>Tersedia</span>
                    </div>
                    <div class="hero-stat">
                        <strong>{{ $jenisBuku->count() }}</strong>
                        <span>Kategori</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div style="font-size:120px;opacity:0.15;line-height:1">📚</div>
            </div>
        </div>
    </div>
</section>

<!-- FILTER BAR -->
<div class="filter-bar">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="search-wrap">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput"
                           placeholder="Cari judul buku atau penerbit...">
                </div>
            </div>
            <div class="col-md-8">
                <div class="filter-chips">
                    <button class="filter-chip active" onclick="filterJenis('semua', this)">
                        Semua
                    </button>
                    @foreach($jenisBuku as $jenis)
                    <button class="filter-chip" onclick="filterJenis('{{ $jenis }}', this)">
                        {{ $jenis }}
                    </button>
                    @endforeach
                    <button class="filter-chip" onclick="filterJenis('tersedia', this)" style="border-color:#059669;color:#059669">
                        ✅ Tersedia
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CATALOG -->
<section class="catalog-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Koleksi Buku</h2>
            <span class="section-count" id="bookCount">{{ $bukus->count() }} buku</span>
        </div>

        <div class="books-grid" id="booksGrid">
            @forelse($bukus as $buku)
            <div class="book-card {{ $buku->stok_tersedia < 1 ? 'out-of-stock' : '' }}"
                 id="card-{{ $buku->id }}"
                 data-id="{{ $buku->id }}"
                 data-nama="{{ $buku->nama_buku }}"
                 data-penerbit="{{ $buku->penerbit }}"
                 data-jenis="{{ $buku->jenis_buku }}"
                 data-stok="{{ $buku->stok_tersedia }}"
                 data-sampul="{{ $buku->sampul_buku ? asset('storage/'.$buku->sampul_buku) : '' }}"
                 onclick="{{ $buku->stok_tersedia > 0 ? 'togglePilih(this)' : '' }}">

                <div class="selected-badge" id="badge-{{ $buku->id }}">✓</div>

                @if($buku->sampul_buku)
                    <img src="{{ asset('storage/'.$buku->sampul_buku) }}"
                         alt="{{ $buku->nama_buku }}" class="book-cover">
                @else
                    <div class="book-cover-placeholder">📖</div>
                @endif

                <div class="book-info">
                    <span class="book-jenis">{{ $buku->jenis_buku }}</span>
                    <div class="book-title">{{ $buku->nama_buku }}</div>
                    <div class="book-penerbit">{{ $buku->penerbit }}</div>
                    <div class="book-footer">
                        @if($buku->stok_tersedia < 1)
                            <span class="stok-badge stok-habis">Stok Habis</span>
                        @elseif($buku->stok_tersedia <= 3)
                            <span class="stok-badge stok-sedikit">Sisa {{ $buku->stok_tersedia }}</span>
                        @else
                            <span class="stok-badge stok-ada">Tersedia {{ $buku->stok_tersedia }}</span>
                        @endif
                        @if($buku->stok_tersedia > 0)
                        <div class="pick-btn" id="pickbtn-{{ $buku->id }}">
                            <i class="fas fa-plus"></i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column:1/-1">
                <i class="fas fa-book-open"></i>
                <p>Belum ada buku tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FLOATING CART -->
<div class="floating-cart" id="floatingCart">
    <div>
        <div class="cart-info">
            <strong id="floatTitle">0 Buku Dipilih</strong>
            <span>Maks. 3 buku per peminjaman</span>
        </div>
        <div class="cart-preview mt-2" id="cartPreview"></div>
    </div>
    <button class="pinjam-btn" onclick="bukaModalPinjam()">
        <i class="fas fa-paper-plane me-2"></i>Ajukan Pinjam
    </button>
</div>

<!-- MODAL FORM PEMINJAMAN -->
<div class="modal fade" id="modalPinjam" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="modal-title-custom">📋 Form Peminjaman Buku</div>
                        <div style="color:rgba(255,255,255,0.65);font-size:13px;margin-top:4px">
                            Isi data diri untuk mengajukan peminjaman
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4">

                <!-- Form State -->
                <div id="formState">
                    <!-- Buku Dipilih -->
                    <div class="mb-4">
                        <label class="form-label mb-2">
                            <i class="fas fa-book me-2 text-navy"></i>Buku yang Dipilih
                        </label>
                        <div class="selected-books-list" id="selectedBooksList"></div>
                    </div>

                    <div id="alertPinjam"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="f_nama" class="form-control" placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" id="f_nim" class="form-control" placeholder="Nomor Induk Mahasiswa">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" id="f_jurusan" class="form-control" placeholder="Program studi / jurusan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="text" id="f_telepon" class="form-control" placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 d-flex gap-2 align-items-start" style="border-radius:12px;font-size:13px">
                        <i class="fas fa-info-circle mt-1"></i>
                        <div>
                            <strong>Informasi:</strong> NIM harus sudah terdaftar di sistem.
                            Batas peminjaman <strong>7 hari</strong>. Keterlambatan dikenakan denda
                            <strong>Rp10.000/hari</strong>.
                        </div>
                    </div>

                    <button class="btn-submit mt-3" id="btnSubmit" onclick="submitPinjam()">
                        <i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman
                    </button>
                </div>

                <!-- Success State -->
                <div id="successState" style="display:none">
                    <div class="success-state">
                        <div class="success-icon">✅</div>
                        <h5 style="font-family:'Fraunces',serif;font-weight:700;margin-bottom:8px">
                            Peminjaman Berhasil Diajukan!
                        </h5>
                        <p style="color:var(--muted);font-size:14px">
                            Permintaan peminjaman kamu sudah diterima. Tunggu konfirmasi dan QR Code dari admin.
                        </p>
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;margin:16px 0;text-align:left">
                            <div style="font-size:12px;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;font-weight:600">
                                Booking ID kamu:
                            </div>
                            <div class="booking-chips" id="bookingChips"></div>
                        </div>
                        <p style="font-size:12px;color:var(--muted)">
                            Simpan Booking ID di atas untuk cek status peminjaman
                        </p>
                        <button class="btn btn-primary mt-2" onclick="resetForm()" style="border-radius:10px">
                            <i class="fas fa-redo me-2"></i>Pinjam Buku Lagi
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // State management
    let selectedBooks = [];
    const MAX_BOOKS = 3;

    function togglePilih(card) {
        const id = parseInt(card.dataset.id);
        const idx = selectedBooks.findIndex(b => b.id === id);

        if (idx > -1) {
            // Unselect
            selectedBooks.splice(idx, 1);
            card.classList.remove('selected');
        } else {
            if (selectedBooks.length >= MAX_BOOKS) {
                showToast('Maksimal 3 buku yang bisa dipilih sekaligus!', 'warning');
                card.classList.add('shake');
                setTimeout(() => card.classList.remove('shake'), 400);
                return;
            }
            // Select
            selectedBooks.push({
                id: id,
                nama: card.dataset.nama,
                penerbit: card.dataset.penerbit,
                sampul: card.dataset.sampul,
            });
            card.classList.add('selected');
        }

        updateCartUI();
    }

    function updateCartUI() {
        const count = selectedBooks.length;

        // Update navbar btn
        const cartBtn = document.getElementById('cartBtn');
        const cartCount = document.getElementById('cartCount');
        const cartLabel = document.getElementById('cartLabel');
        if (count > 0) {
            cartBtn.style.display = 'flex';
            cartCount.textContent = count;
            cartLabel.textContent = count === 1 ? '1 Dipilih' : count + ' Dipilih';
        } else {
            cartBtn.style.display = 'none';
        }

        // Update floating cart
        const floatingCart = document.getElementById('floatingCart');
        const floatTitle = document.getElementById('floatTitle');
        const cartPreview = document.getElementById('cartPreview');

        if (count > 0) {
            floatingCart.classList.add('show');
            floatTitle.textContent = count + ' Buku Dipilih';

            cartPreview.innerHTML = selectedBooks.map(b => `
                <div title="${b.nama}">
                    ${b.sampul
                        ? `<img src="${b.sampul}" class="cart-mini-cover" alt="${b.nama}">`
                        : `<div class="cart-mini-placeholder">📖</div>`
                    }
                </div>
            `).join('');
        } else {
            floatingCart.classList.remove('show');
        }
    }

    function bukaModalPinjam() {
        if (selectedBooks.length === 0) return;
        renderSelectedBooksList();
        const modal = new bootstrap.Modal(document.getElementById('modalPinjam'));
        modal.show();
    }

    function renderSelectedBooksList() {
        const container = document.getElementById('selectedBooksList');
        container.innerHTML = selectedBooks.map((b, i) => `
            <div class="selected-book-chip" id="chip-${b.id}">
                ${b.sampul
                    ? `<img src="${b.sampul}" alt="${b.nama}">`
                    : `<div style="width:32px;height:40px;background:#e2e8f0;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:14px">📖</div>`
                }
                <div>
                    <div style="font-weight:600;font-size:13px">${b.nama}</div>
                    <div style="font-size:11px;color:var(--muted)">${b.penerbit}</div>
                </div>
                <button class="remove-chip" onclick="removeFromCart(${b.id})" title="Hapus">✕</button>
            </div>
        `).join('');
    }

    function removeFromCart(id) {
        const card = document.getElementById('card-' + id);
        if (card) card.classList.remove('selected');
        selectedBooks = selectedBooks.filter(b => b.id !== id);
        updateCartUI();
        renderSelectedBooksList();
        if (selectedBooks.length === 0) {
            bootstrap.Modal.getInstance(document.getElementById('modalPinjam')).hide();
        }
    }

    function filterJenis(jenis, btn) {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');

        const cards = document.querySelectorAll('.book-card');
        const search = document.getElementById('searchInput').value.toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const matchJenis = jenis === 'semua'
                ? true
                : jenis === 'tersedia'
                ? parseInt(card.dataset.stok) > 0
                : card.dataset.jenis === jenis;
            const matchSearch = card.dataset.nama.toLowerCase().includes(search)
                || card.dataset.penerbit.toLowerCase().includes(search);

            if (matchJenis && matchSearch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('bookCount').textContent = visible + ' buku';
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        const activeChip = document.querySelector('.filter-chip.active');
        const activeJenis = activeChip ? activeChip.textContent.trim() : 'Semua';

        const cards = document.querySelectorAll('.book-card');
        let visible = 0;

        cards.forEach(card => {
            const matchSearch = card.dataset.nama.toLowerCase().includes(search)
                || card.dataset.penerbit.toLowerCase().includes(search);

            if (matchSearch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('bookCount').textContent = visible + ' buku';
    });

    function submitPinjam() {
        const nama     = document.getElementById('f_nama').value.trim();
        const nim      = document.getElementById('f_nim').value.trim();
        const jurusan  = document.getElementById('f_jurusan').value.trim();
        const telepon  = document.getElementById('f_telepon').value.trim();

        if (!nama || !nim || !jurusan || !telepon) {
            document.getElementById('alertPinjam').innerHTML =
                '<div class="alert alert-danger" style="border-radius:10px"><i class="fas fa-exclamation-circle me-2"></i>Semua field wajib diisi!</div>';
            return;
        }

        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

        const data = {
            nama, nim, jurusan,
            no_telepon: telepon,
            buku_ids: selectedBooks.map(b => b.id),
        };

        fetch('{{ route("publik.pinjam") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Tampilkan success state
                document.getElementById('formState').style.display = 'none';
                document.getElementById('successState').style.display = 'block';

                // Tampilkan booking IDs
                const chips = res.booking_ids.map(id =>
                    `<div class="booking-chip">${id}</div>`
                ).join('');
                document.getElementById('bookingChips').innerHTML = chips;

                // Reset selected books
                selectedBooks.forEach(b => {
                    const card = document.getElementById('card-' + b.id);
                    if (card) card.classList.remove('selected');
                });
                selectedBooks = [];
                updateCartUI();
            } else {
                document.getElementById('alertPinjam').innerHTML =
                    `<div class="alert alert-danger" style="border-radius:10px"><i class="fas fa-exclamation-circle me-2"></i>${res.message}</div>`;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
            }
        })
        .catch(() => {
            document.getElementById('alertPinjam').innerHTML =
                '<div class="alert alert-danger" style="border-radius:10px">Terjadi kesalahan jaringan. Coba lagi.</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
        });
    }

    function resetForm() {
        document.getElementById('formState').style.display = 'block';
        document.getElementById('successState').style.display = 'none';
        document.getElementById('f_nama').value = '';
        document.getElementById('f_nim').value = '';
        document.getElementById('f_jurusan').value = '';
        document.getElementById('f_telepon').value = '';
        document.getElementById('alertPinjam').innerHTML = '';
        document.getElementById('btnSubmit').disabled = false;
        document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
        bootstrap.Modal.getInstance(document.getElementById('modalPinjam')).hide();
    }

    function showToast(msg, type) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position:fixed;top:80px;right:20px;z-index:9999;
            background:${type === 'warning' ? '#fef3c7' : '#d1fae5'};
            color:${type === 'warning' ? '#92400e' : '#065f46'};
            border:1px solid ${type === 'warning' ? '#fde68a' : '#a7f3d0'};
            padding:12px 20px;border-radius:12px;font-size:14px;
            font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,0.1);
            animation:slideIn 0.3s ease;
        `;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    let nimTimer;

document.getElementById('f_nim').addEventListener('keyup', function() {
    clearTimeout(nimTimer);
    const nim = this.value.trim();

    nimTimer = setTimeout(() => {
        if (nim.length < 5) return;

        fetch(`{{ route('publik.cek-nim') }}?nim=${nim}`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    // AUTO FILL
                    document.getElementById('f_nama').value     = res.data.nama;
                    document.getElementById('f_jurusan').value  = res.data.jurusan;
                    document.getElementById('f_telepon').value  = res.data.no_telepon;

                    // OPTIONAL: lock biar tidak bisa diubah
                    document.getElementById('f_nama').readOnly = true;
                    document.getElementById('f_jurusan').readOnly = true;
                    document.getElementById('f_telepon').readOnly = true;

                } else {
                    resetFieldMahasiswa();
                }
            })
            .catch(() => {
                resetFieldMahasiswa();
            });

    }, 400);
});

function resetFieldMahasiswa() {
    document.getElementById('f_nama').value = '';
    document.getElementById('f_jurusan').value = '';
    document.getElementById('f_telepon').value = '';

    document.getElementById('f_nama').readOnly = false;
    document.getElementById('f_jurusan').readOnly = false;
    document.getElementById('f_telepon').readOnly = false;
}
</script>

<style>
@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}
.shake {
    animation: shake 0.4s ease;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}
</style>

</body>
</html>