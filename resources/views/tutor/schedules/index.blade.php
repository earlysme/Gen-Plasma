@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@push('styles')
    <style>
        .jd {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Page Header */
        .jd-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .jd-header h1 {
            font: 800 clamp(22px, 2.2vw, 26px) "Plus Jakarta Sans", sans-serif;
            letter-spacing: -.5px;
            color: var(--text);
        }

        .jd-header p {
            margin-top: 4px;
            color: var(--muted);
            font-size: 13.5px;
        }

        .jd-btn-create {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--primary-contrast);
            background: var(--primary);
            box-shadow: 0 4px 14px color-mix(in srgb, var(--primary) 25%, transparent);
            border: 0;
            cursor: pointer;
            transition: all .18s ease;
        }

        .jd-btn-create:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 35%, transparent);
        }

        /* Card Umum */
        .jd-card {
            margin-bottom: 20px;
            padding: 20px 22px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .jd-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px 18px;
            margin-bottom: 16px;
        }

        .jd-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .jd-title {
            font: 700 17px/1.3 "Plus Jakarta Sans", sans-serif;
            letter-spacing: -.3px;
            color: var(--text);
            margin: 0;
        }

        .jd-badge-info {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            background: var(--surface-2);
            color: var(--muted);
            font-size: 11.5px;
            font-weight: 600;
        }

        /* Unified Minimal Date Navigator */
        .jd-date-control {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--surface-2);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 4px 6px;
        }

        .jd-datepick {
            position: relative;
        }

        .jd-nav-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--surface);
            color: var(--muted);
            border: 1px solid var(--line);
            text-decoration: none;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            transition: all .15s ease;
        }

        .jd-nav-arrow:hover {
            color: var(--primary);
            background: var(--primary-soft);
            border-color: var(--primary-soft);
        }

        .jd-date-trigger {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 8px;
            background: var(--surface);
            border: 1px solid var(--line);
            color: var(--text);
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s ease;
        }

        .jd-date-trigger svg:first-child {
            color: var(--primary);
            flex-shrink: 0;
        }

        .jd-date-trigger:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .jd-today-btn {
            display: inline-flex;
            align-items: center;
            padding: 6px 11px;
            border-radius: 8px;
            font-size: 11.5px;
            font-weight: 700;
            background: var(--surface);
            color: var(--primary);
            border: 1px solid color-mix(in srgb, var(--primary) 25%, transparent);
            text-decoration: none;
            transition: all .15s ease;
        }

        .jd-today-btn:hover {
            background: var(--primary);
            color: #fff;
        }

        .jd-date-control input[type="date"] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        /* 2. LEGENDA RUANG BIMBEL */
        .jd-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
            font-size: 12.5px;
            color: var(--muted);
        }

        .jd-legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .jd-legend span b {
            color: var(--text);
            font-weight: 700;
            margin-left: 2px;
        }

        .jd-legend i {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 3px;
            border: 2px solid currentColor;
            box-sizing: border-box;
        }

        .jd-legend i.free {
            color: var(--mint-text);
        }

        .jd-legend i.mine {
            color: var(--primary);
        }

        .jd-legend i.other {
            color: var(--muted);
        }

        /* Tabel Ruang Bimbel */
        .jd-scroll {
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--surface);
        }

        .jd-table {
            min-width: 860px;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .jd-table th,
        .jd-table td {
            padding: 0;
            border: 0;
            border-right: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            text-align: center;
        }

        .jd-table th:last-child,
        .jd-table td:last-child {
            border-right: 0;
        }

        .jd-table tr:last-child td {
            border-bottom: 0;
        }

        .jd-table thead th {
            padding: 10px 8px;
            color: var(--text);
            background: var(--surface-2);
            font: 700 13px "Plus Jakarta Sans", sans-serif;
        }

        .jd-table thead th small {
            display: block;
            font-size: 11px;
            color: var(--muted);
            font-weight: 500;
            margin-top: 1px;
        }

        .jd-sess-th {
            position: sticky;
            left: 0;
            z-index: 2;
            width: 130px;
            padding: 10px 12px !important;
            text-align: left !important;
            background: var(--surface-2) !important;
            border-right: 1px solid var(--line) !important;
        }

        .jd-sess-th strong {
            display: block;
            font: 700 13px "Plus Jakarta Sans", sans-serif;
            color: var(--text);
        }

        .jd-sess-th small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            font-weight: 500;
            margin-top: 1px;
        }

        /* Cell Styling */
        .jd-cell {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 64px;
            padding: 6px;
            width: 100%;
            height: 100%;
            border: 0;
            background: transparent;
            text-align: center;
            box-sizing: border-box;
        }

        /* Empty slot cell (+) */
        .jd-cell-empty {
            cursor: pointer;
            background: transparent;
            color: var(--mint-text);
            transition: background .15s ease;
        }

        .jd-cell-empty:hover {
            background: color-mix(in srgb, var(--mint) 30%, transparent);
        }

        .jd-cell-plus {
            font-size: 16px;
            font-weight: 700;
            color: var(--mint-text);
            transition: transform .15s ease;
            line-height: 1;
        }

        .jd-cell-empty:hover .jd-cell-plus {
            transform: scale(1.3);
        }

        /* Occupied: Mine */
        .jd-cell-mine {
            background: var(--primary-soft);
            border-left: 3px solid var(--primary);
            padding: 6px;
        }

        .jd-cell-mine strong {
            display: block;
            color: var(--primary);
            font: 700 12px/1.2 "Plus Jakarta Sans", sans-serif;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .jd-cell-mine span {
            display: block;
            font-size: 11px;
            color: var(--text);
            margin-top: 2px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Occupied: Other Tutor */
        .jd-cell-other {
            background: color-mix(in srgb, var(--surface-2) 65%, transparent);
            padding: 6px;
        }

        .jd-cell-other strong {
            display: block;
            color: var(--text);
            font: 700 12px/1.2 "Plus Jakarta Sans", sans-serif;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .jd-cell-other span {
            display: block;
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Badge Jenjang Pendidikan (SD, SMP, SMA) */
        .badge-level {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
            text-transform: uppercase;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .badge-level-sd {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }
        .badge-level-smp {
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #86EFAC;
        }
        .badge-level-sma {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }
        .badge-level-default {
            background: var(--surface-2);
            color: var(--muted);
            border: 1px solid var(--line);
        }

        /* Chip Tag Tutor Dinamis (Opsi C) */
        .tutor-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            max-width: 100%;
            padding: 1.5px 6px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 700;
            line-height: 1.2;
            border: 1px solid transparent;
            margin-bottom: 2px;
        }
        .tutor-tag-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Agenda Section */
        .jd-agenda-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 14px;
        }

        .jd-agenda-card {
            padding: 16px 18px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            border-left: 4px solid var(--primary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform .18s, box-shadow .18s;
        }

        .jd-agenda-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .jd-agenda-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .jd-agenda-head h3 {
            font: 800 15px "Plus Jakarta Sans", sans-serif;
            color: var(--text);
        }

        .jd-agenda-time {
            color: var(--primary);
            font-size: 12.5px;
            font-weight: 700;
            margin-top: 2px;
        }

        .jd-agenda-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px 14px;
            margin: 12px 0;
            color: var(--muted);
            font-size: 12px;
        }

        .jd-seat-bar-wrap {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .jd-seat-bar {
            width: 50px;
            height: 6px;
            background: var(--surface-2);
            border-radius: 99px;
            overflow: hidden;
        }

        .jd-seat-bar i {
            display: block;
            height: 100%;
            background: var(--primary);
            border-radius: 99px;
        }

        .jd-students {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 12px;
        }

        .jd-chip {
            padding: 3px 9px;
            background: var(--surface-2);
            border-radius: 7px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text);
        }

        .jd-agenda-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--line);
        }

        /* Modal Overlay Buka Slot Baru */
        dialog.jd-dialog {
            width: min(92vw, 580px);
            padding: 0;
            color: var(--text);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, .35);
            margin: auto;
            overflow: hidden;
        }

        dialog.jd-dialog::backdrop {
            background: rgba(15, 12, 35, .65);
            backdrop-filter: blur(8px);
        }

        .jd-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            background: var(--surface-2);
            border-bottom: 1px solid var(--line);
        }

        .jd-modal-title-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .jd-modal-icon {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--primary-soft);
            color: var(--primary);
            flex-shrink: 0;
        }

        .jd-modal-head h3 {
            font: 800 17px/1.2 "Plus Jakarta Sans", sans-serif;
            color: var(--text);
        }

        .jd-modal-head p {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .jd-modal-close {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--surface);
            border: 1px solid var(--line);
            color: var(--muted);
            font-size: 20px;
            cursor: pointer;
            transition: all .15s;
        }

        .jd-modal-close:hover {
            color: var(--pink-text);
            background: var(--pink-soft);
            border-color: var(--pink-soft);
        }

        .jd-modal-body {
            padding: 22px 24px;
        }

        .jd-quick-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 18px;
            border: 1px solid color-mix(in srgb, var(--primary) 20%, transparent);
        }

        .jd-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 14px;
            border-top: 1px solid var(--line);
            margin-top: 18px;
        }

        /* Mobile Card Stack Default (Desktop: Hidden) */
        .jd-mobile-stack {
            display: none;
        }

        @media (max-width: 680px) {
            .jd-card {
                padding: 16px;
            }

            .jd-card-head {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .jd-date-control {
                width: 100%;
                justify-content: space-between;
                box-sizing: border-box;
            }

            .jd-date-trigger {
                flex: 1;
                justify-content: center;
            }

            .jd-legend {
                justify-content: flex-start;
                gap: 10px;
                flex-wrap: wrap;
            }

            /* Sembunyikan tabel geser di mobile */
            .jd-scroll {
                display: none !important;
            }

            /* Tampilkan Kartu Susun (Card Stack) di Mobile */
            .jd-mobile-stack {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .jd-m-session-card {
                background: var(--surface);
                border: 1px solid var(--line);
                border-radius: 14px;
                padding: 12px 14px;
                box-shadow: 0 2px 8px rgba(23, 63, 58, .04);
            }

            .jd-m-session-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-bottom: 9px;
                margin-bottom: 10px;
                border-bottom: 1px dashed var(--line);
            }

            .jd-m-session-time {
                display: flex;
                align-items: center;
                gap: 7px;
                color: var(--text);
                font-size: 13.5px;
            }

            .jd-m-session-time svg {
                color: var(--primary);
            }

            .jd-m-time-pill {
                display: inline-block;
                padding: 3px 8px;
                border-radius: 6px;
                background: var(--surface-2);
                color: var(--muted);
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .2px;
            }

            .jd-m-rooms-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
                gap: 8px;
            }

            .jd-m-room-card {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 9px 10px;
                border-radius: 10px;
                border: 1px solid var(--line);
                background: var(--surface-2);
                min-height: 72px;
                box-sizing: border-box;
                gap: 6px;
            }

            .jd-m-room-card.m-mine {
                background: var(--primary-soft);
                border-color: color-mix(in srgb, var(--primary) 35%, transparent);
            }

            .jd-m-room-card.m-other {
                background: var(--surface-2);
                border-color: var(--line);
            }

            .jd-m-room-card.m-empty {
                background: var(--surface);
                border: 1.5px dashed color-mix(in srgb, var(--line) 90%, var(--primary));
            }

            .jd-m-room-meta {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 4px;
            }

            .jd-m-room-name {
                font-size: 12px;
                font-weight: 700;
                color: var(--text);
            }

            .jd-m-room-cap {
                font-size: 10.5px;
                color: var(--muted);
            }

            .jd-m-btn-empty {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 4px;
                width: 100%;
                padding: 6px 8px;
                border: 1px solid color-mix(in srgb, var(--mint-text) 30%, transparent);
                border-radius: 8px;
                background: color-mix(in srgb, var(--mint) 35%, transparent);
                color: var(--mint-text);
                font-size: 11.5px;
                font-weight: 700;
                cursor: pointer;
                transition: all .15s ease;
            }

            .jd-m-btn-empty:active {
                transform: scale(.96);
                background: var(--mint);
            }

            .jd-m-plus {
                font-size: 14px;
                font-weight: 800;
                line-height: 1;
            }

            .jd-m-badge-booked {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .jd-m-badge-top {
                display: flex;
                align-items: center;
                gap: 5px;
                font-size: 11px;
            }

            .jd-m-badge-booked.is-mine .jd-m-badge-top {
                color: var(--primary);
                font-weight: 700;
            }

            .jd-m-badge-booked.is-other .jd-m-badge-top {
                color: var(--muted);
                font-weight: 600;
            }

            .jd-m-status-dot {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                flex-shrink: 0;
                background: var(--primary);
            }

            .jd-m-badge-booked.is-other .jd-m-status-dot {
                background: var(--muted);
            }

            .jd-m-subject {
                font-size: 11px;
                font-weight: 600;
                color: var(--text);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }

            .jd-agenda-grid {
                grid-template-columns: 1fr;
            }

            dialog.jd-dialog {
                width: 100%;
                max-width: none;
                margin: auto 0 0;
                border-radius: 20px 20px 0 0;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $modalUser = Auth::user();
        $modalSubjects = $modalUser->taughtSubjects()->get();
        if ($modalSubjects->isEmpty()) {
            $modalSubjects = \App\Models\Subject::orderBy('name')->get();
        }
        $modalAutoSubject = $modalSubjects->count() === 1 ? $modalSubjects->first() : null;
        $modalEducationLevels = \App\Models\EducationLevel::all();
        $modalPrograms = \App\Models\Program::all();
        $modalAllRooms = \App\Models\Room::where('is_active', true)->orderBy('code')->get();

        $students =
            $students ??
            ($schedules->contains('status', 'scheduled')
                ? \App\Models\User::role('student')->orderBy('name')->get()
                : collect());

        $statusMap = [
            'completed' => ['Selesai', 'badge-tutor'],
            'cancelled' => ['Batal', 'btn-danger'],
            'scheduled' => ['Aktif', 'badge-student'],
        ];

        // Palet warna dinamis ramah mata untuk Tutor Lain (Opsi C)
        $tutorPalettes = [
            ['bg' => '#EFF6FF', 'text' => '#1D4ED8', 'border' => '#BFDBFE'], // Biru lembut
            ['bg' => '#F5F3FF', 'text' => '#6D28D9', 'border' => '#DDD6FE'], // Ungu
            ['bg' => '#FFF1F2', 'text' => '#BE123C', 'border' => '#FECDD3'], // Rose
            ['bg' => '#F0FDFA', 'text' => '#0F766E', 'border' => '#99F6E4'], // Teal
            ['bg' => '#FFFBEB', 'text' => '#B45309', 'border' => '#FDE68A'], // Amber
            ['bg' => '#FDF4FF', 'text' => '#A21CAF', 'border' => '#F5D0FE'], // Fuchsia
            ['bg' => '#ECFEFF', 'text' => '#0E7490', 'border' => '#A5F3FC'], // Cyan
            ['bg' => '#F1F5F9', 'text' => '#334155', 'border' => '#CBD5E1'], // Slate
        ];

        $getTutorColor = function ($tutorId) use ($tutorPalettes) {
            $idx = abs(crc32((string) $tutorId)) % count($tutorPalettes);
            return $tutorPalettes[$idx];
        };
    @endphp

    <div class="jd">
        {{-- Header Halaman --}}
        <div class="jd-header">
            <div>
                <h1>Jadwal Mengajar</h1>
                <p>Atur sesi, ruang, dan murid bimbingan Anda.</p>
            </div>
            <button type="button" class="jd-btn-create" onclick="openCreateSlot('{{ $checkDate }}')">
                + Buka slot
            </button>
        </div>

        {{-- RUANG BIMBEL (Disatukan dengan Pemilih Tanggal) --}}
        <section class="jd-card">
            <div class="jd-card-head">
                <div class="jd-head-left">
                    <h2 class="jd-title">Ruang bimbel</h2>
                    <span class="jd-badge-info">{{ count($allTimeSlots) }} sesi, {{ count($physicalRooms) }} ruang</span>
                </div>

                {{-- Kontrol Tanggal Minimalis & Elegan --}}
                <div class="jd-date-control">
                    @php
                        $prevDate = \Carbon\Carbon::parse($checkDate)->subDay()->toDateString();
                        $nextDate = \Carbon\Carbon::parse($checkDate)->addDay()->toDateString();
                        $isTodaySelected = \Carbon\Carbon::parse($checkDate)->isToday();
                    @endphp
                    <a href="{{ route('tutor.schedules.index', ['check_date' => $prevDate]) }}" class="jd-nav-arrow" title="Hari sebelumnya">‹</a>
                    
                    <button type="button" id="jd-date-btn" class="jd-date-trigger" data-url="{{ route('tutor.schedules.index') }}" title="Klik untuk pilih tanggal">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" x2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($checkDate)->isoFormat('dddd, D MMMM Y') }}</span>
                        <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" style="opacity:.6">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                    <input type="date" id="jd-date" value="{{ $checkDate }}" tabindex="-1" aria-hidden="true">

                    <a href="{{ route('tutor.schedules.index', ['check_date' => $nextDate]) }}" class="jd-nav-arrow" title="Hari berikutnya">›</a>

                    @if (!$isTodaySelected)
                        <a href="{{ route('tutor.schedules.index', ['check_date' => date('Y-m-d')]) }}" class="jd-today-btn" title="Kembali ke hari ini">Hari ini</a>
                    @endif
                </div>

                {{-- Legenda Status --}}
                <div class="jd-legend">
                    <span><i class="free"></i>Kosong <b>{{ $stats['empty'] }}</b></span>
                    <span><i class="mine"></i>Jadwal saya <b>{{ $stats['mine'] }}</b></span>
                    <span><i class="other"></i>Pengajar lain <b>{{ $stats['others'] }}</b></span>
                    <span style="border-left: 1px solid var(--line); padding-left: 10px; margin-left: 4px; display: inline-flex; align-items: center; gap: 6px;">
                        Jenjang:
                        <span class="badge-level badge-level-sd" style="margin-bottom:0;">SD</span>
                        <span class="badge-level badge-level-smp" style="margin-bottom:0;">SMP</span>
                        <span class="badge-level badge-level-sma" style="margin-bottom:0;">SMA</span>
                    </span>
                </div>
            </div>

            <div class="jd-scroll">
                <table class="jd-table">
                    <thead>
                        <tr>
                            <th class="jd-sess-th" scope="col">Sesi</th>
                            @foreach ($physicalRooms as $rm)
                                <th scope="col">
                                    {{ $rm->name }}
                                    <small>{{ $rm->capacity }} kursi</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allTimeSlots as $ts)
                            <tr>
                                <th class="jd-sess-th" scope="row">
                                    <strong>{{ $ts->name }}</strong>
                                    <small>{{ substr($ts->start_time, 0, 5) }}–{{ substr($ts->end_time, 0, 5) }}</small>
                                </th>
                                @foreach ($physicalRooms as $rm)
                                    @php
                                        $cell = $physicalGrid[$ts->id][$rm->id] ?? null;
                                        $isMine = $cell && (int)$cell->tutor_id === (int)Auth::id();
                                        $lvlCode = $cell && $cell->educationLevel ? strtolower($cell->educationLevel->code) : null;
                                        $lvlName = $cell && $cell->educationLevel ? $cell->educationLevel->name : null;
                                        $tutorColor = ($cell && !$isMine) ? $getTutorColor($cell->tutor_id) : null;
                                    @endphp
                                    <td>
                                        @if (!$cell)
                                            <button type="button" class="jd-cell jd-cell-empty"
                                                onclick="openCreateSlot('{{ $checkDate }}', {{ $ts->id }}, {{ $rm->id }}, '{{ addslashes($rm->name) }}', '{{ addslashes($ts->name) }} ({{ substr($ts->start_time, 0, 5) }}-{{ substr($ts->end_time, 0, 5) }})')"
                                                aria-label="Buka slot {{ $rm->name }}, {{ $ts->name }}"
                                                title="Buka slot {{ $rm->name }}, {{ $ts->name }}">
                                                <span class="jd-cell-plus">+</span>
                                            </button>
                                        @else
                                            <div class="jd-cell {{ $isMine ? 'jd-cell-mine' : 'jd-cell-other' }}">
                                                @if($lvlCode)
                                                    <span class="badge-level badge-level-{{ in_array($lvlCode, ['sd','smp','sma']) ? $lvlCode : 'default' }}">
                                                        {{ $lvlName }}
                                                    </span>
                                                @endif

                                                @if($isMine)
                                                    <strong>Saya</strong>
                                                @else
                                                    <span class="tutor-tag" style="background: {{ $tutorColor['bg'] }}; color: {{ $tutorColor['text'] }}; border-color: {{ $tutorColor['border'] }};">
                                                        <span class="tutor-tag-name">{{ \Illuminate\Support\Str::words($cell->tutor->name, 1, '') }}</span>
                                                    </span>
                                                @endif
                                                <span>{{ $cell->subject->name }}</span>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card Stack (Hanya tampil di HP / layar <= 680px) --}}
            <div class="jd-mobile-stack">
                @foreach ($allTimeSlots as $ts)
                    <div class="jd-m-session-card">
                        <div class="jd-m-session-head">
                            <div class="jd-m-session-time">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <strong>{{ $ts->name }}</strong>
                            </div>
                            <span class="jd-m-time-pill">{{ substr($ts->start_time, 0, 5) }} – {{ substr($ts->end_time, 0, 5) }}</span>
                        </div>

                        <div class="jd-m-rooms-grid">
                            @foreach ($physicalRooms as $rm)
                                @php
                                    $cell = $physicalGrid[$ts->id][$rm->id] ?? null;
                                    $isMine = $cell && (int)$cell->tutor_id === (int)Auth::id();
                                    $lvlCode = $cell && $cell->educationLevel ? strtolower($cell->educationLevel->code) : null;
                                    $lvlName = $cell && $cell->educationLevel ? $cell->educationLevel->name : null;
                                    $tutorColor = ($cell && !$isMine) ? $getTutorColor($cell->tutor_id) : null;
                                @endphp
                                <div class="jd-m-room-card {{ $cell ? ($isMine ? 'm-mine' : 'm-other') : 'm-empty' }}">
                                    <div class="jd-m-room-meta">
                                        <span class="jd-m-room-name">{{ $rm->name }}</span>
                                        <small class="jd-m-room-cap">{{ $rm->capacity }} kursi</small>
                                    </div>

                                    <div class="jd-m-room-body">
                                        @if (!$cell)
                                            <button type="button" class="jd-m-btn-empty"
                                                onclick="openCreateSlot('{{ $checkDate }}', {{ $ts->id }}, {{ $rm->id }}, '{{ addslashes($rm->name) }}', '{{ addslashes($ts->name) }} ({{ substr($ts->start_time, 0, 5) }}-{{ substr($ts->end_time, 0, 5) }})')"
                                                aria-label="Buka slot {{ $rm->name }}, {{ $ts->name }}"
                                                title="Buka slot {{ $rm->name }}, {{ $ts->name }}">
                                                <span class="jd-m-plus">+</span> Buka Slot
                                            </button>
                                        @else
                                            <div class="jd-m-badge-booked {{ $isMine ? 'is-mine' : 'is-other' }}">
                                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 4px; margin-bottom: 2px;">
                                                    <div class="jd-m-badge-top">
                                                        <span class="jd-m-status-dot"></span>
                                                        @if($isMine)
                                                            <strong>Saya</strong>
                                                        @else
                                                            <span class="tutor-tag" style="background: {{ $tutorColor['bg'] }}; color: {{ $tutorColor['text'] }}; border-color: {{ $tutorColor['border'] }}; margin-bottom: 0;">
                                                                <span class="tutor-tag-name">{{ \Illuminate\Support\Str::words($cell->tutor->name, 1, '') }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($lvlCode)
                                                        <span class="badge-level badge-level-{{ in_array($lvlCode, ['sd','smp','sma']) ? $lvlCode : 'default' }}" style="margin-bottom:0;">
                                                            {{ $lvlName }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="jd-m-subject">{{ $cell->subject->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- 3. PRIVAT & KONSULTASI (Jika Ada) --}}
        @if ($dayPrivateConsultSchedules->isNotEmpty())
            <section class="jd-card">
                <div class="jd-card-head">
                    <h2 class="jd-title">Privat &amp; konsultasi</h2>
                    <span class="badge badge-student">{{ $dayPrivateConsultSchedules->count() }} sesi</span>
                </div>
                <div class="jd-agenda-grid">
                    @foreach ($dayPrivateConsultSchedules as $pcs)
                        <div class="jd-agenda-card" style="border-left-color:var(--blue-text);">
                            <div class="jd-agenda-head">
                                <div>
                                    <h3>{{ $pcs->subject->name }}</h3>
                                    <div class="jd-agenda-time">{{ $pcs->timeSlot->name }}</div>
                                </div>
                                <span class="badge badge-student">{{ $pcs->program->name }}</span>
                            </div>
                            <div class="jd-agenda-meta">
                                <span>Pengajar: <strong>{{ $pcs->tutor->name }}</strong></span>
                                <span>Lokasi: {{ $pcs->room->name }}</span>
                                <span>Kapasitas: {{ $pcs->booked_count }}/{{ $pcs->quota_max }} murid</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- 4. PINTASAN KE DASHBOARD AGENDA MENGAJAR --}}
        <section class="card" style="padding:18px 22px;margin-top:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:11px;background:var(--primary-soft);color:var(--primary);display:grid;place-items:center;flex-shrink:0;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" x2="16" y1="13" x2="13"/><line x1="8" x2="16" y1="17" x2="17"/></svg>
                </div>
                <div>
                    <strong style="color:var(--text);font-size:14px;display:block;">Agenda Mengajar &amp; Daftar Murid</strong>
                    <p style="color:var(--muted);font-size:12px;margin:2px 0 0;">Daftar murid bimbingan, absensi sesi selesai, dan status bimbingan kini dipindahkan ke Dashboard Pengajar.</p>
                </div>
            </div>
            <a href="{{ route('tutor.dashboard') }}" class="btn btn-primary" style="font-size:12.5px;">
                Buka Dashboard Pengajar →
            </a>
        </section>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL OVERLAY: BUKA SLOT MENGAJAR LANGSUNG DI HALAMAN    --}}
    {{-- ======================================================== --}}
    <dialog id="dlg-create-slot" class="jd-dialog">
        <div class="jd-modal-head">
            <div class="jd-modal-title-box">
                <div class="jd-modal-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                        <line x1="12" y1="14" x2="12" y2="18"/>
                        <line x1="10" y1="16" x2="14" y2="16"/>
                    </svg>
                </div>
                <div>
                    <h3>Buka Slot Jadwal Baru</h3>
                    <p>Tentukan waktu, materi, jenjang, dan ruangan sesi mengajar.</p>
                </div>
            </div>
            <button type="button" class="jd-modal-close" data-close aria-label="Tutup modal">&times;</button>
        </div>

        <form action="{{ route('tutor.schedules.store') }}" method="POST" class="jd-modal-body">
            @csrf

            <div id="modal-quick-info" class="jd-quick-badge" style="display:none;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span id="modal-quick-text"></span>
            </div>

            <div class="grid grid-cols-2" style="gap:14px;">
                <div class="form-group">
                    <label for="slot-date">Tanggal Mengajar</label>
                    <input type="date" id="slot-date" name="date" class="form-control" value="{{ $checkDate }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="slot-time">Sesi Waktu &amp; Jam</label>
                    <select id="slot-time" name="time_slot_id" class="form-control" required>
                        <option value="">-- Pilih Sesi Jam --</option>
                        @foreach($allTimeSlots as $ts)
                            <option value="{{ $ts->id }}">
                                {{ $ts->name }} ({{ substr($ts->start_time, 0, 5) }} - {{ substr($ts->end_time, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:14px;">
                <div class="form-group">
                    <label for="slot-subject">Mata Pelajaran</label>
                    @if($modalAutoSubject)
                        <div class="form-control" style="background:var(--surface-2);color:var(--text);display:flex;align-items:center;gap:8px;">
                            <strong>{{ $modalAutoSubject->name }}</strong>
                            <span style="font-size:11.5px;color:var(--muted);">(otomatis dipilih)</span>
                        </div>
                        <input type="hidden" name="subject_id" value="{{ $modalAutoSubject->id }}">
                    @else
                        <select id="slot-subject" name="subject_id" class="form-control" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($modalSubjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="form-group">
                    <label for="slot-education">Jenjang Pendidikan</label>
                    <select id="slot-education" name="education_level_id" class="form-control" required>
                        <option value="">-- Pilih Jenjang --</option>
                        @foreach($modalEducationLevels as $lvl)
                            <option value="{{ $lvl->id }}">{{ $lvl->name }} ({{ $lvl->duration_minutes }} Menit)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:14px;">
                <div class="form-group">
                    <label for="slot-program">Jenis Program</label>
                    <select id="slot-program" name="program_id" class="form-control" required onchange="handleModalProgramChange(this)">
                        <option value="">-- Pilih Program --</option>
                        @foreach($modalPrograms as $prg)
                            <option value="{{ $prg->id }}" data-code="{{ $prg->code }}" data-is-private="{{ $prg->is_private ? '1' : '0' }}" data-requires-room="{{ $prg->requires_room ? '1' : '0' }}">
                                {{ $prg->name }} {{ $prg->is_private ? '(Kunci 1 Murid)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="slot-room">Ruangan</label>
                    <select id="slot-room" name="room_id" class="form-control" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($modalAllRooms as $rm)
                            <option value="{{ $rm->id }}" data-code="{{ $rm->code }}" data-physical="{{ $rm->is_physical ? '1' : '0' }}" data-cap="{{ $rm->capacity }}">
                                {{ $rm->name }} {{ $rm->is_physical ? '(Kapasitas: '.$rm->capacity.' orang)' : '(Non-Fisik Bimbel)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="modal-program-helper-note" style="display:none;margin-bottom:14px;padding:12px 14px;border-radius:10px;font-size:12.5px;line-height:1.45;"></div>

            <div class="form-group">
                <label for="slot-quota">Kapasitas Maksimal Siswa (Opsional / Default ikut Ruangan)</label>
                <input type="number" id="slot-quota" name="quota_max" class="form-control" min="1" max="50" placeholder="Biarkan kosong untuk otomatis ikut kapasitas ruangan">
                <small id="slot-quota-help" style="color:var(--muted);font-size:11.5px;display:block;margin-top:4px;">*Untuk program Privat, kuota otomatis dikunci 1 siswa.</small>
            </div>

            <div class="jd-modal-footer">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
                    Buka Slot Jadwal Sekarang
                </button>
            </div>
        </form>
    </dialog>

    {{-- Dialog Tandai Selesai --}}
    <dialog id="dlg-complete" class="jd-dialog">
        <div class="jd-modal-head">
            <div class="jd-modal-title-box">
                <div class="jd-modal-icon" style="background:var(--mint);color:var(--mint-text);">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div>
                    <h3>Tandai Sesi Selesai</h3>
                    <p data-sub style="color:var(--muted);font-size:12px;"></p>
                </div>
            </div>
            <button type="button" class="jd-modal-close" data-close>&times;</button>
        </div>
        <form method="POST" class="jd-modal-body">
            @csrf
            <div class="form-group">
                <label for="jd-notes">Catatan Materi Pembelajaran (Opsional)</label>
                <input type="text" id="jd-notes" name="notes" class="form-control" placeholder="Contoh: Bab 4 Eksponen & Logaritma selesai dibahas">
            </div>
            <div class="jd-modal-footer">
                <button type="button" class="btn btn-secondary" data-close>Tutup</button>
                <button type="submit" class="btn btn-success">Simpan Selesai</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog Tambah Murid --}}
    <dialog id="dlg-assign" class="jd-dialog">
        <div class="jd-modal-head">
            <div class="jd-modal-title-box">
                <div class="jd-modal-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                </div>
                <div>
                    <h3>Tambah Murid ke Sesi</h3>
                    <p data-sub style="color:var(--muted);font-size:12px;"></p>
                </div>
            </div>
            <button type="button" class="jd-modal-close" data-close>&times;</button>
        </div>
        <form method="POST" class="jd-modal-body">
            @csrf
            <div class="form-group">
                <label for="jd-student">Pilih Siswa</label>
                <select id="jd-student" name="student_id" class="form-control" required>
                    <option value="">Pilih murid...</option>
                    @foreach ($students as $std)
                        <option value="{{ $std->id }}">{{ $std->name }} ({{ $std->school_origin ?? 'Siswa' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="jd-modal-footer">
                <button type="button" class="btn btn-secondary" data-close>Tutup</button>
                <button type="submit" class="btn btn-primary">Daftarkan Murid</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog Konfirmasi Batalkan Slot --}}
    <dialog id="dlg-cancel" class="jd-dialog">
        <div class="jd-modal-head">
            <div class="jd-modal-title-box">
                <div class="jd-modal-icon" style="background:var(--pink-soft);color:var(--pink-text);">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h3>Batalkan Slot Mengajar?</h3>
                    <p data-sub style="color:var(--muted);font-size:12px;"></p>
                </div>
            </div>
            <button type="button" class="jd-modal-close" data-close aria-label="Tutup modal">&times;</button>
        </div>
        <form method="POST" class="jd-modal-body">
            @csrf
            @method('DELETE')
            <p style="font-size:13.5px;color:var(--text);line-height:1.5;margin-bottom:6px;">
                Apakah Anda yakin ingin membatalkan slot jadwal mengajar ini?
            </p>
            <p style="font-size:12px;color:var(--muted);line-height:1.4;">
                Slot ruangan pada sesi tersebut akan kembali berstatus <strong>Kosong</strong> dan dapat dibuka kembali kapan saja.
            </p>
            <div class="jd-modal-footer">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-danger" style="padding:9px 18px;">
                    Ya, Batalkan Jadwal
                </button>
            </div>
        </form>
    </dialog>
@endsection

@push('scripts')
    <script>
        // Buka Modal Slot Baru In-Place
        function openCreateSlot(date, timeSlotId, roomId, roomName, timeSlotName) {
            const dlg = document.getElementById('dlg-create-slot');
            if (!dlg) return;

            if (date) {
                document.getElementById('slot-date').value = date;
            }
            if (timeSlotId) {
                document.getElementById('slot-time').value = timeSlotId;
            } else {
                document.getElementById('slot-time').selectedIndex = 0;
            }
            if (roomId) {
                document.getElementById('slot-room').value = roomId;
            } else {
                document.getElementById('slot-room').selectedIndex = 0;
            }

            // Quick Info banner
            const infoBox = document.getElementById('modal-quick-info');
            const infoText = document.getElementById('modal-quick-text');
            if (roomName && timeSlotName) {
                infoText.innerHTML = `Membuka slot di <strong>${roomName}</strong> • <strong>${timeSlotName}</strong>`;
                infoBox.style.display = 'flex';
            } else {
                infoBox.style.display = 'none';
            }

            // Reset program to first option or reguler
            const progSelect = document.getElementById('slot-program');
            if (progSelect && !progSelect.value) {
                // Auto pilih reguler jika ada
                for (let i = 0; i < progSelect.options.length; i++) {
                    if (progSelect.options[i].getAttribute('data-code') === 'reguler') {
                        progSelect.selectedIndex = i;
                        break;
                    }
                }
            }
            handleModalProgramChange(progSelect);

            dlg.showModal();
        }

        // Auto buka modal bila URL mengandung open_modal=1
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_modal') === '1') {
                const date = urlParams.get('date') || urlParams.get('check_date') || '{{ $checkDate }}';
                const timeSlotId = urlParams.get('time_slot_id');
                const roomId = urlParams.get('room_id');
                openCreateSlot(date, timeSlotId, roomId);
            }
        });

        // Program Helper Logic di Modal
        function handleModalProgramChange(select) {
            if (!select) return;
            const selectedOpt = select.options[select.selectedIndex];
            if (!selectedOpt) return;

            const code = selectedOpt.getAttribute('data-code');
            const roomSelect = document.getElementById('slot-room');
            const noteBox = document.getElementById('modal-program-helper-note');
            const quotaInput = document.getElementById('slot-quota');
            const quotaHelp = document.getElementById('slot-quota-help');

            if (code === 'private_home') {
                for (let i = 0; i < roomSelect.options.length; i++) {
                    if (roomSelect.options[i].getAttribute('data-physical') === '0') {
                        roomSelect.selectedIndex = i;
                        break;
                    }
                }
                noteBox.style.display = 'block';
                noteBox.style.background = 'var(--peach)';
                noteBox.style.color = 'var(--peach-text)';
                noteBox.style.border = '1px solid color-mix(in srgb, var(--peach-text) 25%, transparent)';
                noteBox.innerHTML = '<strong>Privat di Rumah:</strong> Sesi berlangsung di rumah murid. Ruangan otomatis dialihkan ke <em>Rumah Siswa</em> (tidak memotong kuota 8 ruangan fisik). Kuota dikunci 1 murid. <em>(Maksimal 2x sesi privat per hari)</em>.';
                quotaInput.value = 1;
                quotaInput.disabled = true;
            } else if (code === 'consult') {
                noteBox.style.display = 'block';
                noteBox.style.background = 'var(--blue-soft)';
                noteBox.style.color = 'var(--blue-text)';
                noteBox.style.border = '1px solid color-mix(in srgb, var(--blue-text) 25%, transparent)';
                noteBox.innerHTML = '<strong>Sesi Konsultasi:</strong> Sesi tanya jawab materi sekolah. <strong>Khusus dialokasikan di Ruang 1 atau Ruang 2</strong>.';
                quotaInput.disabled = false;
                if (!quotaInput.value) quotaInput.value = 4;
            } else if (code === 'private_bimbel') {
                noteBox.style.display = 'block';
                noteBox.style.background = 'var(--primary-soft)';
                noteBox.style.color = 'var(--primary)';
                noteBox.style.border = '1px solid color-mix(in srgb, var(--primary) 25%, transparent)';
                noteBox.innerHTML = '<strong>Privat di Bimbel:</strong> Menggunakan ruangan fisik bimbel (<strong>Khusus Ruang 1 atau Ruang 2</strong>). Kuota langsung dikunci 1 siswa. <em>(Maksimal 2x sesi privat per hari)</em>.';
                quotaInput.value = 1;
                quotaInput.disabled = true;
            } else {
                noteBox.style.display = 'none';
                quotaInput.disabled = false;
                if (quotaInput.value === '1') quotaInput.value = '';
            }
        }

        // Listener saat ruangan berubah
        document.getElementById('slot-room')?.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const cap = opt ? opt.getAttribute('data-cap') : null;
            const quotaInput = document.getElementById('slot-quota');
            const progSelect = document.getElementById('slot-program');
            const progOpt = progSelect ? progSelect.options[progSelect.selectedIndex] : null;

            if (progOpt && progOpt.getAttribute('data-is-private') === '1') {
                quotaInput.value = 1;
            } else if (cap && !quotaInput.disabled && !quotaInput.value) {
                quotaInput.placeholder = 'Kapasitas default: ' + cap + ' orang';
            }
        });

        // Pilih tanggal dari button
        (function() {
            const btn = document.getElementById('jd-date-btn');
            const input = document.getElementById('jd-date');
            if (!btn || !input) return;
            btn.addEventListener('click', () => (input.showPicker ? input.showPicker() : input.click()));
            input.addEventListener('change', () => {
                if (input.value) {
                    window.location.href = btn.dataset.url + '?check_date=' + encodeURIComponent(input.value);
                }
            });
        })();

        // Handler tombol dialog generik
        document.querySelectorAll('[data-open]').forEach(btn => {
            btn.addEventListener('click', () => {
                const dlg = document.getElementById(btn.dataset.open);
                if (!dlg) return;
                const form = dlg.querySelector('form');
                if (form && btn.dataset.action) form.action = btn.dataset.action;
                const subTarget = dlg.querySelector('[data-sub]');
                if (subTarget && btn.dataset.sub) subTarget.textContent = btn.dataset.sub;
                dlg.showModal();
            });
        });

        // Tutup dialog saat klik backdrop atau tombol [data-close]
        document.querySelectorAll('dialog.jd-dialog').forEach(dlg => {
            dlg.addEventListener('click', e => {
                if (e.target === dlg) dlg.close();
            });
            dlg.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => dlg.close()));
        });
    </script>
@endpush

