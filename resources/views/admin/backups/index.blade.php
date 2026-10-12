@extends('layouts.app')

@section('title', 'Backup Data')

@section('content')
<div class="page-header" style="flex-wrap: wrap; gap: 16px;">
    <div>
        <h1>Backup Data</h1>
        <p>Modul pencadangan data sistem dan basis data.</p>
    </div>
</div>

<div class="card" style="padding: 48px 24px; text-align: center; max-width: 680px; margin: 32px auto;">
    <div style="width: 56px; height: 56px; border-radius: 14px; background: var(--surface-2); border: 1px solid var(--line); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; color: var(--primary);">
        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
        </svg>
    </div>
    
    <div style="margin-bottom: 10px;">
        <span class="badge" style="background: var(--surface-2); color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 10px;">
            Dalam Persiapan
        </span>
    </div>

    <h2 style="font: 800 20px 'Plus Jakarta Sans', sans-serif; color: var(--text); margin: 0 0 8px;">
        Fitur Segera Hadir
    </h2>

    <p style="font-size: 13.5px; color: var(--muted); margin: 0 auto; max-width: 460px; line-height: 1.6;">
        Halaman ini disiapkan untuk integrasi pencadangan data berkala dan ekspor laporan ke format spreadsheet pada pembaruan mendatang.
    </p>
</div>
@endsection
