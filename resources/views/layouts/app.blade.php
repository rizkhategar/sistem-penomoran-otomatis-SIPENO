<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIPENO') }} - Sistem Pengajuan Nomor Surat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-slate-50 text-slate-800 overflow-x-hidden">
    <div class="min-h-screen">
        <nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-18 py-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 ring-1 ring-blue-100">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                        </span>
                        <div class="hidden sm:block leading-tight">
                            <span class="text-slate-900 font-extrabold text-lg tracking-tight">SIPENO</span>
                            <span class="text-slate-600 text-xs block">Sistem Penomoran Surat</span>
                        </div>
                    </a>

                    <div class="hidden md:flex items-center gap-1 rounded-2xl border border-slate-200 bg-slate-50 p-1">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
                        @auth
                            @if(Auth::user()->isAdmin())
                                <x-nav-link :href="route('admin.submissions.index')" :active="request()->routeIs('admin.submissions.*')">{{ __('Kelola Surat') }}</x-nav-link>
                                <x-nav-link :href="route('admin.letter-types.index')" :active="request()->routeIs('admin.letter-types.*')">{{ __('Jenis Surat') }}</x-nav-link>
                                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('User') }}</x-nav-link>
                            @else
                                <x-nav-link :href="route('submissions.create')" :active="request()->routeIs('submissions.create')">{{ __('Buat Surat') }}</x-nav-link>
                                <x-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.*') && !request()->routeIs('submissions.create')">{{ __('Semua Surat') }}</x-nav-link>
                            @endif
                            <x-nav-link :href="route('report.index')" :active="request()->routeIs('report.*')">{{ __('Report') }}</x-nav-link>
                            <x-nav-link :href="route('manual')" :active="request()->routeIs('manual')">{{ __('Manual') }}</x-nav-link>
                        @endauth
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
                        <div x-data="notifDropdown()" class="relative">
                            <button @click="toggle()" class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:bg-blue-50 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span x-show="unread > 0" x-text="unread > 9 ? '9+' : unread" class="absolute -top-1 -right-1 min-w-[1.15rem] h-[1.15rem] bg-red-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 ring-2 ring-white"></span>
                            </button>
                            <div x-show="open" @click.outside="close()" x-transition class="absolute right-0 z-50 mt-3 w-80 rounded-2xl shadow-xl" style="display:none">
                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                                    <div class="px-5 py-4 border-b border-slate-200">
                                        <p class="text-sm font-bold text-slate-900">Notifikasi</p>
                                        <p class="text-xs text-slate-600">Update pengajuan terbaru</p>
                                    </div>
                                    <div id="notif-list" class="max-h-96 overflow-y-auto" x-html="notifHtml">
                                        @php $notifs = auth()->user()->notifications()->latest()->take(10)->get(); @endphp
                                        @forelse($notifs as $notif)
                                            @php $subId = $notif->data['submission_id'] ?? null; $url = $subId ? route('submissions.show', $subId) : '#'; @endphp
                                            <a href="{{ $url }}" class="block px-5 py-4 border-b border-slate-100 last:border-0 transition hover:bg-blue-50 {{ $notif->read_at ? '' : 'bg-blue-50' }}">
                                                <p class="text-sm text-slate-800 leading-relaxed">{{ $notif->data['message'] ?? '' }}</p>
                                                <p class="text-xs text-slate-600 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                            </a>
                                        @empty
                                            <div class="px-5 py-8 text-center text-sm text-slate-600">Tidak ada notifikasi</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function notifDropdown() {
                                return {
                                    open: false,
                                    unread: {{ auth()->user()->unreadNotifications->count() }},
                                    notifHtml: '',
                                    toggle() { this.open = !this.open; if (this.open) this.fetchNotifs(); },
                                    close() { this.open = false; },
                                    renderItems(items) {
                                        if (!items || items.length === 0) return '<div class="px-5 py-8 text-center text-sm text-slate-600">Tidak ada notifikasi</div>';
                                        return items.map(item => `<div class="px-5 py-4 border-b border-slate-100 ${item.is_unread ? 'bg-blue-50' : ''}"><p class="text-sm text-slate-800 leading-relaxed">${item.message || ''}</p><p class="text-xs text-slate-600 mt-1">${item.created_at || ''}</p></div>`).join('');
                                    },
                                    fetchNotifs() {
                                        fetch('{{ route("notifications.data") }}')
                                            .then(r => r.json())
                                            .then(d => {
                                                this.unread = d.unread || 0;
                                                this.notifHtml = d.html || this.renderItems(d.items || []);
                                                const list = document.getElementById('notif-list');
                                                if (list) list.innerHTML = this.notifHtml;
                                            });
                                    },
                                    init() { setInterval(() => { this.fetchNotifs(); }, 15000); }
                                }
                            }
                        </script>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm transition hover:bg-blue-50 hover:text-blue-800">
                                    <div class="w-9 h-9 bg-blue-700 text-white rounded-2xl flex items-center justify-center text-sm font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="hidden sm:block text-left leading-tight">
                                        <p class="font-semibold max-w-32 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[11px] text-slate-600">{{ Auth::user()->isAdmin() ? 'Admin' : 'User' }}</p>
                                    </div>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="px-4 py-3 text-xs text-slate-600 border-b border-slate-200 bg-slate-50">
                                    <p class="font-semibold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                                    <p class="mt-0.5">{{ Auth::user()->bidang ?? 'Tanpa bidang' }}</p>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profil') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Keluar') }}</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endauth

                        <button @click="open = ! open" class="md:hidden inline-flex items-center justify-center p-2.5 rounded-2xl border border-slate-200 bg-white text-slate-700 hover:bg-blue-50 transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" @click.away="open = false" x-transition class="md:hidden border-t border-slate-200 bg-white">
                <div class="px-4 py-4 space-y-2 max-w-7xl mx-auto">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <x-responsive-nav-link :href="route('admin.submissions.index')" :active="request()->routeIs('admin.submissions.*')">{{ __('Kelola Surat') }}</x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.letter-types.index')" :active="request()->routeIs('admin.letter-types.*')">{{ __('Jenis Surat') }}</x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">{{ __('User') }}</x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link :href="route('submissions.create')" :active="request()->routeIs('submissions.create')">{{ __('Buat Surat') }}</x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.*') && !request()->routeIs('submissions.create')">{{ __('Semua Surat') }}</x-responsive-nav-link>
                        @endif
                        <x-responsive-nav-link :href="route('report.index')" :active="request()->routeIs('report.*')">{{ __('Report') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('manual')" :active="request()->routeIs('manual')">{{ __('Manual') }}</x-responsive-nav-link>
                    @endauth
                </div>
            </div>
        </nav>

        @if (isset($header))
            <header class="border-b border-slate-200 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="pb-12">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
