@php
use App\Models\Peminjaman;

$lab = auth()->user()->lab;

// hitung peminjaman yang BELUM dikembalikan
$belumKembali = Peminjaman::where('lab', $lab)
    ->where('status', 'dipinjam')
    ->count();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex">
                <!-- LOGO -->
                <div class="shrink-0 flex items-center">
                    <a href="/dashboard/{{ $lab }}">
                        <x-application-logo
                            class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- TOP NAV (DESKTOP) -->
                <div class="hidden sm:flex sm:items-center sm:ms-10 space-x-6">

                    <x-nav-link
                        href="/dashboard/{{ $lab }}"
                        :active="request()->is('dashboard/'.$lab)"
                    >
                        Dashboard
                    </x-nav-link>

                    <x-nav-link
                        href="/dashboard/{{ $lab }}/barang"
                        :active="request()->is('dashboard/*/barang')"
                    >
                        Data Barang
                    </x-nav-link>

                    <x-nav-link
                        href="/dashboard/{{ $lab }}/mahasiswa"
                        :active="request()->is('dashboard/*/mahasiswa')"
                    >
                        Mahasiswa
                    </x-nav-link>

                    <x-nav-link
                        href="/dashboard/{{ $lab }}/peminjaman"
                        :active="request()->is('dashboard/*/peminjaman')"
                    >
                        Peminjaman
                    </x-nav-link>

                    {{-- PENGEMBALIAN + BADGE --}}
                    <x-nav-link
                        href="/dashboard/{{ $lab }}/pengembalian"
                        :active="request()->is('dashboard/*/pengembalian')"
                        class="relative flex items-center"
                    >
                        <span>Pengembalian</span>

                        @if($belumKembali > 0)
                            <span
                                class="absolute -top-1 -right-3 bg-red-600 text-white text-[10px]
                                       min-w-[18px] h-[18px] px-1 rounded-full
                                       flex items-center justify-center font-semibold">
                                {{ $belumKembali }}
                            </span>
                        @endif
                    </x-nav-link>

                </div>
            </div>

            <!-- USER DROPDOWN -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium
                                   text-gray-600 bg-white hover:text-gray-800 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293L10 12l4.707-4.707-1.414-1.414
                                           L10 9.172 6.707 5.879z" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- HAMBURGER -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2
                           text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link href="/dashboard/{{ $lab }}">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link href="/dashboard/{{ $lab }}/barang">
                Data Barang
            </x-responsive-nav-link>

            <x-responsive-nav-link href="/dashboard/{{ $lab }}/mahasiswa">
                Mahasiswa
            </x-responsive-nav-link>

            <x-responsive-nav-link href="/dashboard/{{ $lab }}/peminjaman">
                Peminjaman
            </x-responsive-nav-link>

            {{-- MOBILE PENGEMBALIAN --}}
            <x-responsive-nav-link
                href="/dashboard/{{ $lab }}/pengembalian"
                class="flex items-center justify-between"
            >
                <span>Pengembalian</span>

                @if($belumKembali > 0)
                    <span class="bg-red-600 text-white text-[10px]
                                 min-w-[18px] h-[18px] px-1 rounded-full
                                 flex items-center justify-center font-semibold">
                        {{ $belumKembali }}
                    </span>
                @endif
            </x-responsive-nav-link>

        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 text-gray-800">
                {{ Auth::user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link
                    :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
