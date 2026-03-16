<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Lead Quality')) — LeadOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --dark: #0f0f1a;
            --dark-2: #16162a;
            --primary: #ff8c00;
            --primary-light: #ffa333;
            --secondary: #10b981;
            --gray: #94a3b8;
            --gray-light: #1e1e3a;
            --glass: rgba(255,255,255,0.05);
            --glass-border: rgba(255,255,255,0.08);
        }

        body { font-family: 'Inter', sans-serif; background: var(--dark); color: #f1f5f9; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 250px; min-height: 100vh; background: var(--dark-2); border-right: 1px solid var(--glass-border); display: flex; flex-direction: column; padding: 1.5rem 1rem; flex-shrink: 0; box-shadow: 4px 0 24px rgba(0,0,0,0.2); z-index: 10; }
        .sidebar-logo { font-size: 1.5rem; font-weight: 800; color: white; padding: 0.5rem 0.75rem 2rem; letter-spacing: -0.5px; display: flex; align-items: center; gap: 0.5rem; }
        .sidebar-logo i { color: var(--primary); font-size: 1.25rem; }
        .sidebar-logo span { color: var(--primary-light); }
        .nav-link { display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 1rem; border-radius: 0.75rem; color: var(--gray); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s ease; border: 1px solid transparent; margin-bottom: 0.25rem; }
        .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; color: rgba(148, 163, 184, 0.7); transition: color 0.2s; }
        .nav-link:hover { background: rgba(255,255,255,0.03); color: white; }
        .nav-link:hover i { color: var(--primary-light); }
        .nav-link.active { background: linear-gradient(90deg, rgba(255,140,0,0.15) 0%, rgba(255,140,0,0.05) 100%); color: white; border-color: rgba(255,140,0,0.2); }
        .nav-link.active i { color: var(--primary); }
        .nav-section { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(148, 163, 184, 0.5); padding: 1.5rem 0.75rem 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
        
        /* User info at bottom of sidebar */
        .sidebar-user { margin-top: auto; padding: 1.25rem 0.75rem; border-top: 1px solid var(--glass-border); background: rgba(0,0,0,0.2); border-radius: 1rem; margin-bottom: 0.5rem; }
        .sidebar-user-name { font-weight: 600; font-size: 0.9rem; color: white; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
        .sidebar-user-meta { font-size: 0.8rem; color: var(--gray); display: flex; align-items: center; gap: 0.75rem; justify-content: center; background: var(--dark); padding: 0.5rem; border-radius: 0.5rem; margin-bottom: 0.75rem; }
        .sidebar-user-meta span { display: flex; align-items: center; gap: 0.25rem; }
        .sidebar-user-meta span i { color: #f59e0b; font-size: 0.85rem; }
        .btn-logout { width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem; border-radius: 0.5rem; background: transparent; border: 1px solid var(--glass-border); color: var(--gray); font-size: 0.85rem; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .btn-logout:hover { color: #f87171; border-color: rgba(248,113,113,0.3); background: rgba(248,113,113,0.05); }

        /* Main */
        .main { flex: 1; display: flex; flex-direction: column; overflow-x: hidden; }
        .topbar { padding: 1.25rem 2rem; border-bottom: 1px solid var(--glass-border); font-size: 0.9rem; color: var(--gray); background: var(--dark); display: flex; align-items: center; gap: 0.5rem; }
        .topbar i { color: var(--primary-light); }
        .content { flex: 1; padding: 2rem; }

        /* Cards */
        .card { background: var(--dark-2); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-good { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge-avg  { background: rgba(251,191,36,0.15);  color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); }
        .badge-bad  { background: rgba(239,68,68,0.15);   color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

        /* Table */
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray); border-bottom: 1px solid var(--glass-border); background: rgba(255,255,255,0.01); }
        td { padding: 1.25rem 1rem; border-bottom: 1px solid var(--glass-border); font-size: 0.9rem; color: #e2e8f0; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s ease; }
        .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; box-shadow: 0 4px 12px rgba(255, 140, 0, 0.2); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(255, 140, 0, 0.3); }
        .btn-primary:active { transform: translateY(0); }

        /* Flash message */
        .flash { padding: 1rem 1.25rem; border-radius: 0.75rem; margin-bottom: 1.5rem; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #10b981; font-size: 0.9rem; display: flex; align-items: center; gap: 0.75rem; }

        /* Forms */
        .form-control { width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; background: var(--dark-2); border: 1px solid var(--glass-border); color: white; font-size: 0.9rem; outline: none; transition: all 0.2s; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.15); background: var(--dark); }
        .form-control::placeholder { color: var(--gray); opacity: 0.6; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 16px 12px; padding-right: 2.5rem; }
        select.form-control option { background: var(--dark); color: white; }

        /* Mobile Responsiveness */
        .mobile-toggle { display: none; background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; padding: 0.5rem; transition: color 0.2s; }
        .mobile-toggle:hover { color: var(--primary); }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 999; backdrop-filter: blur(2px); opacity: 0; transition: opacity 0.3s ease; }
        
        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -280px; top: 0; bottom: 0; width: 280px; transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1000; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
            .sidebar.active { left: 0; }
            .sidebar-overlay.active { display: block; opacity: 1; }
            .mobile-toggle { display: block; margin-right: 0.5rem; }
            .topbar { padding: 1rem; flex-wrap: wrap; }
            .content { padding: 1rem; }
            .card { padding: 1.25rem; }
            h2 { font-size: 1.5rem !important; }
            
            /* Responsive tables */
            .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 0.5rem; }
            table { min-width: 600px; }
            
            /* Stack grids */
            [style*="grid-template-columns"] { grid-template-columns: 1fr !important; gap: 1rem !important; }
            [style*="flex-direction: row"] { flex-direction: column !important; }
            [style*="width: 50%"] { width: 100% !important; }
            .btn { width: 100%; justify-content: center; }
            
            /* Inputs */
            .form-control { font-size: 1rem; /* Prevent zoom on iOS */ }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <i class="fa-solid fa-chart-line"></i> Lead<span>OS</span>
            <button class="mobile-toggle" id="closeSidebar" style="margin-left: auto; display: none;">&times;</button>
        </div>

        <span class="nav-section">{{ __('Main') }}</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> {{ __('Dashboard') }}</a>
        <a href="{{ route('pipeline.index') }}" class="nav-link {{ request()->routeIs('pipeline.*') ? 'active' : '' }}"><i class="fa-solid fa-table-columns"></i> {{ __('Pipeline') }}</a>
        <a href="{{ route('contacts.index') }}" class="nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> {{ __('Contacts') }}</a>
        <a href="{{ route('analytics.index') }}" class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie"></i> {{ __('Analytics') }}</a>
        <a href="{{ route('email-scanner.index') }}" class="nav-link {{ request()->routeIs('email-scanner.*') ? 'active' : '' }}"><i class="fa-solid fa-envelope-open-text"></i> {{ __('Email Scanner') }}</a>
        <a href="{{ route('sequences.index') }}" class="nav-link {{ request()->routeIs('sequences.*') ? 'active' : '' }}"><i class="fa-solid fa-paper-plane"></i> {{ __('Sequences') }}</a>

        <span class="nav-section">{{ __('Settings & Tools') }}</span>
        <a href="{{ route('teams.index') }}" class="nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}"><i class="fa-solid fa-building"></i> {{ __('Workspaces') }}</a>
        <a href="{{ route('icp.index') }}" class="nav-link {{ request()->routeIs('icp.*') ? 'active' : '' }}"><i class="fa-solid fa-bullseye"></i> {{ __('ICP Builder') }}</a>
        <a href="{{ route('diagnostic.index') }}" class="nav-link {{ request()->routeIs('diagnostic.*') ? 'active' : '' }}"><i class="fa-solid fa-stethoscope"></i> {{ __('Diagnostic Tool') }}</a>

        <div class="sidebar-user">
            <div class="sidebar-user-name"><i class="fa-solid fa-circle-user" style="color: var(--gray); font-size: 1.25rem;"></i> {{ auth()->user()->name }}</div>
            <div class="sidebar-user-meta">
                <span title="{{ __('Experience Points') }}"><i class="fa-solid fa-trophy"></i> {{ auth()->user()->points ?? 0 }} XP</span>
                <span title="{{ __('Daily Streak') }}"><i class="fa-solid fa-fire text-orange"></i> {{ auth()->user()->streak ?? 0 }} days</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('Logout') }}</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <button id="mobileToggle" class="mobile-toggle"><i class="fa-solid fa-bars"></i></button>
            <i class="fa-solid fa-layer-group"></i>
            {{ __($view_name ?? (explode('.', Route::currentRouteName() ?? 'Dashboard')[0])) }}
            <span style="margin-left: 0.5rem; opacity: 0.5;">/ LeadOS</span>

            <div style="margin-left: auto; display: flex; align-items: center; gap: 1rem;">
                <x-language-switcher />
            </div>
        </header>
        <div class="content">
            @if(session('success'))
                <div class="flash">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('mobileToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const closeBtn = document.getElementById('closeSidebar');

            function openSidebar() {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }

            function closeSidebarFn() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if(toggle) toggle.addEventListener('click', openSidebar);
            if(overlay) overlay.addEventListener('click', closeSidebarFn);
            if(closeBtn) closeBtn.addEventListener('click', closeSidebarFn);

            // Close sidebar when window is resized to desktop width
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    closeSidebarFn();
                    if(closeBtn) closeBtn.style.display = 'none';
                } else {
                    if(closeBtn) closeBtn.style.display = 'block';
                }
            });
            
            // Initial check for close button
            if (window.innerWidth <= 768 && closeBtn) {
                closeBtn.style.display = 'block';
            }
        });
    </script>
</body>
</html>
