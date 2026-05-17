<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Form Peminjaman Buku – SiPusaka</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
  :root {
    --navy: #0f2444;
    --navy-mid: #1a3c6b;
    --navy-light: #234d82;
    --gold: #c9930a;
    --gold-light: #f0b429;
    --cream: #faf8f3;
    --white: #ffffff;
    --gray: #64748b;
    --gray-light: #f1f5f9;
    --green: #059669;
    --red: #dc2626;
    --border: #e2e8f0;
    --shadow: 0 4px 32px rgba(15,36,68,0.10);
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    color: var(--navy);
    min-height: 100vh;
  }

  /* ── HEADER ── */
  .header {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 60%, #0d3460 100%);
    padding: 36px 40px 32px;
    position: relative;
    overflow: hidden;
  }
  .header::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: radial-gradient(circle, rgba(201,147,10,0.18) 0%, transparent 70%);
    border-radius: 50%;
  }
  .header::after {
    content: '';
    position: absolute;
    bottom: -50px; left: -50px;
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(201,147,10,0.10) 0%, transparent 70%);
    border-radius: 50%;
  }
  .header-inner {
    max-width: 680px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
  }
  .header-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(201,147,10,0.2);
    color: var(--gold-light);
    border: 1px solid rgba(201,147,10,0.35);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 14px;
  }
  .header h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(22px, 3.5vw, 36px);
    color: var(--white);
    margin-bottom: 6px;
    line-height: 1.2;
  }
  .header h1 span { color: var(--gold-light); }
  .header-sub {
    color: rgba(255,255,255,0.55);
    font-size: 13px;
    letter-spacing: 0.5px;
  }

  /* ── PROGRESS BAR ── */
  .progress-wrap {
    max-width: 680px;
    margin: 0 auto;
    padding: 28px 20px 0;
  }
  .progress-steps {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 8px;
  }
  .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
  }
  .step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 17px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: var(--border);
    z-index: 0;
    transition: background 0.4s;
  }
  .step.done:not(:last-child)::after,
  .step.active:not(:last-child)::after {
    background: linear-gradient(to right, var(--gold), var(--border));
  }
  .step.done:not(:last-child)::after { background: var(--gold); }
  .step-circle {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
    color: var(--gray);
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
  }
  .step.active .step-circle {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--gold-light);
    box-shadow: 0 0 0 4px rgba(201,147,10,0.15);
  }
  .step.done .step-circle {
    background: var(--green);
    border-color: var(--green);
    color: white;
  }
  .step-label {
    margin-top: 6px;
    font-size: 11px;
    color: var(--gray);
    font-weight: 500;
    text-align: center;
    white-space: nowrap;
  }
  .step.active .step-label { color: var(--navy); font-weight: 600; }
  .step.done .step-label { color: var(--green); }

  /* ── FORM CARD ── */
  .form-wrap {
    max-width: 680px;
    margin: 24px auto 60px;
    padding: 0 20px;
  }
  .form-card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
  }

  /* ── SECTION PANELS ── */
  .form-section {
    display: none;
    padding: 36px 36px 28px;
    animation: fadeSlide 0.35s ease;
  }
  .form-section.active { display: block; }
  @keyframes fadeSlide {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .section-label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
  }
  .section-num {
    background: var(--navy);
    color: var(--gold-light);
    width: 28px; height: 28px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
    flex-shrink: 0;
  }
  .section-title-text {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: var(--navy);
  }

  /* ── FIELD GROUPS ── */
  .field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
  }
  .field-row.full { grid-template-columns: 1fr; }
  @media (max-width: 520px) { .field-row { grid-template-columns: 1fr; } }

  .field-group { display: flex; flex-direction: column; gap: 6px; }
  .field-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--gray);
  }
  .required-dot {
    display: inline-block;
    width: 5px; height: 5px;
    background: var(--gold);
    border-radius: 50%;
    margin-left: 4px;
    vertical-align: middle;
    margin-bottom: 2px;
  }

  input[type="text"],
  input[type="email"],
  select {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--navy);
    background: var(--gray-light);
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    outline: none;
    appearance: none;
  }
  input:focus, select:focus {
    border-color: var(--navy-light);
    background: white;
    box-shadow: 0 0 0 3px rgba(35,77,130,0.10);
  }
  input.valid { border-color: var(--green); background: #f0fdf4; }
  input.error { border-color: var(--red); background: #fef2f2; }
  select { cursor: pointer; }

  .field-hint {
    font-size: 11.5px;
    color: var(--gray);
    line-height: 1.4;
  }
  .field-error {
    font-size: 11.5px;
    color: var(--red);
    display: none;
  }
  .field-error.show { display: block; }

  /* ── SELECTED BOOK CARD ── */
  .selected-book {
    background: linear-gradient(135deg, #f0f7ff, #e8f4fd);
    border: 1.5px solid #bfd9f2;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
    gap: 14px;
    align-items: flex-start;
    display: flex;
  }
  .selected-book-cover {
    width: 48px; height: 64px;
    border-radius: 6px;
    background: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    color: var(--gold-light);
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    flex-shrink: 0;
  }
  .selected-book-info { flex: 1; }
  .selected-book-title { font-size: 14px; font-weight: 600; color: var(--navy); margin-bottom: 4px; }
  .selected-book-detail { font-size: 12px; color: var(--gray); margin-bottom: 2px; }
  .book-id-tag {
    display: inline-block;
    background: rgba(15,36,68,0.08);
    color: var(--navy-mid);
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 6px;
    margin-top: 4px;
  }

  /* ── TERMS BOX ── */
  .terms-box {
    background: var(--gray-light);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    font-size: 13px;
    line-height: 1.7;
    color: var(--gray);
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid var(--border);
  }
  .terms-box h4 { color: var(--navy); font-size: 13px; margin-bottom: 8px; }
  .terms-item { display: flex; gap: 8px; margin-bottom: 4px; }
  .terms-num {
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px;
    color: var(--gold);
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .checkbox-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
  }
  .custom-checkbox {
    width: 18px; height: 18px;
    border: 2px solid var(--border);
    border-radius: 5px;
    background: white;
    flex-shrink: 0;
    margin-top: 1px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
    position: relative;
  }
  .custom-checkbox.checked {
    background: var(--navy);
    border-color: var(--navy);
  }
  .custom-checkbox.checked::after {
    content: '✓';
    color: var(--gold-light);
    font-size: 11px;
    font-weight: 700;
  }
  .checkbox-label { font-size: 13px; color: var(--navy); line-height: 1.5; cursor: pointer; }
  .checkbox-label span { color: var(--navy-light); font-weight: 600; text-decoration: underline; cursor: pointer; }

  /* ── REVIEW PANEL ── */
  .review-group {
    background: var(--gray-light);
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 14px;
    border: 1px solid var(--border);
  }
  .review-group-title {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--gray);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .review-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    padding: 7px 0;
    border-bottom: 1px solid var(--border);
    font-size: 13.5px;
  }
  .review-row:last-child { border-bottom: none; padding-bottom: 0; }
  .review-key { color: var(--gray); flex-shrink: 0; }
  .review-val { color: var(--navy); font-weight: 500; text-align: right; }
  .review-book-card {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 10px 0;
  }
  .denda-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 14px;
    font-size: 12.5px;
    color: #92400e;
    line-height: 1.5;
  }

  /* ── SUCCESS STATE ── */
  .success-section {
    display: none;
    padding: 48px 36px 40px;
    text-align: center;
    animation: fadeSlide 0.4s ease;
  }
  .success-section.active { display: block; }
  .success-icon {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px;
    margin: 0 auto 20px;
    box-shadow: 0 0 0 12px rgba(5,150,105,0.08);
  }
  .success-section h2 {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: var(--navy);
    margin-bottom: 8px;
  }
  .success-section p { font-size: 14px; color: var(--gray); margin-bottom: 24px; }
  .booking-id-display {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--navy);
    color: var(--gold-light);
    font-family: 'JetBrains Mono', monospace;
    font-size: 18px;
    font-weight: 600;
    padding: 14px 28px;
    border-radius: 12px;
    margin-bottom: 28px;
    letter-spacing: 2px;
  }
  .qr-placeholder {
    width: auto;
    background: var(--gray-light);
    border: 2px dashed var(--border);
    border-radius: 12px;
    margin: 0 auto 20px;
    display: inline-flex; align-items: center; justify-content: center;
    flex-direction: column;
    gap: 6px;
    color: var(--gray);
    font-size: 12px;
    padding: 16px;
  }
  #qr-code-svg img, #qr-code-svg canvas {
    border-radius: 6px;
    display: block;
  }

  /* ── BUTTONS ── */
  .btn-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 36px 28px;
    border-top: 1px solid var(--border);
    gap: 12px;
  }
  .btn {
    padding: 11px 28px;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
    display: flex; align-items: center; gap: 8px;
  }
  .btn-back {
    background: white;
    border: 1.5px solid var(--border);
    color: var(--gray);
  }
  .btn-back:hover { border-color: var(--navy); color: var(--navy); }
  .btn-next {
    background: var(--navy);
    color: var(--gold-light);
    margin-left: auto;
  }
  .btn-next:hover { background: var(--navy-mid); box-shadow: 0 4px 16px rgba(15,36,68,0.25); transform: translateY(-1px); }
  .btn-submit {
    background: linear-gradient(135deg, var(--green), #047857);
    color: white;
    margin-left: auto;
  }
  .btn-submit:hover { box-shadow: 0 4px 16px rgba(5,150,105,0.35); transform: translateY(-1px); }

  /* ── MISC ── */
  .divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 24px 0;
  }
  .nim-status {
    display: none;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 500;
    margin-top: 6px;
  }
  .nim-status.found { display: block; background: #d1fae5; color: #065f46; }
  .nim-status.not-found { display: block; background: #fee2e2; color: #991b1b; }

  .alert {
    display: none;
    margin-top: 16px;
    padding: 12px 14px;
    border-radius: 10px;
    font-size: 13px;
  }
  .alert-danger {
    background: #fee2e2;
    border: 1.5px solid #fca5a5;
    color: #991b1b;
  }
  .alert.show { display: flex !important; }

  @media (max-width: 480px) {
    .form-section { padding: 24px 20px 20px; }
    .btn-row { padding: 16px 20px 20px; }
    .header { padding: 28px 20px 24px; }
  }
</style>
</head>
<body>

<!-- ── HEADER ── -->
<div class="header">
  <div class="header-inner">
    <div class="header-badge">📚 SiPusaka</div>
    <h1>Form <span>Peminjaman Buku</span></h1>
    <p class="header-sub">Perpustakaan Digital · Isi data dengan lengkap dan benar</p>
  </div>
</div>

<!-- ── PROGRESS ── -->
<div class="progress-wrap">
  <div class="progress-steps">
    <div class="step active" id="step-1">
      <div class="step-circle">01</div>
      <div class="step-label">Data Diri</div>
    </div>
    <div class="step" id="step-2">
      <div class="step-circle">02</div>
      <div class="step-label">Konfirmasi</div>
    </div>
  </div>
</div>

<!-- ── FORM CARD ── -->
<div class="form-wrap">
<div class="form-card">

  <!-- ════════════════════════════════════
       STEP 1 · DATA DIRI MAHASISWA
  ════════════════════════════════════ -->
  <div class="form-section active" id="section-1">
    <div class="section-label">
      <div class="section-num">01</div>
      <span class="section-title-text">Data Diri Mahasiswa</span>
    </div>

    @if($buku)
    <div class="selected-book">
      <div class="selected-book-cover">📖</div>
      <div class="selected-book-info">
        <div class="selected-book-title">{{ $buku->nama_buku }}</div>
        <div class="selected-book-detail">{{ $buku->pengarang }}</div>
        <div class="selected-book-detail">{{ $buku->kategori }}</div>
        <div class="book-id-tag">{{ $buku->id }}</div>
      </div>
    </div>
    @endif

    <div class="field-row">
      <div class="field-group">
        <label class="field-label">NIM <span class="required-dot"></span></label>
        <input type="text" id="nim" placeholder="contoh: 12345678" maxlength="12"
               oninput="checkNIM(this)">
        <div class="nim-status" id="nim-status"></div>
        <div class="field-error" id="nim-error">NIM tidak valid atau tidak ditemukan.</div>
      </div>
      <div class="field-group">
        <label class="field-label">Nama Lengkap <span class="required-dot"></span></label>
        <input type="text" id="nama" placeholder="Nama sesuai KTM">
        <div class="field-error" id="nama-error">Nama tidak boleh kosong.</div>
      </div>
    </div>

    <div class="field-row">
      <div class="field-group">
        <label class="field-label">Program Studi <span class="required-dot"></span></label>
        <select id="prodi">
          <option value="">— Pilih Prodi —</option>
          <option>Teknik Informatika</option>
          <option>Sistem Informasi</option>
          <option>Ilmu Komputer</option>
          <option>Teknik Elektro</option>
          <option>Manajemen Informatika</option>
          <option>Akuntansi</option>
          <option>Manajemen</option>
          <option>Hukum</option>
          <option>Psikologi</option>
          <option>Kedokteran</option>
          <option>Farmasi</option>
          <option>Lainnya</option>
        </select>
        <div class="field-error" id="prodi-error">Pilih program studi Anda.</div>
      </div>
    </div>

    <div class="field-row full">
      <div class="field-group">
        <label class="field-label">Email Aktif <span class="required-dot"></span></label>
        <input type="email" id="email" placeholder="email@kampus.ac.id">
        <div class="field-hint">Konfirmasi akan dikirim ke email ini.</div>
        <div class="field-error" id="email-error">Format email tidak valid.</div>
      </div>
    </div>

    <div class="field-row">
        <div class="field-group">
            <label class="field-label">Nomor Telepon / WhatsApp</label>
            <input type="text" id="telp" placeholder="08xxxxxxxxxx" maxlength="14">
            <div class="field-hint">Opsional · Untuk notifikasi jatuh tempo.</div>
        </div>

        <div class="field-group">
            <label class="field-label">Kode Referral <span class="required-dot"></span></label>
            <input type="text" id="referral_token" placeholder="Masukkan kode referral (6 digit)" maxlength="6" required oninput="validateReferralToken(this)">
            <div class="field-hint">Wajib diisi untuk mengakses ebook setelah persetujuan.</div>
            <div class="field-error" id="referral-error">Token referral tidak valid.</div>
        </div>
    </div>

    <!-- Alert for referral token validation -->
    <div class="alert alert-danger" role="alert" id="referral-alert">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:" style="margin-right: 8px; flex-shrink: 0;">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </svg>
      <div id="referral-alert-text">Token referral tidak valid atau tidak ditemukan.</div>
    </div>
  </div>

  <!-- ════════════════════════════════════
       STEP 2 · KONFIRMASI & SYARAT
  ════════════════════════════════════ -->
  <div class="form-section" id="section-2">
    <div class="section-label">
      <div class="section-num">02</div>
      <span class="section-title-text">Ringkasan & Konfirmasi</span>
    </div>

    <!-- Review: Data Mahasiswa -->
    <div class="review-group">
      <div class="review-group-title">🎓 Data Mahasiswa</div>
      <div class="review-row">
        <span class="review-key">NIM</span>
        <span class="review-val" id="rv-nim">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Nama</span>
        <span class="review-val" id="rv-nama">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Prodi</span>
        <span class="review-val" id="rv-prodi">—</span>
      </div>
      <div class="review-row">
        <span class="review-key">Email</span>
        <span class="review-val" id="rv-email">—</span>
      </div>
    </div>

    <!-- Review: Buku -->
    @if($buku)
    <div class="review-group">
      <div class="review-group-title">📚 Buku yang Dipinjam</div>
      <div class="review-book-card">
        <div class="selected-book-cover" style="width:40px;height:52px;font-size:18px">📖</div>
        <div>
          <div class="selected-book-title" style="font-size:14px">{{ $buku->nama_buku }}</div>
          <div class="selected-book-detail" style="font-size:12px">{{ $buku->pengarang }}</div>
          <div class="book-id-tag">{{ $buku->id }}</div>
        </div>
      </div>
    </div>
    @endif

    <!-- Denda Info -->
    <div class="denda-note">
      <span>⚠️</span>
      <span>Durasi peminjaman: <strong>14 hari</strong>. Keterlambatan pengembalian dikenakan denda <strong>Rp10.000 / hari</strong>. Booking ID akan dikirim ke email Anda setelah pengajuan disetujui admin.</span>
    </div>

    <hr class="divider">

    <!-- Terms -->