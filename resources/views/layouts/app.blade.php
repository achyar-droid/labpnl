<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lab Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100">

@php
    $labName = match($lab) {
        'iot' => 'IoT',
        'jaringan' => 'Jaringan',
        'cloud' => 'Cloud',
        default => 'Lab'
    };
@endphp

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-blue-600 text-white flex flex-col justify-between">

        <div>
            <!-- TITLE -->
            <div class="p-6 font-bold text-lg border-b border-blue-500 leading-tight">
                {{ $labName }} Laboratory<br>Management
            </div>

            <!-- MENU -->
            <nav class="mt-4 space-y-1 px-3">

                <a href="/dashboard/{{ $lab }}"
                   class="sidebar-link {{ request()->is('dashboard/'.$lab) ? 'active' : '' }}">
                    Dashboard Utama
                </a>

                <a href="/dashboard/{{ $lab }}/barang"
                   class="sidebar-link {{ request()->is('dashboard/'.$lab.'/barang*') ? 'active' : '' }}">
                    Data Barang
                </a>

                <a href="/dashboard/{{ $lab }}/mahasiswa"
                   class="sidebar-link {{ request()->is('dashboard/'.$lab.'/mahasiswa*') ? 'active' : '' }}">
                    Data Mahasiswa
                </a>

                <a href="/dashboard/{{ $lab }}/peminjaman"
                   class="sidebar-link {{ request()->is('dashboard/'.$lab.'/peminjaman*') ? 'active' : '' }}">
                    Peminjaman Barang
                </a>

                {{-- ✅ PENGEMBALIAN (FIX STYLE + BADGE) --}}
                <a href="/dashboard/{{ $lab }}/pengembalian"
                   class="sidebar-link flex items-center justify-between
                   {{ request()->is('dashboard/'.$lab.'/pengembalian*') ? 'active' : '' }}">
                    
                    <span>Pengembalian Barang</span>

                    @if($badgePengembalian > 0)
                        <span class="badge-notif">
                            {{ $badgePengembalian }}
                        </span>
                    @endif
                </a>

                <a href="/dashboard/{{ $lab }}/laporan-peminjaman"
                   class="sidebar-link {{ request()->is('dashboard/'.$lab.'/laporan-peminjaman*') ? 'active' : '' }}">
                    Laporan Peminjaman
                </a>

            </nav>
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}" class="p-4">
            @csrf
            <button class="w-full text-left sidebar-link">
                Logout
            </button>
        </form>

    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</div>

</body>
</html>

{{-- STYLE --}}
<style>
.sidebar-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-radius: 8px;
    transition: .2s;
}

.sidebar-link:hover {
    background: rgba(255,255,255,.15);
}

.sidebar-link.active {
    background: white;
    color: #2563eb;
    font-weight: 600;
}

/* badge */
.badge-notif{
    background:#dc2626;
    color:#fff;
    font-size:11px;
    font-weight:600;
    min-width:18px;
    height:18px;
    padding:0 6px;
    border-radius:9999px;
    display:flex;
    align-items:center;
    justify-content:center;
    line-height:1;
}
</style>
