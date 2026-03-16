<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('LeadOS - Advanced B2B Lead Generation & CRM') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --dark: #0f0f1a;
            --dark-2: #16162a;
            --primary: #ff8c00;
            --primary-light: #ffa333;
            --gray: #94a3b8;
            --glass: rgba(255,255,255,0.03);
            --glass-border: rgba(255,255,255,0.08);
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark); 
            color: #f1f5f9; 
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Nav */
        nav {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            background: rgba(15, 15, 26, 0.8);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0; width: 100%; z-index: 100;
        }

        .logo { font-size: 1.5rem; font-weight: 800; color: white; display: flex; align-items: center; gap: 0.5rem; text-decoration: none; }
        .logo i { color: var(--primary); }
        .logo span { color: var(--primary-light); }

        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: var(--gray); text-decoration: none; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: white; }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem 1.25rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.2s ease; border: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: #ffffff !important; box-shadow: 0 4px 12px rgba(255, 140, 0, 0.2); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(255, 140, 0, 0.4); color: white !important; }
        .btn-outline { border: 1px solid var(--glass-border); color: white; background: var(--glass); }
        .btn-outline:hover { background: rgba(255,255,255,0.08); }

        /* Hero */
        .hero {
            padding: 12rem 2rem 8rem;
            text-align: center;
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero::before {
            content: ''; position: absolute; top: -20%; left: 50%; transform: translateX(-50%);
            width: 800px; height: 800px; background: radial-gradient(circle, rgba(255,140,0,0.15) 0%, rgba(15,15,26,0) 70%);
            z-index: -1; border-radius: 50%;
        }

        .hero h1 { font-size: clamp(2.5rem, 10vw, 4.5rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.02em; }
        .hero h1 span { background: linear-gradient(135deg, var(--primary) 0%, #ff5e00 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: clamp(1rem, 4vw, 1.25rem); color: var(--gray); max-width: 600px; margin: 0 auto 3rem; }
        .hero-actions { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; }
        .hero-actions .btn { padding: 1rem 2rem; font-size: 1rem; border-radius: 999px; }

        /* Dashboard Preview */
        .preview-container { margin: 4rem auto 0; max-width: 1000px; padding: 1rem; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 1.5rem; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.5); transform: perspective(1000px) rotateX(2deg); width: 100%; }
        .preview-inner { background: var(--dark-2); border-radius: 0.75rem; height: clamp(250px, 50vh, 500px); overflow: hidden; position: relative; border: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; color: var(--gray); }
        
        /* Features */
        .features { padding: clamp(4rem, 10vw, 8rem) 1.5rem; max-width: 1200px; margin: 0 auto; }
        .section-header { text-align: center; margin-bottom: 4rem; }
        .section-header h2 { font-size: clamp(1.75rem, 5vw, 2.5rem); font-weight: 700; margin-bottom: 1rem; }
        .section-header p { color: var(--gray); font-size: 1.1rem; }

        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(Min(100%, 300px), 1fr)); gap: 2rem; }
        .feature-card { background: var(--dark-2); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 2.5rem 2rem; transition: transform 0.3s, box-shadow 0.3s; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); border-color: rgba(255, 140, 0, 0.3); }
        .feature-icon { width: 50px; height: 50px; border-radius: 1rem; background: rgba(255, 140, 0, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; }
        .feature-card h3 { font-size: 1.25rem; margin-bottom: 1rem; }
        .feature-card p { color: var(--gray); font-size: 0.95rem; }

        /* Footer */
        footer { padding: 4rem 2rem; border-top: 1px solid var(--glass-border); text-align: center; color: var(--gray); font-size: 0.9rem; }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            nav { padding: 1rem; }
            .nav-links { display: none; }
            .nav-links.active { 
                display: flex; 
                flex-direction: column; 
                position: absolute; 
                top: 100%; left: 0; width: 100%; 
                background: var(--dark-2); 
                padding: 1.5rem; 
                border-bottom: 1px solid var(--glass-border);
                gap: 1.5rem;
                z-index: 200;
            }
            .mobile-menu-toggle { display: block !important; color: white; font-size: 1.5rem; cursor: pointer; }
            
            .hero { padding: 8rem 1rem 4rem; width: 100%; overflow: hidden; }
            .hero-actions { flex-direction: column; gap: 0.75rem; padding: 0 1rem; }
            .hero-actions .btn { width: 100%; }

            .preview-container { transform: none; margin-top: 2rem; border-radius: 1rem; }
            .features { padding: 4rem 1rem; }
        }

        .mobile-menu-toggle { display: none; }
    </style>
