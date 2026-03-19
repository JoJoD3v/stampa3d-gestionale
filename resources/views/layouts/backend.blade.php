<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Gestionale Stampe 3D</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div class="app-shell">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <svg width="32" height="32" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="52" height="52" rx="10" fill="rgba(180,192,217,0.18)"/>
                <path d="M14 36L26 14L38 36H14Z" fill="#B4C0D9" opacity="0.8"/>
                <path d="M20 36L26 24L32 36H20Z" fill="#fff"/>
                <rect x="22" y="38" width="8" height="3" rx="1.5" fill="#B4C0D9"/>
            </svg>
            <div class="sidebar-brand-text">
                <div class="sidebar-brand-title">Stampe 3D</div>
                <div class="sidebar-brand-subtitle">Gestionale</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            <div class="sidebar-section-label">Principale</div>

            <a href="{{ route('backend.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>

            <div class="sidebar-section-label">Gestione</div>

            <a href="{{ route('backend.users.index') }}"
               class="sidebar-link {{ request()->routeIs('backend.users.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Gestione Utenti
            </a>

            <a href="{{ route('backend.printers.index') }}"
               class="sidebar-link {{ request()->routeIs('backend.printers.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <polyline points="6 9 6 2 18 2 18 9"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <rect x="6" y="14" width="12" height="8"/>
                </svg>
                Gestione Stampanti
            </a>

            <a href="{{ route('backend.customers.index') }}"
               class="sidebar-link {{ request()->routeIs('backend.customers.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Gestione Clienti
            </a>

            <a href="{{ route('backend.lavori.index') }}"
               class="sidebar-link {{ request()->routeIs('backend.lavori.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/>
                    <line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
                Gestione Lavori
            </a>

            <a href="{{ route('backend.projects.index') }}"
               class="sidebar-link {{ request()->routeIs('backend.projects.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    <line x1="12" y1="11" x2="12" y2="17"/>
                    <line x1="9" y1="14" x2="15" y2="14"/>
                </svg>
                Gestione Progetti
            </a>

        </nav>

        {{-- Sidebar footer --}}
        <div class="sidebar-footer">
            <div>v1.0.0 &mdash; Gestionale Stampe 3D</div>
        </div>
    </aside>

    {{-- ===================== TOPBAR ===================== --}}
    <header class="topbar">
        <div class="topbar-left">
            <span class="topbar-page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-right">
            <div class="topbar-user">
                <div class="topbar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->surname, 0, 1)) }}
                </div>
                <div>
                    <div class="topbar-user-name">{{ auth()->user()->name }} {{ auth()->user()->surname }}</div>
                    <div class="topbar-user-role">{{ auth()->user()->is_admin ? 'Amministratore' : 'Utente' }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" title="Esci">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Esci
                </button>
            </form>
        </div>
    </header>

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="main-content">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tr[data-href]').forEach(function (tr) {
        tr.addEventListener('click', function (e) {
            if (!e.target.closest('a, button, form, input, select, textarea')) {
                window.location.href = tr.dataset.href;
            }
        });
    });
});
</script>
@stack('scripts')
</body>
</html>
