<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPUSAKA') — Sistem Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand-primary: #2563eb;
            --brand-dark:    #1d4ed8;
            --brand-light:   #eff6ff;
            --bg-page:       #f8fafc;
            --bg-white:      #ffffff;
            --text-primary:  #0f172a;
            --text-muted:    #64748b;
            --text-light:    #94a3b8;
            --border:        #e2e8f0;
            --nav-h:         64px;
            --font:          'Plus Jakarta Sans', sans-serif;
            --radius-sm:     8px;
            --radius-md:     12px;
            --radius-lg:     16px;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font);
            background: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* ── NAVBAR ── */
        .sipusaka-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            height: var(--nav-h);
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        /* Brand */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .nav-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--brand-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-brand-icon svg {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: #fff;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .nav-brand-text {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .nav-brand-text span {
            color: var(--brand-primary);
        }

        /* Nav links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all 0.15s;
        }

        .nav-links a svg {
            width: 15px;
            height: 15px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .nav-links a:hover {
            color: var(--brand-primary);
            background: var(--brand-light);
        }

        .nav-links a.active {
            color: var(--brand-primary);
            background: var(--brand-light);
            font-weight: 600;
        }

        /* Hamburger */
        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            border: none;
            background: none;
            border-radius: var(--radius-sm);
        }

        .nav-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--text-primary);
            border-radius: 2px;
            transition: all 0.25s;
        }

        /* ── MAIN ── */
        main {
            flex: 1;
        }

        /* ── FOOTER ── */
        .sipusaka-footer {
            background: var(--bg-white);
            border-top: 1px solid var(--border);
            padding: 1.5rem;
        }

        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-brand-dot {
            width: 8px;
            height: 8px;
            background: var(--brand-primary);
            border-radius: 50%;
        }

        .footer-brand-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        .footer-copy {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* ── STACKS ── */
        @stack('styles')

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .nav-links {
                display: none;
                position: absolute;
                top: var(--nav-h);
                left: 0;
                right: 0;
                background: var(--bg-white);
                border-bottom: 1px solid var(--border);
                flex-direction: column;
                padding: 0.75rem 1rem;
                gap: 0.25rem;
                box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            }

            .nav-links.open { display: flex; }

            .nav-links a { padding: 0.625rem 0.875rem; }

            .nav-toggle { display: flex; }

            .footer-inner { justify-content: center; text-align: center; }
        }
    </style>
    @stack('head_styles')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="sipusaka-nav">
        <div class="nav-inner">
            <a href="{{ url('/') }}" class="nav-brand">
                <div class="nav-brand-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    </svg>
                </div>
                <span class="nav-brand-text">SIPU<span>SAKA</span></span>
            </a>

            <ul class="nav-links" id="navMenu">
                <li>
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                        </svg>
                        Katalog Buku
                    </a>
                </li>
                <li>
                    <a href="{{ route('publik.cek-status') }}" class="{{ request()->routeIs('publik.cek-status*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        Cek Status Peminjaman
                    </a>
                </li>
            </ul>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="sipusaka-footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <div class="footer-brand-dot"></div>
                <span class="footer-brand-name">SIPUSAKA</span>
            </div>
            <span class="footer-copy">© {{ date('Y') }} Sistem Informasi Perpustakaan. Semua hak dilindungi.</span>
        </div>
    </footer>

    <script>
        const toggle = document.getElementById('navToggle');
        const menu = document.getElementById('navMenu');
        toggle?.addEventListener('click', () => menu.classList.toggle('open'));
    </script>

    @stack('scripts')
</body>
</html>