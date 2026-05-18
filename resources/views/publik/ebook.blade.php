<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Ebook | {{ $buku->nama_buku }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    /* ── RESET & BASE ────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body {
        height: 100%;
        overflow: hidden;               /* KEY: no scroll on root */
        font-family: 'DM Sans', sans-serif;
        background: #0d1117;
        color: #e2e8f0;
    }

    /* ── LAYOUT SHELL ────────────────────────────────── */
    .app {
        display: grid;
        grid-template-rows: 52px 1fr;   /* topbar + main */
        height: 100vh;
        overflow: hidden;
    }

    /* ── TOPBAR ──────────────────────────────────────── */
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        background: #161b22;
        border-bottom: 1px solid rgba(255,255,255,.07);
        gap: 12px;
        z-index: 10;
    }
    .topbar-brand {
        font-family: 'Lora', serif;
        font-weight: 700;
        font-size: 1rem;
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .topbar-brand-dot {
        width: 8px; height: 8px;
        background: #f0b429;
        border-radius: 50%;
    }
    .topbar-title {
        font-size: 13px;
        font-weight: 500;
        color: rgba(255,255,255,.5);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
        text-align: center;
    }
    .topbar-nav {
        display: flex;
        gap: 4px;
        flex-shrink: 0;
    }
    .topbar-nav a {
        color: rgba(255,255,255,.5);
        font-size: 12.5px;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 6px;
        transition: all .15s;
    }
    .topbar-nav a:hover { background: rgba(255,255,255,.07); color: #fff; }

    /* ── MAIN SPLIT ──────────────────────────────────── */
    .main {
        display: grid;
        grid-template-columns: 300px 1fr;
        height: 100%;
        overflow: hidden;
    }

    /* ── SIDEBAR ─────────────────────────────────────── */
    .sidebar {
        background: #161b22;
        border-right: 1px solid rgba(255,255,255,.07);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
    }
    .sidebar-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,.1) transparent;
    }
    .sidebar-scroll::-webkit-scrollbar { width: 4px; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

    /* Book cover + info */
    .book-cover-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255,255,255,.06);
        margin-bottom: 20px;
    }
    .book-cover {
        width: 100px;
        height: 136px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 16px 40px rgba(0,0,0,.6);
        margin-bottom: 14px;
    }
    .book-cover-ph {
        width: 100px; height: 136px;
        background: #21262d;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin-bottom: 14px;
        box-shadow: 0 16px 40px rgba(0,0,0,.6);
    }
    .book-title {
        font-family: 'Lora', serif;
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        text-align: center;
        line-height: 1.4;
        margin-bottom: 6px;
    }
    .book-meta-row {
        font-size: 12px;
        color: rgba(255,255,255,.4);
        text-align: center;
        line-height: 1.6;
    }
    .book-meta-row strong { color: rgba(255,255,255,.6); }

    /* Access badge */
    .access-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(240,180,41,.1);
        border: 1px solid rgba(240,180,41,.2);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 12px;
        color: #f0b429;
        margin-bottom: 16px;
        line-height: 1.4;
    }
    .access-badge.full {
        background: rgba(16,185,129,.1);
        border-color: rgba(16,185,129,.2);
        color: #10b981;
    }
    .access-badge i { flex-shrink: 0; font-size: 14px; }

    /* NIM input */
    .nim-section label {
        font-size: 11.5px;
        font-weight: 600;
        color: rgba(255,255,255,.4);
        letter-spacing: .5px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 8px;
    }
    .nim-row { display: flex; gap: 8px; }
    .nim-input {
        flex: 1;
        background: #21262d;
        border: 1.5px solid rgba(255,255,255,.1);
        border-radius: 9px;
        padding: 9px 12px;
        font-size: 13px;
        color: #e2e8f0;
        font-family: 'DM Sans', sans-serif;
        outline: none;
        transition: border-color .2s;
        min-width: 0;
    }
    .nim-input:focus { border-color: #f0b429; }
    .nim-input::placeholder { color: rgba(255,255,255,.2); }
    .nim-btn {
        background: #f0b429;
        color: #0d1117;
        border: none;
        border-radius: 9px;
        padding: 9px 14px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
        font-family: 'DM Sans', sans-serif;
    }
    .nim-btn:hover { background: #f5c842; transform: scale(1.03); }
    .nim-msg {
        font-size: 11.5px;
        margin-top: 8px;
        display: none;
        border-radius: 7px;
        padding: 7px 10px;
    }
    .nim-msg.ok  { background: rgba(16,185,129,.12); color: #10b981; display: block; }
    .nim-msg.err { background: rgba(239,68,68,.12);  color: #f87171; display: block; }

    /* Page info */
    .page-stat {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,.06);
    }
    .page-stat-label {
        font-size: 11.5px;
        font-weight: 600;
        color: rgba(255,255,255,.3);
        letter-spacing: .5px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .page-counter {
        font-family: 'Lora', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }
    .page-counter span { font-size: 1rem; color: rgba(255,255,255,.3); font-family: 'DM Sans', sans-serif; font-weight: 400; }
    .page-limit-note {
        font-size: 11.5px;
        color: rgba(255,255,255,.3);
        margin-top: 6px;
        line-height: 1.5;
    }

    /* Sidebar nav buttons */
    .sidebar-nav {
        padding: 16px 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        border-top: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0;
    }
    .nav-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 12px;
        border: 1.5px solid rgba(255,255,255,.1);
        background: transparent;
        border-radius: 10px;
        color: rgba(255,255,255,.6);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        font-family: 'DM Sans', sans-serif;
    }
    .nav-btn:hover:not(:disabled) {
        background: rgba(255,255,255,.07);
        color: #fff;
        border-color: rgba(255,255,255,.2);
    }
    .nav-btn:disabled {
        opacity: .3;
        cursor: not-allowed;
    }

    /* ── PDF VIEWER ──────────────────────────────────── */
    .viewer {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        background: #0d1117;
    }
    .viewer-canvas-wrap {
        flex: 1;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        position: relative;
    }
    #pdfCanvas {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 24px 80px rgba(0,0,0,.7);
        display: block;
    }
    .viewer-statusbar {
        padding: 10px 20px;
        background: #161b22;
        border-top: 1px solid rgba(255,255,255,.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        color: rgba(255,255,255,.35);
        flex-shrink: 0;
    }
    .viewer-statusbar .dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .viewer-statusbar .dot.yellow { background: #f0b429; }

    /* Loading & empty states */
    .viewer-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        gap: 14px;
        color: rgba(255,255,255,.25);
    }
    .viewer-placeholder i { font-size: 52px; }
    .viewer-placeholder p { font-size: 13.5px; text-align: center; max-width: 280px; line-height: 1.6; }
    .spin { animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* No PDF warning */
    .no-pdf-box {
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 14px;
        padding: 24px;
        text-align: center;
        color: #f87171;
        max-width: 340px;
    }
    .no-pdf-box i { font-size: 36px; margin-bottom: 12px; }
    .no-pdf-box h3 { font-family: 'Lora', serif; font-size: 1rem; margin-bottom: 6px; }
    .no-pdf-box p  { font-size: 13px; line-height: 1.6; color: rgba(255,255,255,.4); }

    /* ── MOBILE (stacked) ────────────────────────────── */
    @media (max-width: 768px) {
        html, body { overflow: hidden; }
        .app { grid-template-rows: 52px 1fr; }
        .main { grid-template-columns: 1fr; grid-template-rows: auto 1fr; }

        .sidebar {
            height: auto;
            max-height: 46vh;
            border-right: none;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-scroll { padding: 14px 16px; }

        .book-cover-wrap {
            flex-direction: row;
            align-items: flex-start;
            gap: 14px;
            padding-bottom: 14px;
            margin-bottom: 14px;
        }
        .book-cover, .book-cover-ph { width: 64px; height: 88px; margin-bottom: 0; flex-shrink: 0; }
        .book-title { text-align: left; font-size: 13.5px; }
        .book-meta-row { text-align: left; }
        .page-stat { margin-top: 12px; padding-top: 12px; }
        .page-counter { font-size: 1.4rem; }
        .sidebar-nav { grid-template-columns: 1fr 1fr; padding: 10px 16px; gap: 6px; }
        .nav-btn { font-size: 12px; padding: 8px; }
        .topbar-nav { display: none; }
        .topbar-title { font-size: 12px; text-align: left; }
        .viewer-canvas-wrap { padding: 10px; }
    }
    </style>
</head>
<body>
<div class="app">

    {{-- ── TOPBAR ── --}}
    <header class="topbar">
        <a href="/" class="topbar-brand">
            <div class="topbar-brand-dot"></div>
            SiPusaka
        </a>
        <div class="topbar-title">{{ $buku->nama_buku }}</div>
        <nav class="topbar-nav">
            <a href="/"><i class="fas fa-home me-1"></i>Beranda</a>
            <a href="/cek-status">Cek Status</a>
            <a href="{{ route('publik.join-member.form') }}">Member</a>
            <a href="/admin/login" style="opacity:.4">Admin</a>
        </nav>
    </header>

    <div class="main">

        {{-- ── SIDEBAR ── --}}
        <aside class="sidebar">
            <div class="sidebar-scroll">

                {{-- Cover & Info --}}
                <div class="book-cover-wrap">
                    @if($buku->sampul_buku)
                        <img src="{{ asset('storage/'.$buku->sampul_buku) }}"
                             alt="{{ $buku->nama_buku }}" class="book-cover">
                    @else
                        <div class="book-cover-ph">📘</div>
                    @endif
                    <div>
                        <div class="book-title">{{ $buku->nama_buku }}</div>
                        <div class="book-meta-row">
                            <strong>Penerbit:</strong> {{ $buku->penerbit }}<br>
                            <strong>Jenis:</strong> {{ $buku->jenis_buku }}
                        </div>
                    </div>
                </div>

                {{-- Access status --}}
                <div class="access-badge" id="accessBadge">
                    <i class="fas fa-lock"></i>
                    <div>Akses terbatas — hanya setengah halaman. Masukkan NIM untuk cek status member.</div>
                </div>

                {{-- NIM check --}}
                <div class="nim-section">
                    <label>Cek Status Member</label>
                    <div class="nim-row">
                        <input class="nim-input" id="nimInput" type="text"
                               placeholder="Masukkan NIM"
                               onkeydown="if(event.key==='Enter') checkMember()">
                        <button class="nim-btn" onclick="checkMember()">Cek</button>
                    </div>
                    <div class="nim-msg" id="nimMsg"></div>
                </div>

                {{-- Page stat --}}
                <div class="page-stat">
                    <div class="page-stat-label">Halaman</div>
                    <div class="page-counter">
                        <span id="currentPageDisplay">—</span>
                        <span>/ <span id="totalPageDisplay">—</span></span>
                    </div>
                    <div class="page-limit-note" id="pageLimitNote">Memuat...</div>
                </div>

            </div>

            {{-- Nav buttons --}}
            <div class="sidebar-nav">
                <button class="nav-btn" id="prevBtn" onclick="changePage(-1)" disabled>
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </button>
                <button class="nav-btn" id="nextBtn" onclick="changePage(1)" disabled>
                    Berikutnya <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </aside>

        {{-- ── VIEWER ── --}}
        <div class="viewer">
            <div class="viewer-canvas-wrap" id="viewerWrap">

                @if(!$buku->pdf_path)
                    <div class="viewer-placeholder">
                        <div class="no-pdf-box">
                            <i class="fas fa-file-slash"></i>
                            <h3>Ebook belum tersedia</h3>
                            <p>Admin perlu mengunggah file PDF terlebih dahulu agar buku ini dapat dibaca secara online.</p>
                        </div>
                    </div>
                @else
                    <div class="viewer-placeholder" id="loadingState">
                        <i class="fas fa-circle-notch spin" style="font-size:36px;color:rgba(255,255,255,.2)"></i>
                        <p>Memuat ebook...</p>
                    </div>
                    <canvas id="pdfCanvas" style="display:none;"></canvas>
                @endif

            </div>
            <div class="viewer-statusbar">
                <div>
                    @if($buku->pdf_path)
                        <span class="dot" id="statusDot"></span>
                        <span id="statusText">Memuat...</span>
                    @else
                        <span class="dot yellow"></span>
                        <span>Ebook belum tersedia</span>
                    @endif
                </div>
                <div>SiPusaka · Ebook Reader</div>
            </div>
        </div>

    </div>{{-- /main --}}
</div>{{-- /app --}}

<div class="comment-section" style="background:#161b22;padding:20px;margin:20px;border-radius:8px;">
    <h3 style="color:#fff;margin-bottom:10px;">Komentar (Member Only)</h3>
    <div id="commentList" style="margin-bottom:15px;color:#e2e8f0;"></div>

    <form id="commentForm" style="display:none;">
        <textarea id="commentText" rows="3" placeholder="Tulis komentar..." style="width:100%;border-radius:6px;padding:8px;background:#21262d;color:#e2e8f0;border:none;resize:vertical;"></textarea>
        <button type="submit" style="margin-top:8px;background:#f0b429;color:#0d1117;border:none;padding:8px 12px;border-radius:6px;font-weight:700;cursor:pointer;">Kirim</button>
    </form>
</div>

<script>
    let memberId = null;
    function initComments() {
        fetch("{{ route('publik.ebook.check-member') }}?nim=" + encodeURIComponent(document.getElementById('nimInput').value))
            .then(r => r.json())
            .then(data => {
                if (data.member && data.mahasiswa_id) {
                    memberId = data.mahasiswa_id;
                    document.getElementById('commentForm').style.display = 'block';
                    loadComments();
                }
            });
    }

    function loadComments() {
        fetch("{{ route('publik.ebook.comments.index', ['bukuId' => $buku->id]) }}")
            .then(r => r.json())
            .then(data => {
                const list = document.getElementById('commentList');
                list.innerHTML = '';
                if (data.comments && data.comments.length) {
                    data.comments.forEach(c => {
                        const div = document.createElement('div');
                        div.style.marginBottom='6px';
                        div.innerHTML = `<strong>${c.nama}</strong> <small>${c.created_at}</small><br>${c.komentar}`;
                        list.appendChild(div);
                    });
                } else {
                    list.innerHTML = '<i>Belum ada komentar.</i>';
                }
            });
    }

    document.getElementById('commentForm').addEventListener('submit', function(e){
        e.preventDefault();
        if (!memberId) return;
        fetch("{{ route('publik.ebook.comment.store', ['bukuId' => $buku->id]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                mahasiswa_id: memberId,
                komentar: document.getElementById('commentText').value
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('commentText').value = '';
                loadComments();
            } else {
                alert(data.message || 'Gagal mengirim komentar.');
            }
        });
    });

    // Hook into existing member check
    const originalCheckMember = window.checkMember;
    window.checkMember = function() {
        originalCheckMember();
        setTimeout(initComments, 500);
    };
</script>

<div class="comment-section" style="background:#161b22;padding:20px;margin:20px;border-radius:8px;">
    <h3 style="color:#fff;margin-bottom:10px;">Komentar (Member Only)</h3>
    <div id="commentList" style="margin-bottom:15px;color:#e2e8f0;"></div>

    <form id="commentForm" style="display:none;">
        <textarea id="commentText" rows="3" placeholder="Tulis komentar..." style="width:100%;border-radius:6px;padding:8px;background:#21262d;color:#e2e8f0;border:none;resize:vertical;"></textarea>
        <button type="submit" style="margin-top:8px;background:#f0b429;color:#0d1117;border:none;padding:8px 12px;border-radius:6px;font-weight:700;cursor:pointer;">Kirim</button>
    </form>
</div>

<script>
    let memberId = null;
    function initComments() {
        fetch("{{ route('publik.ebook.check-member') }}?nim=" + encodeURIComponent(document.getElementById('nimInput').value))
            .then(r => r.json())
            .then(data => {
                if (data.member && data.mahasiswa_id) {
                    memberId = data.mahasiswa_id;
                    document.getElementById('commentForm').style.display = 'block';
                    loadComments();
                }
            });
    }

    function loadComments() {
        fetch("{{ route('publik.ebook.comments.index', ['bukuId' => $buku->id]) }}")
            .then(r => r.json())
            .then(data => {
                const list = document.getElementById('commentList');
                list.innerHTML = '';
                if (data.comments && data.comments.length) {
                    data.comments.forEach(c => {
                        const div = document.createElement('div');
                        div.style.marginBottom='6px';
                        div.innerHTML = `<strong>${c.nama}</strong> <small>${c.created_at}</small><br>${c.komentar}`;
                        list.appendChild(div);
                    });
                } else {
                    list.innerHTML = '<i>Belum ada komentar.</i>';
                }
            });
    }

    document.getElementById('commentForm').addEventListener('submit', function(e){
        e.preventDefault();
        if (!memberId) return;
        fetch("{{ route('publik.ebook.comment.store', ['bukuId' => $buku->id]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                mahasiswa_id: memberId,
                komentar: document.getElementById('commentText').value
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('commentText').value = '';
                loadComments();
            } else {
                alert(data.message || 'Gagal mengirim komentar.');
            }
        });
    });

    const originalCheckMember = window.checkMember;
    window.checkMember = function() {
        originalCheckMember();
        setTimeout(initComments, 500);
    };
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.worker.min.js';

const DENDA_PER_HARI = 10000;
const pdfUrl   = '{{ $buku->pdf_path ? route("publik.ebook.file", $buku->id) : "" }}';
let pdfDoc     = null;
let curPage    = 1;
let fullAccess = false;
let pageLimit  = 1;

/* ── FIT CANVAS TO WRAPPER ──────────────────── */
function getWrapperSize() {
    const wrap = document.getElementById('viewerWrap');
    return { w: wrap.clientWidth - 32, h: wrap.clientHeight - 32 };
}

function renderPage(num) {
    if (!pdfDoc) return;
    pdfDoc.getPage(num).then(page => {
        const { w, h } = getWrapperSize();
        const vp0 = page.getViewport({ scale: 1 });
        const scale = Math.min(w / vp0.width, h / vp0.height);
        const vp = page.getViewport({ scale });

        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width  = vp.width;
        canvas.height = vp.height;

        page.render({ canvasContext: ctx, viewport: vp }).promise.then(() => {
            document.getElementById('loadingState') &&
                (document.getElementById('loadingState').style.display = 'none');
            canvas.style.display = 'block';

            document.getElementById('currentPageDisplay').textContent = num;
            document.getElementById('statusDot').className = 'dot';
            document.getElementById('statusText').textContent = `Halaman ${num} dari ${pdfDoc.numPages}`;
            updateNav();
        });
    });
}

function changePage(delta) {
    const next = curPage + delta;
    if (next < 1 || next > pageLimit) return;
    curPage = next;
    renderPage(curPage);
}

function updateNav() {
    document.getElementById('prevBtn').disabled = curPage <= 1;
    document.getElementById('nextBtn').disabled = curPage >= pageLimit;
    document.getElementById('pageLimitNote').textContent = fullAccess
        ? 'Akses penuh tersedia ✓'
        : `Tersedia hingga halaman ${pageLimit} (setengah)`;
}

/* ── MEMBER CHECK ───────────────────────────── */
function checkMember() {
    const nim = document.getElementById('nimInput').value.trim();
    const msg = document.getElementById('nimMsg');
    if (!nim) {
        msg.className = 'nim-msg err';
        msg.textContent = 'Masukkan NIM terlebih dahulu.';
        return;
    }
    msg.className = 'nim-msg';
    msg.textContent = '';

    fetch('{{ route('publik.ebook.check-member') }}?nim=' + encodeURIComponent(nim))
        .then(r => r.json())
        .then(data => {
            fullAccess = data.member && data.status === 'approved';
            pageLimit  = fullAccess ? pdfDoc.numPages : Math.ceil(pdfDoc.numPages / 2);
            curPage    = Math.min(curPage, pageLimit);

            const badge = document.getElementById('accessBadge');
            if (fullAccess) {
                badge.className = 'access-badge full';
                badge.innerHTML = '<i class="fas fa-unlock"></i><div>Akses penuh — semua halaman tersedia.</div>';
                msg.className = 'nim-msg ok';
            } else {
                badge.className = 'access-badge';
                badge.innerHTML = '<i class="fas fa-lock"></i><div>Member belum disetujui. Akses terbatas setengah halaman.</div>';
                msg.className = 'nim-msg err';
            }
            msg.textContent = data.message;
            renderPage(curPage);
        })
        .catch(() => {
            msg.className = 'nim-msg err';
            msg.textContent = 'Gagal memeriksa status. Coba lagi.';
        });
}

/* ── INIT ───────────────────────────────────── */
if (pdfUrl) {
    pdfjsLib.getDocument(pdfUrl).promise.then(doc => {
        pdfDoc    = doc;
        pageLimit = Math.ceil(doc.numPages / 4);
        document.getElementById('totalPageDisplay').textContent = doc.numPages;
        document.getElementById('prevBtn').disabled = false;
        document.getElementById('nextBtn').disabled = false;
        renderPage(1);
    }).catch(() => {
        const ls = document.getElementById('loadingState');
        if (ls) {
            ls.innerHTML = `
                <div class="no-pdf-box" style="background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.2);">
                    <i class="fas fa-exclamation-triangle" style="color:#f87171"></i>
                    <h3>Gagal memuat ebook</h3>
                    <p>File mungkin rusak atau belum tersedia di server.</p>
                </div>`;
        }
    });
}

/* ── RE-RENDER ON RESIZE ────────────────────── */
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (pdfDoc) renderPage(curPage);
    }, 150);
});
</script>
</body>
</html>