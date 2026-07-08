<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIPENO - Sistem Pengajuan Nomor Surat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-800 to-blue-900 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                            <div class="hidden sm:block">
                                <span class="text-white font-semibold text-sm">SIPENO</span>
                                <span class="text-blue-200 text-xs block -mt-0.5">Disdukcapil</span>
                            </div>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center gap-1">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        @auth
                            @if(Auth::user()->isAdmin())
                                <x-nav-link :href="route('admin.submissions.index')" :active="request()->routeIs('admin.submissions.*')">
                                    {{ __('Kelola Surat') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.letter-types.index')" :active="request()->routeIs('admin.letter-types.*')">
                                    {{ __('Jenis Surat') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                    {{ __('User') }}
                                </x-nav-link>
                            @else
                                <x-nav-link :href="route('submissions.create')" :active="request()->routeIs('submissions.create')">
                                    {{ __('Buat Surat') }}
                                </x-nav-link>
                                <x-nav-link :href="route('submissions.index')" :active="request()->routeIs('submissions.*') && !request()->routeIs('submissions.create')">
                                    {{ __('Semua Surat') }}
                                </x-nav-link>
                            @endif
                            <x-nav-link :href="route('report.index')" :active="request()->routeIs('report.*')">
                                {{ __('Report') }}
                            </x-nav-link>
                            <x-nav-link :href="route('manual')" :active="request()->routeIs('manual')">
                                {{ __('Manual') }}
                            </x-nav-link>
                        @endauth
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
                        <div x-data="notifDropdown()" class="relative">
                            <button @click="toggle()" class="relative p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span x-show="unread > 0" x-text="unread > 9 ? '9+' : unread" class="absolute -top-0.5 -right-0.5 min-w-[1rem] h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1"></span>
                            </button>
                            <div x-show="open" @click.outside="close()" x-transition class="absolute right-0 z-50 mt-2 w-80 rounded-md shadow-lg" style="display:none">
                                <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white overflow-hidden">
                                    <div class="px-4 py-2 text-xs text-gray-400 border-b font-medium">Notifikasi</div>
                                    <div id="notif-list" x-html="notifHtml">
                                        @php $notifs = auth()->user()->notifications()->latest()->take(10)->get(); @endphp
                                        @forelse($notifs as $notif)
                                            @php $subId = $notif->data['submission_id'] ?? null; $url = $subId ? route('submissions.show', $subId) : '#'; @endphp
                                            <a href="{{ $url }}" class="block px-4 py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition {{ $notif->read_at ? '' : 'bg-blue-50/50' }}">
                                                <div class="flex items-start gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full mt-1.5 shrink-0 {{ $notif->read_at ? 'bg-gray-300' : 'bg-blue-500' }}"></span>
                                                    <div class="min-w-0">
                                                        <p class="text-xs text-gray-700 leading-relaxed">{{ $notif->data['message'] ?? '' }}</p>
                                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-6 text-center text-xs text-gray-400">Tidak ada notifikasi</div>
                                        @endforelse
                                    </div>
                                    <div x-show="unread > 0" class="border-t border-gray-100">
                                        <form method="POST" action="{{ route('notifications.read-all') }}">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 text-xs text-center text-blue-600 hover:bg-gray-50 font-medium">Tandai semua sudah dibaca</button>
                                        </form>
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
                                        if (!items || items.length === 0) {
                                            return '<div class="px-4 py-6 text-center text-xs text-gray-400">Tidak ada notifikasi</div>';
                                        }
                                        return items.map(item => `<div class="block px-4 py-3 border-b border-gray-50 ${item.is_unread ? 'bg-blue-50/50' : ''}"><p class="text-xs text-gray-700 leading-relaxed">${item.message || ''}</p><p class="text-[10px] text-gray-400 mt-0.5">${item.created_at || ''}</p></div>`).join('');
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
                                    init() { setInterval(() => { this.fetchNotifs(); }, 10000); }
                                }
                            }
                        </script>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 text-sm text-white/90 hover:text-white transition px-3 py-2 rounded-lg hover:bg-white/10">
                                    <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-xs font-semibold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="px-4 py-2 text-xs text-gray-400 border-b">
                                    {{ Auth::user()->email }}
                                    @if(Auth::user()->isAdmin())
                                        <span class="ml-1 text-blue-600 font-medium">(Admin)</span>
                                    @endif
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profil') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Keluar') }}</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                        @endauth

                        <button @click="open = ! open" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" @click.away="open = false" class="md:hidden border-t border-white/10">
                <div class="px-2 py-3 space-y-1 max-w-7xl mx-auto">
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
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