</head>
<body>
    <nav>
    <nav>
        <a href="/" class="logo"><i class="fa-solid fa-chart-line"></i> Lead<span>OS</span></a>
        <div class="mobile-menu-toggle" onclick="document.getElementById('nav-links').classList.toggle('active')">
            <i class="fa-solid fa-bars"></i>
        </div>
        <div class="nav-links" id="nav-links">
            <x-language-switcher />
            <a href="{{ route('docs') }}">{{ __('Documentation') }}</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Go to Dashboard') }}</a>
            @else
                <a href="{{ route('login') }}">{{ __('Log in') }}</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Get Started') }}</a>
                @endif
            @endauth
        </div>
    </nav>

    <section class="hero">
        <h1>{!! __('The Ultimate B2B<br><span>Lead Generation</span> Engine.') !!}</h1>
        <p>{{ __('LeadOS combines an AI CRM, Automated Sequences, Email Scanning, and Team Workspaces into one powerful tool built for the modern sales team.') }}</p>
        <div class="hero-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Enter Dashboard') }} <i class="fa-solid fa-arrow-right"></i></a>
            @else
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Start for Free') }} <i class="fa-solid fa-rocket"></i></a>
                @endif
                <a href="{{ route('docs') }}" class="btn btn-outline">{{ __('Read Docs') }} <i class="fa-solid fa-book"></i></a>
            @endauth
        </div>

        <div class="preview-container">
            <div class="preview-inner" style="background-image: url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1000&q=80'); background-size: cover; background-position: center;">
                <div style="position: absolute; inset: 0; background: rgba(15, 15, 26, 0.85); display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-chart-pie" style="font-size: 4rem; color: var(--primary); margin-bottom: 1rem;"></i>
                    <h3 style="color: white; font-size: 1.5rem;">{{ __('Powerful CRM Interface') }}</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="section-header">
            <h2>{{ __('Everything you need to close more deals') }}</h2>
            <p>{{ __('A unified suite of tools designed to streamline your outreach and improve lead quality.') }}</p>
        </div>
        <div class="grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-robot"></i></div>
                <h3>{{ __('AI Lead Analysis') }}</h3>
                <p>{{ __('Built-in intelligence to score and evaluate every contact against your Ideal Customer Profile, giving you a Fit Score.') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-paper-plane"></i></div>
                <h3>{{ __('Automated Sequences') }}</h3>
                <p>{{ __('Enroll contacts into multi-step drip campaigns. Send emails automatically using your connected IMAP/SMTP accounts.') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                <h3>{{ __('Inbox Scanner') }}</h3>
                <p>{{ __('Connect your email and let LeadOS automatically detect new leads, infer company names, and suggest additions to your CRM.') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-table-columns"></i></div>
                <h3>{{ __('Visual Pipeline') }}</h3>
                <p>{{ __('A drag-and-drop Kanban board to manage your deals visually. Instantly update stages from Cold to Won.') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-building"></i></div>
                <h3>{{ __('Team Workspaces') }}</h3>
                <p>{{ __('Collaborate with your team, invite members, assign roles, and separate data neatly across multiple organizations.') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-chrome"></i></div>
                <h3>{{ __('Chrome Extension') }}</h3>
                <p>{{ __('Generate API tokens and seamlessly pull LinkedIn contacts into LeadOS right from your browser.') }}</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} {{ __('LeadOS. All rights reserved.') }}</p>
    </footer>
</body>
</html>
