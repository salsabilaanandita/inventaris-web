<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Pro - Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-width-collapsed: 84px;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --accent-soft: #eef0ff;
            --text-muted: #94a3b8;
            --text-dark: #1e293b;
            --bg-main: #f8fafc;
            --bg-sidebar: #ffffff;
            --border-color: #eef1f6;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }

        [data-bs-theme="dark"] {
            --accent: #818cf8;
            --accent-hover: #a5b4fc;
            --accent-soft: rgba(99, 102, 241, 0.15);
            --text-muted: #a1a1aa;
            --text-dark: #fafafa;
            --bg-main: #09090b; /* True deep black/zinc */
            --bg-sidebar: #131316; /* Slightly lighter for contrast */
            --border-color: #27272a;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.4);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.6), 0 4px 6px -2px rgba(0, 0, 0, 0.5);
            
            /* Bootstrap overrides for dark mode */
            --bs-body-bg: var(--bg-main);
            --bs-body-color: var(--text-dark);
            --bs-tertiary-bg: var(--bg-sidebar);
            --bs-border-color: var(--border-color);
            --bs-light: #27272a; /* Make bg-light components darker */
            --bs-light-rgb: 39, 39, 42;
        }
        
        [data-bs-theme="dark"] .bg-white {
            background-color: var(--bg-sidebar) !important;
        }
        
        [data-bs-theme="dark"] .text-dark {
            color: var(--text-dark) !important;
        }
        
        [data-bs-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }
        
        [data-bs-theme="dark"] .border,
        [data-bs-theme="dark"] .border-top,
        [data-bs-theme="dark"] .border-bottom,
        [data-bs-theme="dark"] .border-start,
        [data-bs-theme="dark"] .border-end {
            border-color: var(--border-color) !important;
        }
        
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: var(--bg-sidebar);
            border-color: var(--border-color);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.4) !important;
        }
        [data-bs-theme="dark"] .dropdown-item {
            color: var(--text-dark);
        }
        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: var(--accent-soft);
            color: var(--text-dark);
        }
        [data-bs-theme="dark"] .list-group-item {
            background-color: var(--bg-sidebar);
            color: var(--text-dark);
            border-color: var(--border-color);
        }
        [data-bs-theme="dark"] .list-group-item:hover {
            background-color: var(--accent-soft);
        }
        [data-bs-theme="dark"] .list-group-item.bg-light {
            background-color: #1a1a24 !important; /* Slightly distinct for unread notifications */
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            background: var(--bg-sidebar);
            width: var(--sidebar-width);
            height: 100vh;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: width 0.25s ease, background-color 0.3s ease, border-color 0.3s ease;
        }

        body.sidebar-collapsed #sidebar { width: var(--sidebar-width-collapsed); }
        body.sidebar-collapsed #main-content { margin-left: var(--sidebar-width-collapsed); }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 1.25rem;
            flex-shrink: 0;
        }

        .brand { display: flex; align-items: center; gap: 10px; overflow: hidden; }
        .brand-icon {
            width: 34px; height: 34px; min-width: 34px;
            border-radius: 10px;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.05rem;
        }
        .brand-name {
            font-weight: 700; font-size: 1rem; color: var(--text-dark);
            white-space: nowrap; opacity: 1; transition: opacity .15s ease;
        }
        body.sidebar-collapsed .brand-name { opacity: 0; width: 0; }

        .sidebar-toggle-btn {
            border: none; background: #f1f5f9; color: #64748b;
            width: 28px; height: 28px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: .2s;
        }
        .sidebar-toggle-btn:hover { background: var(--accent-soft); color: var(--accent); }
        body.sidebar-collapsed .sidebar-toggle-btn i { transform: rotate(180deg); }

        /* ===== NAV ===== */
        .sidebar-scroll {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-heading {
            color: #94a3b8; font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            padding: 1.1rem 1.5rem .5rem;
            white-space: nowrap; overflow: hidden;
            transition: opacity .15s ease;
        }
        body.sidebar-collapsed .sidebar-heading { opacity: 0; height: 0; padding: 0; margin: 0; }

        .nav-link {
            color: var(--text-muted);
            padding: .75rem 1rem;
            border-radius: .75rem;
            margin: .25rem .85rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            font-size: .875rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }
        .nav-link:hover { 
            background: var(--accent-soft); 
            color: var(--accent); 
            transform: translateX(4px);
        }
        .nav-link.active { 
            background: var(--accent); 
            color: #ffffff; 
            font-weight: 600; 
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        [data-bs-theme="dark"] .nav-link.active {
            color: #1e293b;
        }
        .nav-link.disabled { color: var(--border-color); cursor: not-allowed; }
        .nav-link.disabled:hover { background: transparent; color: var(--border-color); transform: none; }

        .sidebar-group-toggle {
            width: calc(100% - 1.7rem);
            border: 0;
            background: transparent;
            text-align: left;
        }
        .sidebar-group-toggle .chevron {
            margin-left: auto;
            margin-right: 0;
            font-size: .85rem;
            transition: transform .2s ease;
        }
        .sidebar-group.is-open .sidebar-group-toggle .chevron { transform: rotate(180deg); }
        .sidebar-submenu { display: none; }
        .sidebar-group.is-open .sidebar-submenu { display: block; }
        .sidebar-submenu .nav-link { margin-left: 1.35rem; }

        .nav-link i {
            font-size: 1.1rem;
            min-width: 22px;
            text-align: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .nav-link span { opacity: 1; transition: opacity .1s ease; }

        body.sidebar-collapsed .nav-link { justify-content: center; margin: .2rem .7rem; }
        body.sidebar-collapsed .nav-link i { margin-right: 0; }
        body.sidebar-collapsed .nav-link span { display: none; }
        body.sidebar-collapsed .sidebar-group-toggle { width: calc(100% - 1.4rem); }
        body.sidebar-collapsed .sidebar-group-toggle .chevron { display: none; }
        body.sidebar-collapsed .sidebar-submenu { display: none; }

        /* tooltip saat collapsed */
        body.sidebar-collapsed .nav-link::after {
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%);
            background: #0f172a;
            color: #fff;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: .75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity .15s ease;
            z-index: 1200;
        }
        body.sidebar-collapsed .nav-link:hover::after { opacity: 1; }

        /* ===== BOTTOM GROUP (FAQ / Support / Report Bug / Settings) ===== */
        .sidebar-footer {
            flex-shrink: 0;
            border-top: 1px solid var(--border-color);
            padding: .6rem 0 1.1rem;
        }

        /* ===== MAIN ===== */
        #main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: margin-left .25s ease; }

        .top-nav {
            background: rgba(var(--bg-main), 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky; top: 0; z-index: 999;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        [data-bs-theme="dark"] .top-nav {
            background: rgba(9, 9, 11, 0.85); /* Matches new deep black */
        }
        .card { 
            border: 1px solid var(--border-color); 
            border-radius: 1.25rem; 
            box-shadow: var(--card-shadow); 
            background-color: var(--bg-sidebar);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease, border-color 0.3s ease;
        }
        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }
        
        .table {
            color: var(--text-dark);
        }
        [data-bs-theme="dark"] .table-light, [data-bs-theme="dark"] .bg-light {
            background-color: #18181b !important; /* Zinc 900 */
            color: #fafafa !important;
        }
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-dark);
        }

        /* Premium Minimalist Table */
        .custom-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            background: transparent;
            padding: 1rem;
            font-weight: 600;
        }
        .custom-table td {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
            vertical-align: middle;
            background: transparent;
        }
        .custom-table tbody tr {
            transition: all 0.2s ease;
        }
        .custom-table tbody tr:hover {
            background-color: var(--accent-soft);
        }
        [data-bs-theme="dark"] .custom-table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        /* Sortable Headers */
        .sortable-header {
            cursor: pointer;
            user-select: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .sortable-header i {
            font-size: 0.8rem;
            color: #cbd5e1;
            transition: color 0.2s;
        }
        .sortable-header:hover i {
            color: var(--accent);
        }

        /* Custom Checkbox */
        .custom-checkbox .form-check-input {
            width: 1.15rem;
            height: 1.15rem;
            border-radius: 0.3rem;
            border-color: #cbd5e1;
            cursor: pointer;
        }
        .custom-checkbox .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        [data-bs-theme="dark"] .custom-checkbox .form-check-input {
            background-color: #18181b;
            border-color: #3f3f46;
        }

        /* Filter Pills & Tabs */
        .filter-pill {
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.4rem 1rem;
            border-radius: 50rem;
            border: 1px solid var(--border-color);
            background-color: var(--bg-sidebar);
            color: var(--text-muted);
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .filter-pill:hover, .filter-pill.active {
            background-color: var(--accent-soft);
            color: var(--accent);
            border-color: var(--accent-soft);
        }
        
        .search-pill {
            border-radius: 50rem;
            padding: 0.5rem 1.25rem 0.5rem 2.5rem !important; /* Force padding to prevent icon overlap */
            border: 1px solid var(--border-color);
            background-color: var(--bg-sidebar);
            font-size: 0.85rem;
            width: 250px;
            max-width: 100%;
            transition: all 0.3s ease;
        }
        .search-pill:focus {
            width: 300px;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-soft);
        }
        .search-wrapper {
            position: relative;
            width: 100%;
            max-width: 250px; /* Keep max width for desktop */
        }
        .search-wrapper:focus-within {
            max-width: 300px;
        }
        .search-wrapper .bi-search {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            z-index: 10;
        }
        
        @media (max-width: 768px) {
            .search-wrapper {
                max-width: 100%;
            }
            .search-pill, .search-pill:focus {
                width: 100%;
            }
        }
        
        /* Modern Inputs */
        .form-control, .form-select {
            border-radius: 0.75rem;
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            background-color: var(--bg-main);
            color: var(--text-dark);
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-soft);
            background-color: var(--bg-sidebar);
        }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select {
            background-color: #09090b; /* matches deep black */
            color: #fafafa;
        }
        [data-bs-theme="dark"] .form-control:focus, [data-bs-theme="dark"] .form-select:focus {
            background-color: #131316;
            border-color: var(--accent);
        }
        
        /* Buttons */
        .btn {
            border-radius: 0.75rem;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: var(--accent);
            border: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
        }

        /* Minimalist Circular Pagination */
        .custom-pagination { gap: 0.25rem; }
        .custom-pagination .page-link {
            border-radius: 50% !important;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid transparent;
            color: var(--text-muted);
            background: transparent;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0;
            transition: all 0.2s ease;
        }
        .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: var(--border-color);
            color: var(--text-dark);
        }
        .custom-pagination .page-item.active .page-link {
            background-color: transparent !important;
            border-color: var(--border-color) !important;
            color: var(--text-dark) !important;
            font-weight: 700;
        }
        .custom-pagination .page-item.disabled .page-link {
            background-color: transparent;
            border-color: transparent;
            color: #cbd5e1;
        }
        [data-bs-theme="dark"] .custom-pagination .page-item.active .page-link {
            color: #fafafa !important;
        }

        @media (max-width: 768px) {
            #sidebar { 
                width: var(--sidebar-width); 
                transform: translateX(-100%);
                z-index: 1050;
            }
            body.mobile-sidebar-open #sidebar {
                transform: translateX(0);
            }
            #main-content { margin-left: 0 !important; width: 100%; }
            body.sidebar-collapsed #sidebar { width: var(--sidebar-width); }
            body.sidebar-collapsed #main-content { margin-left: 0; }
            
            .sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5); z-index: 1040;
                backdrop-filter: blur(2px);
            }
            body.mobile-sidebar-open .sidebar-overlay { display: block; }
        }
    </style>
