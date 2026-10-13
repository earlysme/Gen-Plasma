<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#173f3a">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Gen Plasma') — Gen Plasma</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/gen-plasma-alur-selaras-mark.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('images/gen-plasma-alur-selaras-mark.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f5ede0;
            --surface: #ffffff;
            --surface-2: #ebdcc0;
            --text: #173f3a;
            --muted: #5e6f6a;
            --line: #dfcca8;
            --primary: #173f3a;
            --primary-dark: #0f2b27;
            --primary-soft: #f2d5a8;
            --primary-contrast: #ffffff;
            --accent-gold: #d88a32;
            --accent-gold-soft: #faecd5;
            --accent-gold-text: #8e520f;
            --accent-terra: #b74e2c;
            --accent-terra-soft: #fae5de;
            --accent-terra-text: #94381b;
            --mint: #d4eee5;
            --mint-text: #126c59;
            --peach: #fae4cf;
            --peach-text: #b74e2c;
            --blue-soft: #ddeadf;
            --blue-text: #173f3a;
            --pink-soft: #fae5de;
            --pink-text: #b74e2c;
            --shadow: 0 4px 20px rgba(23,63,58,.07);
            --radius: 16px;
        }
        html[data-theme="dark"] {
            --bg: #0b1614;
            --surface: #122421;
            --surface-2: #1a332f;
            --text: #f5ead7;
            --muted: #95a9a4;
            --line: #23423d;
            --primary: #4eae9a;
            --primary-dark: #39917f;
            --primary-soft: #183d36;
            --primary-contrast: #071513;
            --accent-gold: #f0a74f;
            --accent-gold-soft: #362512;
            --accent-gold-text: #fdd7a0;
            --accent-terra: #de6d4a;
            --accent-terra-soft: #381b15;
            --accent-terra-text: #fbc2b2;
            --mint: #153931;
            --mint-text: #72e7ca;
            --peach: #382518;
            --peach-text: #f7b282;
            --blue-soft: #132f38;
            --blue-text: #78cee2;
            --pink-soft: #3b1818;
            --pink-text: #f78585;
            --shadow: 0 4px 24px rgba(0,0,0,.45);
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font:14px/1.5 "DM Sans",sans-serif; background:var(--bg); color:var(--text); overflow-x:clip; }
        a { color:inherit; text-decoration:none; } button,input,select,textarea { font:inherit; } button { cursor:pointer; }
        .app-shell { min-height:100vh; }.sidebar { width:248px; position:fixed; inset:0 auto 0 0; z-index:30; display:flex; flex-direction:column; padding:24px 16px 16px; background:var(--surface); border-right:1px solid var(--line); transition:transform .25s ease; }
        .brand { display:flex; align-items:center; gap:12px; padding:0 8px 24px; text-decoration:none; }.brand-logo { width:38px; height:38px; object-fit:contain; flex-shrink:0; border-radius:10px; }.avatar { display:grid; place-items:center; flex-shrink:0; border-radius:12px; font-weight:800; }.brand strong { display:block; font:800 17px/1.2 "Plus Jakarta Sans"; letter-spacing:-.5px; }.brand small,.profile-meta span { color:var(--muted); font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; } .brand small { margin-top:2px; display:block; } .main-nav { display:flex; flex:1; flex-direction:column; gap:3px; overflow-y:auto; }.nav-label { padding:14px 12px 6px; color:var(--muted); font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; }.nav-item { display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; color:var(--muted); font-size:13px; font-weight:600; }.nav-item:hover { background:var(--surface-2); color:var(--text); }.nav-item.active { background:var(--primary-soft); color:var(--primary); font-weight:700; }.nav-icon { width:18px; height:18px; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; }.sidebar-bottom { display:flex; align-items:center; padding-top:14px; border-top:1px solid var(--line); }.profile-mini { display:flex; gap:10px; align-items:center; overflow:hidden; }.avatar { width:36px; height:36px; background:var(--primary-soft); color:var(--primary); font-size:13px; }.profile-meta strong { display:block; overflow:hidden; font-size:13px; text-overflow:ellipsis; white-space:nowrap; }.profile-meta span { display:block; font-size:12px; font-weight:400; letter-spacing:normal; text-transform:none; }
        .topbar { position:fixed; top:0; left:248px; right:0; z-index:20; display:flex; align-items:center; justify-content:space-between; height:74px; padding:0 32px; background:var(--surface); border-bottom:1px solid var(--line); box-shadow:var(--shadow); }.breadcrumb { display:flex; gap:8px; align-items:center; color:var(--muted); font-size:13px; }.breadcrumb strong { color:var(--text); }.top-actions { display:flex; gap:10px; align-items:center; }.icon-btn,.mobile-menu { display:grid; width:38px; height:38px; place-items:center; color:var(--text); background:var(--surface); border:1px solid var(--line); border-radius:10px; font-size:16px; }.icon-btn:hover { background:var(--surface-2); }.mobile-menu { display:none; background:var(--surface-2); }.btn-logout { display:flex; gap:6px; align-items:center; padding:8px 14px; color:var(--text); background:var(--surface); border:1px solid var(--line); border-radius:10px; font-size:13px; font-weight:700; }.btn-logout:hover { color:var(--pink-text); background:var(--pink-soft); border-color:var(--pink-soft); }
        .main-area { min-height:100vh; margin-left:248px; padding-top:74px; }.page-wrap { max-width:1400px; margin:auto; padding:28px 32px 40px; }.page-header,.welcome-row { display:flex; gap:16px; align-items:center; justify-content:space-between; margin-bottom:24px; }.page-header h1,.welcome-row h1 { font:800 clamp(22px,2.2vw,28px) "Plus Jakarta Sans"; letter-spacing:-.8px; }.page-header p,.welcome-row p { margin-top:4px; color:var(--muted); font-size:14px; }
        .card,.panel { padding:22px; margin-bottom:20px; background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow); }.grid { display:grid; gap:16px; }.grid-cols-2 { grid-template-columns:repeat(2,minmax(0,1fr)); }.grid-cols-4,.stats-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }.btn { display:inline-flex; gap:6px; align-items:center; justify-content:center; padding:9px 14px; border:0; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; }.btn-primary { color:var(--primary-contrast); background:var(--primary); box-shadow:0 4px 12px color-mix(in srgb,var(--primary) 25%,transparent); }.btn-primary:hover { background:var(--primary-dark); }.btn-danger { color:var(--pink-text); background:var(--pink-soft); }.btn-success { color:var(--mint-text); background:var(--mint); }.btn-secondary { color:var(--text); background:var(--surface-2); }
        .btn-icon { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; padding:0; border:1px solid transparent; border-radius:10px; cursor:pointer; text-decoration:none; transition:all .18s ease; flex-shrink:0; position:relative; background:transparent; }
        .btn-icon:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(23,63,58,.08); }
        .btn-icon:active { transform:translateY(0); }
        .btn-icon:disabled { opacity:.4; cursor:not-allowed; pointer-events:none; box-shadow:none; transform:none; }
        .btn-icon svg { width:16px; height:16px; display:block; stroke:currentColor; }
        .btn-icon-edit { color:var(--primary); background:var(--primary-soft); border-color:color-mix(in srgb,var(--primary) 20%,transparent); }
        .btn-icon-edit:hover { background:var(--primary); color:var(--primary-contrast); }
        .btn-icon-danger, .btn-icon-delete { color:var(--pink-text); background:var(--pink-soft); border-color:color-mix(in srgb,var(--pink-text) 20%,transparent); }
        .btn-icon-danger:hover, .btn-icon-delete:hover { background:#b74e2c; color:#fff; }
        .btn-icon-success { color:var(--mint-text); background:var(--mint); border-color:color-mix(in srgb,var(--mint-text) 20%,transparent); }
        .btn-icon-success:hover { background:#126c59; color:#fff; }
        .btn-icon-primary { color:var(--primary-contrast); background:var(--primary); border-color:transparent; box-shadow:0 3px 10px color-mix(in srgb,var(--primary) 25%,transparent); }
        .btn-icon-primary:hover { background:var(--primary-dark); }
        .btn-icon-secondary { color:var(--muted); background:var(--surface-2); border-color:var(--line); }
        .btn-icon-secondary:hover { color:var(--primary); background:var(--primary-soft); border-color:var(--primary-soft); }
        .tab-pill { padding:8px 14px; border:0; border-radius:9px; font-size:13px; font-weight:600; color:var(--muted); background:transparent; cursor:pointer; transition:all .18s ease; }
        .tab-pill:hover { color:var(--text); background:color-mix(in srgb,var(--surface) 60%,transparent); }
        .tab-pill.active { color:var(--primary-contrast); background:var(--primary); font-weight:700; box-shadow:0 2px 8px color-mix(in srgb,var(--primary) 25%,transparent); }
        .btn-consult { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; border-radius:10px; font-size:13px; font-weight:700; background:var(--primary-soft); color:var(--primary); border:1px solid color-mix(in srgb,var(--primary) 25%,transparent); cursor:pointer; transition:all .18s ease; text-decoration:none; }
        .btn-consult:hover { background:var(--primary); color:var(--primary-contrast); }
        .btn-consult:disabled { opacity:.4; cursor:not-allowed; pointer-events:none; }
        .badge { display:inline-flex; gap:4px; align-items:center; padding:4px 10px; border-radius:8px; font-size:11px; font-weight:700; }.badge-admin { color:var(--primary); background:var(--primary-soft); }.badge-tutor { color:var(--accent-terra-text); background:var(--accent-terra-soft); }.badge-student { color:var(--accent-gold-text); background:var(--accent-gold-soft); }.alert { padding:12px 16px; margin-bottom:16px; border:1px solid var(--line); border-radius:12px; }.alert-success { color:var(--mint-text); background:var(--mint); }.alert-error { color:var(--pink-text); background:var(--pink-soft); }.form-group { margin-bottom:16px; }.form-group label { display:block; margin-bottom:6px; font-size:13px; font-weight:700; }.form-control { width:100%; padding:10px 12px; color:var(--text); background:var(--surface); border:1px solid var(--line); border-radius:10px; }.form-control:focus { outline:2px solid color-mix(in srgb,var(--primary) 25%,transparent); border-color:var(--primary); }
        table { width:100%; border-collapse:collapse; } th,td { padding:12px; border-bottom:1px solid var(--line); text-align:left; font-size:13px; } th { color:var(--muted); background:var(--surface-2); font-weight:700; } tr:last-child td { border-bottom:0; }
        .toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px; pointer-events:none; max-width:min(90vw,380px); }
        .toast { display:flex; align-items:center; gap:10px; padding:12px 18px; border-radius:14px; font-size:13px; font-weight:600; line-height:1.4; background:var(--surface); color:var(--text); border:1px solid var(--line); box-shadow:0 10px 30px rgba(30,27,75,.12); pointer-events:auto; transform:translateY(-8px); opacity:0; transition:all .25s ease; }
        .toast.show { opacity:1; transform:translateY(0); }
        .toast-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .toast-success { border-left:4px solid var(--mint-text); }
        .toast-success .toast-dot { background:var(--mint-text); }
        .toast-error { border-left:4px solid var(--pink-text); }
        .toast-error .toast-dot { background:var(--pink-text); }
        .scrim { display:none; }
        .guest-page { display:grid; min-height:100vh; padding:24px; place-items:center; background:radial-gradient(circle at top right,#e6e2ff 0,transparent 32%),var(--bg); }.auth-card { width:min(100%,440px); padding:32px; background:var(--surface); border:1px solid var(--line); border-radius:20px; box-shadow:0 18px 45px rgba(30,27,75,.12); }.auth-brand { display:flex; gap:10px; align-items:center; margin-bottom:28px; }.auth-brand .brand-mark { width:42px; height:42px; }.auth-card h1 { margin-bottom:6px; font:800 24px "Plus Jakarta Sans"; letter-spacing:-.6px; }.auth-card p { margin-bottom:24px; color:var(--muted); }.auth-note { margin-top:18px; padding:14px; color:var(--muted); background:var(--surface-2); border-radius:12px; font-size:12px; }.auth-note ul { margin:8px 0 0 18px; }.auth-note code { color:var(--text); }
        @media (max-width:1080px) { .grid-cols-4,.stats-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } } @media (max-width:850px) { .sidebar { transform:translateX(-100%); }.sidebar.open { transform:translateX(0); }.scrim.open { position:fixed; inset:0; z-index:25; display:block; background:rgba(0,0,0,.4); }.topbar { left:0; padding:0 16px; }.mobile-menu { display:grid; }.main-area { margin-left:0; }.page-wrap { padding:20px 16px; }.grid-cols-2 { grid-template-columns:1fr; } } @media (max-width:560px) { .grid-cols-4,.stats-grid { grid-template-columns:1fr; }.page-header,.welcome-row { align-items:flex-start; flex-direction:column; }.btn-logout span { display:none; } th,td { padding:10px 8px; } }
    </style>
    @stack('styles')
</head>
<body>
@auth
    @php
        $user = Auth::user();
        $role = $user->role;
        $initials = collect(explode(' ', $user->name))->filter()->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->implode('');
        $section = $role === 'student' ? 'Siswa' : ucfirst($role);
    @endphp
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <a class="brand" href="{{ $role === 'admin' ? route('admin.dashboard') : ($role === 'tutor' ? route('tutor.dashboard') : route('student.dashboard')) }}">
                <img class="brand-logo" src="{{ asset('images/gen-plasma-alur-selaras-mark.svg') }}" alt="Gen Plasma">
                <span><strong>Gen Plasma</strong><small>{{ $section }} Panel</small></span>
            </a>
            <nav class="main-nav">
                <div class="nav-label">Menu Utama</div>
                @if($role === 'admin')
                    <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                        </span>
                        Dashboard
                    </a>
                    <div class="nav-label">Manajemen Bimbel</div>
                    <a class="nav-item {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}" href="{{ route('admin.programs.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        </span>
                        Kelola Program
                    </a>
                    <a class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}" href="{{ route('admin.schedules.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </span>
                        Kelola Jadwal
                    </a>
                    <a class="nav-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}" href="{{ route('admin.rooms.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21h16M7 21V4a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v17M14 12v.01"></path></svg>
                        </span>
                        Kelola Ruangan
                    </a>
                    <a class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        </span>
                        Kelola Mapel
                    </a>
                    <a class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </span>
                        Kelola Pengguna
                    </a>
                    <a class="nav-item {{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}" href="{{ route('admin.statistics.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </span>
                        Statistik Mingguan
                    </a>
                    <a class="nav-item {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}" href="{{ route('admin.backups.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        </span>
                        Backup Data
                    </a>
                @elseif($role === 'tutor')
                    <a class="nav-item {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}" href="{{ route('tutor.dashboard') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                        </span>
                        Dashboard
                    </a>
                    <a class="nav-item {{ request()->routeIs('tutor.schedules.*') ? 'active' : '' }}" href="{{ route('tutor.schedules.index') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </span>
                        Jadwal Mengajar
                    </a>
                    <a class="nav-item" href="{{ route('tutor.schedules.index', ['open_modal' => 1]) }}"
                       @if(request()->routeIs('tutor.schedules.index')) onclick="event.preventDefault(); if(typeof openCreateSlot === 'function') openCreateSlot();" @endif>
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        </span>
                        Buka Slot Jadwal
                    </a>
                @else
                    <a class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                        </span>
                        Dashboard
                    </a>
                    <a class="nav-item {{ request()->routeIs('student.bookings.my') ? 'active' : '' }}" href="{{ route('student.bookings.my') }}">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </span>
                        Jadwal Belajar
                    </a>
                @endif
            </nav>
            <div class="sidebar-bottom">
                <div class="profile-mini">
                    @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" style="width:36px;height:36px;border-radius:12px;object-fit:cover;border:1px solid var(--line);flex-shrink:0;">
                    @else
                        <span class="avatar">{{ $initials }}</span>
                    @endif
                    <span class="profile-meta"><strong>{{ $user->name }}</strong><span>{{ $section }}</span></span>
                </div>
            </div>
        </aside>
        <div class="scrim" id="scrim"></div>
        <header class="topbar"><button class="mobile-menu" id="menuToggle" aria-label="Buka navigasi">☰</button><div class="breadcrumb"><span>Gen Plasma</span><span>/</span><strong>@yield('title', 'Dashboard')</strong></div><div class="top-actions"><button class="icon-btn" id="themeToggle" type="button" title="Ganti tema">☾</button><form action="{{ route('logout') }}" method="POST">@csrf <button class="btn-logout" type="submit">↪ <span>Keluar</span></button></form></div></header>
        <main class="main-area"><div class="page-wrap">
            @if(isset($errors) && $errors->any()) <div class="alert alert-error"><ul style="padding-left:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
            @yield('content')
        </div></main>
    </div>
@else
    @yield('content')
@endauth

    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast toast-success show" role="alert">
                <span class="toast-dot"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="toast toast-error show" role="alert">
                <span class="toast-dot"></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <script>
        const themeBtn = document.getElementById('themeToggle');
        if (themeBtn) { let theme = localStorage.getItem('gp_theme') || 'light'; const setTheme = value => { theme = value; document.documentElement.dataset.theme = value; localStorage.setItem('gp_theme', value); themeBtn.textContent = value === 'light' ? '☾' : '☀'; }; setTheme(theme); themeBtn.addEventListener('click', () => setTheme(theme === 'light' ? 'dark' : 'light')); }
        const menu = document.getElementById('menuToggle'), sidebar = document.getElementById('sidebar'), scrim = document.getElementById('scrim');
        if (menu) menu.addEventListener('click', () => { sidebar.classList.toggle('open'); scrim.classList.toggle('open'); });
        if (scrim) scrim.addEventListener('click', () => { sidebar.classList.remove('open'); scrim.classList.remove('open'); });

        setTimeout(() => {
            document.querySelectorAll('.toast.show').forEach(t => {
                t.style.opacity = '0';
                t.style.transform = 'translateY(-10px)';
                setTimeout(() => t.remove(), 300);
            });
        }, 3500);
    </script>
    @stack('scripts')
</body>
</html>
