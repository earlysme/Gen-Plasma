@extends('layouts.app')

@section('title', 'Masuk ke akun')

@push('styles')
<style>
    .login { display:grid; grid-template-columns:1.05fr 1fr; min-height:100vh; }
    .login-art { position:relative; display:flex; flex-direction:column; justify-content:space-between; gap:32px; padding:44px 56px; overflow:hidden; color:#f5ead7; background:radial-gradient(circle at 85% 15%, rgba(216,138,50,0.18) 0%, transparent 45%), radial-gradient(circle at 15% 85%, rgba(183,78,44,0.12) 0%, transparent 45%), linear-gradient(150deg, #0e2622 0%, #173f3a 55%, #1c4b45 100%); }
    .login-art .brand { padding:0; color:#f5ead7; }
    .login-logo { width:280px; max-width:100%; height:auto; }
    .login-art h1 { max-width:520px; margin:0 0 16px; font:800 clamp(30px,3.6vw,46px)/1.1 "Plus Jakarta Sans",sans-serif; letter-spacing:-1.5px; color:#f5ead7; }
    .login-art p { max-width:430px; margin:0; color:#d2deda; font-size:15px; line-height:1.6; }
    .login-art small { color:#8ba59f; font-weight:600; }
    .login-side { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:20px; padding:32px; background:var(--bg); }
    .login-header { width:min(100%,420px); }
    .login-header h2 { margin:0; font:800 24px "Plus Jakarta Sans",sans-serif; letter-spacing:-.8px; }
    .panel-subtitle { margin:4px 0 0; color:var(--muted); font-size:13px; }
    .login-card { display:flex; flex-direction:column; gap:16px; width:min(100%,420px); padding:30px; background:var(--surface); border:1px solid var(--line); border-radius:20px; box-shadow:var(--shadow); }
    .field { display:grid; gap:6px; }
    .field label { font-size:13px; font-weight:700; }
    .field input { width:100%; padding:10px 12px; color:var(--text); background:var(--surface); border:1px solid var(--line); border-radius:10px; }
    .field input:focus { outline:2px solid color-mix(in srgb,var(--primary) 25%,transparent); border-color:var(--primary); }
    .pass { position:relative; }
    .pass input { padding-right:76px; }
    .pass button { position:absolute; top:5px; right:6px; padding:7px 8px; color:var(--primary); background:transparent; border:0; font-size:13px; font-weight:700; }
    .login-note { margin:0; color:var(--muted); font-size:12px; }
    .login-note code { color:var(--text); }
    .demo-accounts { width:min(100%,420px); padding:16px; background:var(--surface-2); border-radius:14px; }
    .demo-accounts strong { display:block; margin-bottom:7px; font-size:12px; }
    .demo-accounts ul { display:grid; gap:4px; margin:0; padding-left:17px; color:var(--muted); font-size:11px; }
    .demo-accounts code { color:var(--text); font-size:11px; }
    @media (max-width:850px) { .login { grid-template-columns:1fr; }.login-art { padding:26px; gap:22px; }.login-side { padding:20px; } }
    @media (max-width:560px) { .login-art { padding:24px 22px 22px; }.login-art h1 { font-size:28px; }.login-art > small { display:none; }.login-side { align-items:center; padding:20px 16px 32px; }.login-card { padding:22px; }.login-card input { min-height:46px; font-size:16px; } }
</style>
@endpush

@section('content')
<main class="login">
    <section class="login-art">
        <a class="brand" href="{{ route('login') }}" aria-label="Gen Plasma"><img class="login-logo" src="{{ asset('images/gen-plasma-alur-selaras-lockup-reversed.svg') }}" alt="Gen Plasma"></a>
        <div>
            <h1>Atur jadwal bimbel, lebih mudah.</h1>
            <p>Sistem penjadwalan terpadu untuk mengelola jadwal les, sesi konsultasi, dan alokasi ruangan bimbingan belajar secara efisien.</p>
        </div>
        <small>© {{ now()->year }} Gen Plasma</small>
    </section>

    <section class="login-side">
        <div class="login-header">
            <h2>Masuk ke akun</h2>
            <p class="panel-subtitle">Masukkan email dan kata sandi Anda.</p>
        </div>

        <form class="login-card" action="{{ route('login.post') }}" method="POST">
            @csrf

            @if($errors->any())
                <div style="background:var(--pink-soft);color:var(--pink-text);padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus placeholder="nama@email.com"></div>
            <div class="field"><label for="password">Kata sandi</label><div class="pass"><input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Masukkan kata sandi"><button type="button" id="passToggle">Lihat</button></div></div>
            <button class="btn btn-primary" type="submit" style="padding:13px">Masuk</button>
            <p class="login-note">Gunakan akun yang sudah terdaftar. Peran akan ditentukan otomatis setelah login.</p>
        </form>

        <div class="demo-accounts">
            <strong>Akun demo</strong>
            <ul>
                <li>Admin: <code>admin@bimbel.test</code> / <code>password</code></li>
                <li>Tutor: <code>idrussalam1503@gmail.com</code> / <code>password</code></li>
                <li>Siswa: <code>nurfauzyahk@gmail.com</code> / <code>password</code></li>
            </ul>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    const passToggle = document.getElementById('passToggle');
    const passwordInput = document.getElementById('password');
    passToggle.addEventListener('click', () => { const showPassword = passwordInput.type === 'password'; passwordInput.type = showPassword ? 'text' : 'password'; passToggle.textContent = showPassword ? 'Sembunyi' : 'Lihat'; });
</script>
@endpush