</head>
<body data-bs-theme="light">
    
<script>
    // Inisialisasi tema sebelum render halaman penuh untuk mencegah flash styling
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-bs-theme', savedTheme);
</script>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="d-flex" id="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <div class="brand-icon"><i class="bi bi-box-fill"></i></div>
                <span class="brand-name">INV-PRO</span>
            </div>
            <button class="sidebar-toggle-btn" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                <i class="bi bi-arrow-left-short"></i>
            </button>
        </div>

        <div class="sidebar-scroll">
            <div class="sidebar-heading">Menu Utama</div>
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}" data-title="Dashboard">
                <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
            </a>

            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang']))
                @php
                    $masterActive = request()->routeIs('categories.*', 'locations.*', 'units.*', 'suppliers.*');
                @endphp
                <div class="sidebar-group {{ $masterActive ? 'is-open' : '' }}" data-sidebar-group>
                    <button class="nav-link sidebar-group-toggle {{ $masterActive ? 'active' : '' }}" type="button" data-title="Data Master" aria-expanded="{{ $masterActive ? 'true' : 'false' }}">
                        <i class="bi bi-database-fill-gear"></i><span>Data Master</span><i class="bi bi-chevron-down chevron"></i>
                    </button>
                    <div class="sidebar-submenu">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}" data-title="Kategori"><i class="bi bi-collection-fill"></i><span>Kategori</span></a>
                        <a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}" data-title="Lokasi / Ruangan"><i class="bi bi-geo-alt-fill"></i><span>Lokasi / Ruangan</span></a>
                        <a class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}" href="{{ route('units.index') }}" data-title="Satuan"><i class="bi bi-rulers"></i><span>Satuan</span></a>
                        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}" data-title="Supplier"><i class="bi bi-truck"></i><span>Supplier</span></a>
                    </div>
                </div>
            @endif

            <div class="sidebar-heading">Operasional</div>

            <a class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}" data-title="Data Barang">
                <i class="bi bi-box-seam-fill"></i> <span>Data Barang</span>
            </a>
            
            <a class="nav-link {{ request()->routeIs('stocks.*') ? 'active' : '' }}" href="{{ route('stocks.index') }}" data-title="Stok Barang">
                <i class="bi bi-box2-fill"></i> <span>Stok Barang</span>
            </a>

            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang']))
                <a class="nav-link {{ request()->routeIs('stock-ins.*') ? 'active' : '' }}" href="{{ route('stock-ins.index') }}" data-title="Barang Masuk">
                    <i class="bi bi-box-arrow-in-down"></i> <span>Barang Masuk</span>
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang']))
                <a class="nav-link {{ request()->routeIs('stock-opnames.*') ? 'active' : '' }}" href="{{ route('stock-opnames.index') }}" data-title="Stok Opname">
                    <i class="bi bi-clipboard-check-fill"></i> <span>Stok Opname</span>
                </a>
                <a class="nav-link {{ request()->routeIs('stock-histories.*') ? 'active' : '' }}" href="{{ route('stock-histories.index') }}" data-title="Riwayat Stok">
                    <i class="bi bi-clock-history"></i> <span>Riwayat Stok</span>
                </a>
            @endif

            <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}" data-title="Pesanan">
                <i class="bi bi-cart-check-fill"></i> <span>Pesanan</span>
            </a>

            <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}" data-title="Pelanggan">
                <i class="bi bi-people-fill"></i> <span>Pelanggan</span>
            </a>

            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang', 'manager']))
                @php $transactionActive = request()->routeIs('lendings.*', 'returns.*', 'damages.*'); @endphp
                <div class="sidebar-group {{ $transactionActive ? 'is-open' : '' }}" data-sidebar-group>
                    <button class="nav-link sidebar-group-toggle {{ $transactionActive ? 'active' : '' }}" type="button" data-title="Transaksi Lain" aria-expanded="{{ $transactionActive ? 'true' : 'false' }}">
                        <i class="bi bi-arrow-left-right"></i><span>Transaksi Lain</span><i class="bi bi-chevron-down chevron"></i>
                    </button>
                    <div class="sidebar-submenu">
                        <a class="nav-link {{ request()->routeIs('lendings.*') ? 'active' : '' }}" href="{{ route('lendings.index') }}" data-title="Peminjaman"><i class="bi bi-arrow-left-right"></i><span>Peminjaman</span></a>
                        <a class="nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}" href="{{ route('returns.index') }}" data-title="Pengembalian"><i class="bi bi-arrow-return-left"></i><span>Pengembalian</span></a>
                        <a class="nav-link {{ request()->routeIs('damages.*') ? 'active' : '' }}" href="{{ route('damages.index') }}" data-title="Rusak/Hilang"><i class="bi bi-exclamation-triangle-fill"></i><span>Rusak/Hilang</span></a>
                    </div>
                </div>
            @endif

            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'manager']))
                <div class="sidebar-heading">Laporan & Analitik</div>
                @php $reportActive = request()->routeIs('reports.*'); @endphp
                <div class="sidebar-group {{ $reportActive ? 'is-open' : '' }}" data-sidebar-group>
                    <button class="nav-link sidebar-group-toggle {{ $reportActive ? 'active' : '' }}" type="button" data-title="Laporan" aria-expanded="{{ $reportActive ? 'true' : 'false' }}">
                        <i class="bi bi-bar-chart-fill"></i><span>Laporan</span><i class="bi bi-chevron-down chevron"></i>
                    </button>
                    <div class="sidebar-submenu">
                        <a class="nav-link {{ request()->routeIs('reports.stock') ? 'active' : '' }}" href="{{ route('reports.stock') }}" data-title="Laporan Stok"><i class="bi bi-bar-chart-fill"></i><span>Laporan Stok</span></a>
                        <a class="nav-link {{ request()->routeIs('reports.lendings') ? 'active' : '' }}" href="{{ route('reports.lendings') }}" data-title="Laporan Peminjaman"><i class="bi bi-file-earmark-text-fill"></i><span>Laporan Peminjaman</span></a>
                        <a class="nav-link {{ request()->routeIs('reports.mutations') ? 'active' : '' }}" href="{{ route('reports.mutations') }}" data-title="Mutasi Barang"><i class="bi bi-arrow-down-up"></i><span>Mutasi Barang</span></a>
                        <a class="nav-link {{ request()->routeIs('reports.low-stock') ? 'active' : '' }}" href="{{ route('reports.low-stock') }}" data-title="Stok Menipis"><i class="bi bi-exclamation-circle-fill"></i><span>Stok Menipis</span></a>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role == 'super_admin')
                <div class="sidebar-heading">Sistem</div>
                @php $managementActive = request()->routeIs('users.*', 'activity-logs.*'); @endphp
                <div class="sidebar-group {{ $managementActive ? 'is-open' : '' }}" data-sidebar-group>
                    <button class="nav-link sidebar-group-toggle {{ $managementActive ? 'active' : '' }}" type="button" data-title="Manajemen" aria-expanded="{{ $managementActive ? 'true' : 'false' }}">
                        <i class="bi bi-people-fill"></i><span>Manajemen</span><i class="bi bi-chevron-down chevron"></i>
                    </button>
                    <div class="sidebar-submenu">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}" data-title="Kelola User"><i class="bi bi-people-fill"></i><span>Kelola User</span></a>
                        <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}" data-title="Log Aktivitas"><i class="bi bi-clock-history"></i><span>Log Aktivitas</span></a>
                    </div>
                </div>
            @endif
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="nav-link w-100 border-0 bg-transparent text-danger" data-title="Keluar">
                    <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </nav>

    <div id="main-content" class="w-100">
        <header class="top-nav px-4 py-2 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-md-none border-0 rounded-circle d-flex align-items-center justify-content-center text-muted" id="mobileMenuToggle" style="width: 40px; height: 40px; background: transparent;">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h6 class="mb-0 fw-bold d-none d-md-block">{{ now()->format('l, d F Y') }}</h6>
            </div>

            <div class="dropdown d-flex align-items-center gap-3">
                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="btn btn-light border-0 rounded-circle d-flex align-items-center justify-content-center text-muted position-relative" data-bs-toggle="dropdown" style="width: 40px; height: 40px; background: transparent;">
                        <i class="bi bi-bell-fill"></i>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="position-absolute badge rounded-pill bg-danger" style="font-size: 0.6rem; top: 2px; right: 2px;">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3" style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <h6 class="mb-0 fw-bold">Notifikasi</h6>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-decoration-none p-0 small" style="font-size: 0.8rem;">Tandai semua dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="list-group list-group-flush">
                            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                @foreach($unreadNotifications as $notif)
                                    <form action="{{ route('notifications.markAsRead', $notif->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="list-group-item list-group-item-action px-3 py-2 border-bottom {{ !$notif->is_read ? 'bg-light' : '' }} text-start w-100 border-0">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1 small fw-bold text-truncate" style="max-width: 200px;">{{ $notif->title }}</h6>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1 small text-muted" style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $notif->message }}
                                            </p>
                                        </button>
                                    </form>
                                @endforeach
                            @else
                                <div class="px-3 py-4 text-center text-muted">
                                    <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                                    <small>Tidak ada notifikasi baru.</small>
                                </div>
                            @endif
                        </div>
                        <div class="px-3 py-2 border-top text-center">
                            <a href="{{ route('notifications.index') }}" class="text-decoration-none small fw-semibold">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>

                <button class="btn btn-light border-0 rounded-circle d-flex align-items-center justify-content-center text-muted" id="themeToggler" style="width: 40px; height: 40px; background: transparent;">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                </button>
                <div class="d-flex align-items-center gap-3 cursor-pointer" data-bs-toggle="dropdown">
                    <div class="text-end d-none d-sm-block">
                        <p class="mb-0 fw-bold small text-dark" style="color: var(--text-dark) !important;">{{ auth()->user()->name }}</p>
                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.6rem;">{{ strtoupper(auth()->user()->role) }}</span>
                    </div>
                    <div class="avatar bg-indigo text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #6366f1;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3">
                    <li><a class="dropdown-item py-2 small" href="{{ route('staff.users.edit') }}"><i class="bi bi-person me-2"></i> Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item py-2 small text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <div class="p-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sidebar collapse/expand dengan state tersimpan di localStorage
    const body = document.body;
    const toggleBtn = document.getElementById('sidebarToggle');
    const STORAGE_KEY = 'sidebarCollapsed';

    function applyState(collapsed) {
        body.classList.toggle('sidebar-collapsed', collapsed);
    }

    // load state saat halaman dibuka
    applyState(localStorage.getItem(STORAGE_KEY) === '1');

    toggleBtn.addEventListener('click', () => {
        const collapsed = !body.classList.contains('sidebar-collapsed');
        applyState(collapsed);
        localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
    });

    const mobileToggleBtn = document.getElementById('mobileMenuToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (mobileToggleBtn) {
        mobileToggleBtn.addEventListener('click', () => {
            body.classList.add('mobile-sidebar-open');
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            body.classList.remove('mobile-sidebar-open');
        });
    }

    document.querySelectorAll('[data-sidebar-group] .sidebar-group-toggle').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const group = toggle.closest('[data-sidebar-group]');
            const isOpen = group.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    // Theme Switcher Logic
    const themeToggler = document.getElementById('themeToggler');
    const themeIcon = document.getElementById('themeIcon');
    
    function updateThemeIcon(theme) {
        if (theme === 'dark') {
            themeIcon.classList.remove('bi-moon-stars-fill');
            themeIcon.classList.add('bi-sun-fill');
            themeIcon.classList.replace('text-muted', 'text-warning');
        } else {
            themeIcon.classList.remove('bi-sun-fill');
            themeIcon.classList.add('bi-moon-stars-fill');
            themeIcon.classList.replace('text-warning', 'text-muted');
        }
    }

    // Set initial icon
    updateThemeIcon(savedTheme);

    // Make search icons clickable to submit the form
    document.querySelectorAll('.search-wrapper .bi-search').forEach(icon => {
        icon.addEventListener('click', function() {
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    themeToggler.addEventListener('click', () => {
        const currentTheme = document.body.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        document.body.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);

        // Jika Chart.js ada di halaman, kita perlu memberikan trigger ke chart untuk update (diurus di dashboard)
        window.dispatchEvent(new Event('themeChanged'));
    });
</script>
@stack('scripts')
</body>
</html>