<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#6558e8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Gen Plasma') — Gen Plasma</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#f6f4fb; --surface:#fff; --surface-2:#eeeaf7; --text:#1e1b4b; --muted:#5b5780; --line:#e2ddf1; --primary:#6558e8; --primary-dark:#5043d0; --primary-soft:#eeebff; --mint:#dcf8ee; --mint-text:#178568; --peach:#fff0df; --peach-text:#b76b20; --blue-soft:#e4f3ff; --blue-text:#2775ad; --pink-soft:#fde3f0; --pink-text:#b3226b; --shadow:0 4px 20px rgba(30,27,75,.05); --radius:16px; }
        html[data-theme="dark"] { --bg:#14112f; --surface:#1e1b4b; --surface-2:#2a2660; --text:#eeebff; --muted:#a9a5d0; --line:#332f6b; --primary:#9389ff; --primary-dark:#766cf0; --primary-soft:#2e2866; --mint:#203e3a; --mint-text:#7ce0bf; --peach:#45352b; --peach-text:#ffc68b; --blue-soft:#233b50; --blue-text:#8ecfff; --pink-soft:#462c3b; --pink-text:#ff8cc4; --shadow:0 4px 20px rgba(0,0,0,.25); }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font:14px/1.5 "DM Sans",sans-serif; background:var(--bg); color:var(--text); overflow-x:clip; }
        a { color:inherit; text-decoration:none; } button,input,select,textarea { font:inherit; } button { cursor:pointer; }
        .app-shell { min-height:100vh; }.sidebar { width:248px; position:fixed; inset:0 auto 0 0; z-index:30; display:flex; flex-direction:column; padding:24px 16px 16px; background:var(--surface); border-right:1px solid var(--line); transition:transform .25s ease; }
        .brand { display:flex; align-items:center; gap:10px; padding:0 8px 24px; }.brand-mark,.avatar { display:grid; place-items:center; flex-shrink:0; border-radius:12px; font-weight:800; }.brand-mark { width:38px; height:38px; background:var(--primary); color:#fff; font:800 18px "Plus Jakarta Sans"; }.brand strong { display:block; font:800 17px "Plus Jakarta Sans"; letter-spacing:-.5px; }.brand small,.profile-meta span { color:var(--muted); font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; }.main-nav { display:flex; flex:1; flex-direction:column; gap:3px; overflow-y:auto; }.nav-label { padding:14px 12px 6px; color:var(--muted); font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; }.nav-item { display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; color:var(--muted); font-size:13px; font-weight:600; }.nav-item:hover { background:var(--surface-2); color:var(--text); }.nav-item.active { background:var(--primary-soft); color:var(--primary); font-weight:700; }.nav-icon { width:18px; text-align:center; font-size:16px; }.sidebar-bottom { display:flex; align-items:center; padding-top:14px; border-top:1px solid var(--line); }.profile-mini { display:flex; gap:10px; align-items:center; overflow:hidden; }.avatar { width:36px; height:36px; background:var(--primary-soft); color:var(--primary); font-size:13px; }.profile-meta strong { display:block; overflow:hidden; font-size:13px; text-overflow:ellipsis; white-space:nowrap; }.profile-meta span { display:block; font-size:12px; font-weight:400; letter-spacing:normal; text-transform:none; }
        .topbar { position:fixed; top:0; left:248px; right:0; z-index:20; display:flex; align-items:center; justify-content:space-between; height:74px; padding:0 32px; background:var(--surface); border-bottom:1px solid var(--line); box-shadow:var(--shadow); }.breadcrumb { display:flex; gap:8px; align-items:center; color:var(--muted); font-size:13px; }.breadcrumb strong { color:var(--text); }.top-actions { display:flex; gap:10px; align-items:center; }.icon-btn,.mobile-menu { display:grid; width:38px; height:38px; place-items:center; color:var(--text); background:var(--surface); border:1px solid var(--line); border-radius:10px; font-size:16px; }.icon-btn:hover { background:var(--surface-2); }.mobile-menu { display:none; background:var(--surface-2); }.btn-logout { display:flex; gap:6px; align-items:center; padding:8px 14px; color:var(--text); background:var(--surface); border:1px solid var(--line); border-radius:10px; font-size:13px; font-weight:700; }.btn-logout:hover { color:var(--pink-text); background:var(--pink-soft); border-color:var(--pink-soft); }
        .main-area { min-height:100vh; margin-left:248px; padding-top:74px; }.page-wrap { max-width:1400px; margin:auto; padding:28px 32px 40px; }.page-header,.welcome-row { display:flex; gap:16px; align-items:center; justify-content:space-between; margin-bottom:24px; }.page-header h1,.welcome-row h1 { font:800 clamp(22px,2.2vw,28px) "Plus Jakarta Sans"; letter-spacing:-.8px; }.page-header p,.welcome-row p { margin-top:4px; color:var(--muted); font-size:14px; }
        .card,.panel { padding:22px; margin-bottom:20px; background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow); }.grid { display:grid; gap:16px; }.grid-cols-2 { grid-template-columns:repeat(2,minmax(0,1fr)); }.grid-cols-4,.stats-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }.btn { display:inline-flex; gap:6px; align-items:center; justify-content:center; padding:9px 14px; border:0; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; }.btn-primary { color:#fff; background:var(--primary); box-shadow:0 4px 12px rgba(101,88,232,.2); }.btn-primary:hover { background:var(--primary-dark); }.btn-danger { color:var(--pink-text); background:var(--pink-soft); }.btn-success { color:var(--mint-text); background:var(--mint); }.btn-secondary { color:var(--text); background:var(--surface-2); }.badge { display:inline-flex; gap:4px; align-items:center; padding:4px 10px; border-radius:8px; font-size:11px; font-weight:700; }.badge-admin { color:var(--primary); background:var(--primary-soft); }.badge-tutor { color:var(--mint-text); background:var(--mint); }.badge-student { color:var(--blue-text); background:var(--blue-soft); }.alert { padding:12px 16px; margin-bottom:16px; border:1px solid var(--line); border-radius:12px; }.alert-success { color:var(--mint-text); background:var(--mint); }.alert-error { color:var(--pink-text); background:var(--pink-soft); }.form-group { margin-bottom:16px; }.form-group label { display:block; margin-bottom:6px; font-size:13px; font-weight:700; }.form-control { width:100%; padding:10px 12px; color:var(--text); background:var(--surface); border:1px solid var(--line); border-radius:10px; }.form-control:focus { outline:2px solid color-mix(in srgb,var(--primary) 25%,transparent); border-color:var(--primary); }
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
            <a class="brand" href="{{ $role === 'admin' ? route('admin.dashboard') : ($role === 'tutor' ? route('tutor.dashboard') : route('student.dashboard')) }}"><span class="brand-mark">GP</span><span><strong>Gen Plasma</strong><small>{{ $section }} Panel</small></span></a>
            <nav class="main-nav">
                <div class="nav-label">Menu Utama</div>
                @if($role === 'admin')
                    <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="nav-icon">⌂</span>Dashboard</a>
                    <div class="nav-label">Manajemen Bimbel</div>
                    <a class="nav-item {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}" href="{{ route('admin.programs.index') }}"><span class="nav-icon">▦</span>Kelola Program</a>
                    <a class="nav-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}" href="{{ route('admin.schedules.index') }}"><span class="nav-icon">▤</span>Kelola Jadwal</a>
                    <a class="nav-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}" href="{{ route('admin.rooms.index') }}"><span class="nav-icon">▣</span>Kelola Ruangan</a>
                    <a class="nav-item {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}"><span class="nav-icon">▥</span>Kelola Mapel</a>
                    <a class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><span class="nav-icon">◈</span>Kelola Pengguna</a>
                @elseif($role === 'tutor')
                    <a class="nav-item {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}" href="{{ route('tutor.dashboard') }}"><span class="nav-icon">⌂</span>Jadwal Mengajar</a>
                    <a class="nav-item {{ request()->routeIs('tutor.schedules.create') ? 'active' : '' }}" href="{{ route('tutor.schedules.create') }}"><span class="nav-icon">＋</span>Buka Slot Jadwal</a>
                @else
                    <a class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}"><span class="nav-icon">⌂</span>Dashboard</a>
                    <a class="nav-item {{ request()->routeIs('student.bookings.my') ? 'active' : '' }}" href="{{ route('student.bookings.my') }}"><span class="nav-icon">▤</span>Jadwal Belajar</a>
                @endif
            </nav>
            <div class="sidebar-bottom"><div class="profile-mini"><span class="avatar">{{ $initials }}</span><span class="profile-meta"><strong>{{ $user->name }}</strong><span>{{ $section }}</span></span></div></div>
        </aside>
        <div class="scrim" id="scrim"></div>
        <header class="topbar"><button class="mobile-menu" id="menuToggle" aria-label="Buka navigasi">☰</button><div class="breadcrumb"><span>Gen Plasma</span><span>/</span><strong>@yield('title', 'Dashboard')</strong></div><div class="top-actions"><button class="icon-btn" id="themeToggle" type="button" title="Ganti tema">☾</button><form action="{{ route('logout') }}" method="POST">@csrf <button class="btn-logout" type="submit">↪ <span>Keluar</span></button></form></div></header>
        <main class="main-area"><div class="page-wrap">
            @if($errors->any()) <div class="alert alert-error"><ul style="padding-left:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
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
