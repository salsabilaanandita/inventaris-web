<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InventarisPro - Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e6e9ef;
            --accent: #2952e3;
            --accent-dark: #1c3bab;
            --panel-1: #2952e3;
            --panel-2: #16215c;
            --surface: #f6f8fc;
        }

        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--ink);
        }

        .page {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ---------- LEFT: content ---------- */
        .side-content {
            flex: 1 1 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 6vh 6vw;
            background: #ffffff;
        }

        .side-content-inner {
            max-width: 460px;
            width: 100%;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 3.5rem;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 0.6rem;
            background: var(--accent);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .brand-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--ink);
            letter-spacing: -0.01em;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: var(--ink);
            margin: 0 0 1.1rem;
        }

        .welcome-subtitle {
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.65;
            margin: 0 0 2.75rem;
            max-width: 40ch;
        }

        .cta-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 3rem;
        }

        .btn-primary-custom {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 0.65rem;
            padding: 0.9rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.15s ease, transform 0.1s ease;
        }

        .btn-primary-custom:hover {
            background: var(--accent-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            background: #fff;
            color: var(--ink);
            border: 1.5px solid var(--line);
            border-radius: 0.65rem;
            padding: 0.9rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-outline-custom:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: var(--surface);
        }

        .feature-strip {
            display: flex;
            gap: 0.75rem;
            padding-top: 2rem;
            border-top: 1px solid var(--line);
        }

        .feature-chip {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.7rem 0.8rem;
            border: 1px solid var(--line);
            border-radius: 0.65rem;
            background: var(--surface);
        }

        .feature-chip i {
            font-size: 1.1rem;
            color: var(--accent);
        }

        .feature-chip span {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--ink);
        }

        /* ---------- RIGHT: illustration panel ---------- */
        .side-panel {
            flex: 1 1 50%;
            position: relative;
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background:
                radial-gradient(60% 55% at 22% 20%, rgba(255,255,255,0.10), transparent 60%),
                linear-gradient(155deg, var(--panel-1) 0%, var(--panel-2) 100%);
            padding: 5vh 4vw;
        }

        @media (min-width: 900px) {
            .side-panel { display: flex; }
        }

        .panel-inner {
            position: relative;
            width: 100%;
            max-width: 480px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .orbit {
            position: relative;
            width: 100%;
            height: 300px;
            margin-bottom: 3rem;
        }

        .orbit-connector {
            position: absolute;
            background: rgba(255,255,255,0.22);
        }
        .orbit-connector.h { height: 2px; }
        .orbit-connector.v { width: 2px; }

        .orbit-bubble {
            position: absolute;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 8px rgba(255,255,255,0.05);
        }

        .orbit-bubble i {
            font-size: 1.3rem;
            color: #fff;
        }

        .bubble-1 { top: 8px;   left: 6%; }
        .bubble-2 { top: 122px; left: 0%; }
        .bubble-3 { top: 236px; left: 6%; }

        .conn-1 { top: 36px;  left: 62px; width: 90px; }
        .conn-2 { top: 150px; left: 56px; width: 96px; }
        .conn-3 { top: 264px; left: 62px; width: 90px; }
        .conn-v { top: 36px; left: 151px; height: 228px; }

        .device-card {
            position: absolute;
            right: 0;
            top: 30px;
            width: 260px;
            background: #ffffff;
            border-radius: 0.85rem;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.35);
            overflow: hidden;
        }

        .device-card__bar {
            display: flex;
            gap: 6px;
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid var(--line);
        }

        .device-card__dot {
            width: 8px; height: 8px; border-radius: 50%;
        }
        .device-card__dot:nth-child(1) { background: #f04949; }
        .device-card__dot:nth-child(2) { background: #f6b93b; }
        .device-card__dot:nth-child(3) { background: #35c46a; }

        .device-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid #f1f3f8;
        }
        .device-row:last-child { border-bottom: none; }

        .device-row__label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--ink);
        }
        .device-row__sub {
            font-size: 0.62rem;
            color: var(--muted);
        }

        .status-pill {
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 999px;
            white-space: nowrap;
        }
        .status-ok   { background: #e3f7ea; color: #1c8c4b; }
        .status-warn { background: #fff2e0; color: #b8710b; }
        .status-info { background: #e7edff; color: #2952e3; }

        .panel-heading {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            line-height: 1.35;
            margin: 0 0 0.6rem;
        }

        .panel-subtext {
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
            text-align: center;
            max-width: 34ch;
            line-height: 1.6;
            margin: 0 0 1.5rem;
        }

        .panel-dots {
            display: flex;
            gap: 0.4rem;
        }

        .panel-dots span {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: rgba(255,255,255,0.35);
        }
        .panel-dots span.active {
            width: 18px;
            border-radius: 999px;
            background: #fff;
        }
    </style>
</head>
<body>

<div class="page">

    <!-- LEFT: content -->
    <div class="side-content">
        <div class="side-content-inner">
            <div class="brand-row">
                <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
                <span class="brand-name">InventarisPro</span>
            </div>

            <h1 class="welcome-title">Kelola inventaris<br>lebih praktis dan rapi.</h1>
            <p class="welcome-subtitle">Platform modern untuk mengelola stok barang, melacak peminjaman, dan memonitor perbaikan &mdash; semua dalam satu tempat.</p>

            <div class="cta-group">
                <a href="{{ route('login') }}" class="btn-primary-custom">Masuk ke akun Anda</a>
                <a href="{{ route('register') }}" class="btn-outline-custom">Buat akun baru</a>
            </div>

            <div class="feature-strip">
                <div class="feature-chip">
                    <i class="bi bi-boxes"></i>
                    <span>Barang</span>
                </div>
                <div class="feature-chip">
                    <i class="bi bi-tools"></i>
                    <span>Repair</span>
                </div>
                <div class="feature-chip">
                    <i class="bi bi-handbag"></i>
                    <span>Lending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: illustration -->
    <div class="side-panel">
        <div class="panel-inner">
            <div class="orbit">
                <div class="orbit-bubble bubble-1"><i class="bi bi-boxes"></i></div>
                <div class="orbit-bubble bubble-2"><i class="bi bi-tools"></i></div>
                <div class="orbit-bubble bubble-3"><i class="bi bi-handbag"></i></div>

                <div class="orbit-connector h conn-1"></div>
                <div class="orbit-connector h conn-2"></div>
                <div class="orbit-connector h conn-3"></div>
                <div class="orbit-connector v conn-v"></div>

                <div class="device-card">
                    <div class="device-card__bar">
                        <span class="device-card__dot"></span>
                        <span class="device-card__dot"></span>
                        <span class="device-card__dot"></span>
                    </div>
                    <div class="device-row">
                        <div>
                            <div class="device-row__label">Laptop Dell XPS</div>
                            <div class="device-row__sub">Unit #INV-0231</div>
                        </div>
                        <span class="status-pill status-ok">Tersedia</span>
                    </div>
                    <div class="device-row">
                        <div>
                            <div class="device-row__label">Proyektor Epson</div>
                            <div class="device-row__sub">Unit #INV-0198</div>
                        </div>
                        <span class="status-pill status-info">Dipinjam</span>
                    </div>
                    <div class="device-row">
                        <div>
                            <div class="device-row__label">Kursi Ergonomis</div>
                            <div class="device-row__sub">Unit #INV-0107</div>
                        </div>
                        <span class="status-pill status-warn">Perbaikan</span>
                    </div>
                </div>
            </div>

            <h2 class="panel-heading">Semua stok, peminjaman &amp;<br>perbaikan dalam satu layar.</h2>
            <p class="panel-subtext">Dashboard yang mudah disesuaikan untuk kebutuhan bisnis Anda.</p>
            <div class="panel-dots">
                <span class="active"></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

</div>

</body>
</html>