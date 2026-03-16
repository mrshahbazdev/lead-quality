<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('LeadOS Documentation') }}</title>
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

        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--dark); 
            color: #f1f5f9; 
            line-height: 1.6;
            margin: 0;
            padding: 0;
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

        .docs-container { padding: clamp(6rem, 15vw, 10rem) 1.5rem 4rem; max-width: 900px; margin: 0 auto; }
        .card { background: var(--dark-2); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; }
        
        .docs-container h1 { font-size: clamp(2rem, 8vw, 2.5rem); font-weight: 700; margin-bottom: 1rem; }

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
            .mobile-menu-toggle { display: block !important; color: white; font-size: 1.5rem; cursor: pointer; position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); }
            
            .docs-container { padding: clamp(5rem, 12vw, 8rem) 1rem 2rem; }
            .card { padding: 1.25rem; }
        }

        .mobile-menu-toggle { display: none; }
    </style>
</head>
<body>
    <nav>
        <a href="/" class="logo"><i class="fa-solid fa-chart-line"></i> Lead<span>OS</span></a>
        <div class="mobile-menu-toggle" onclick="document.getElementById('nav-links').classList.toggle('active')">
            <i class="fa-solid fa-bars"></i>
        </div>
        <div class="nav-links" id="nav-links">
            <x-language-switcher />
            <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Go to Dashboard') }}</a>
        </div>
    </nav>

    <div class="docs-container">
        <div style="margin-bottom: 3rem; text-align: center;">
            <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">{{ __('LeadOS Documentation') }}</h1>
            <p style="color: var(--gray); font-size: 1.1rem;">{{ __('Your complete guide to setting up and mastering the B2B Lead Engine.') }}</p>
        </div>

        <!-- Overview -->
        <div class="card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-light);">
                <i class="fa-solid fa-rocket"></i> {{ __('Getting Started') }}
            </h2>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('LeadOS is designed to execute the 2/2/2 Strategy (2 Outreaches Daily, 2 Follow-ups Weekly, 2 Meetings Monthly). It is a full-fledged CRM aimed at enhancing your lead quality through AI insights, structured pipelines, and email automation.') }}</p>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('As a new user, you are automatically assigned a default Workspace. Everything you do inside LeadOS is scoped to your current active Workspace, meaning your data is kept secure and separate.') }}</p>
        </div>

        <!-- ICP Builder -->
        <div class="card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-light);">
                <i class="fa-solid fa-bullseye"></i> {{ __('1. Defining your ICP') }}
            </h2>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('The Ideal Customer Profile (ICP) is the foundation of LeadOS. Before adding leads, go to the ICP Builder.') }}</p>
            <ul style="list-style: disc; margin-left: 2rem; color: #cbd5e1; margin-bottom: 1rem;">
                <li>{{ __('Define your Target Industries, Minimum Budgets, and exact Job Titles.') }}</li>
                <li>{!! __('List "Red Flags" so the AI knows who to reject.') !!}</li>
                <li>{{ __('When you add a Contact, the built-in AI will score them against this exact ICP.') }}</li>
            </ul>
        </div>

        <!-- Chrome Extension -->
        <div class="card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-light);">
                <i class="fa-brands fa-chrome"></i> {{ __('2. Using the Chrome Extension') }}
            </h2>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('Lead scraping from LinkedIn is made easy through our dedicated extension.') }}</p>
            <ul style="list-style: decimal; margin-left: 2rem; color: #cbd5e1; margin-bottom: 1rem;">
                <li>{!! __('Go to Settings & Tools > Workspaces.') !!}</li>
                <li>{{ __('Scroll down to "API Credentials" and generate a new token.') }}</li>
                <li>{{ __('Copy the token and paste it into the Chrome Extension popup.') }}</li>
                <li>{{ __('Visit any LinkedIn profile and click "Scrape & Send to LeadOS". The contact will instantly appear in your Contacts list.') }}</li>
            </ul>
        </div>

        <!-- Email Scanner & Settings -->
        <div class="card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-light);">
                <i class="fa-solid fa-envelope-open-text"></i> {{ __('3. Connect Email & Scanner') }}
            </h2>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('LeadOS can read your inbox to find unlogged leads and send drip campaigns on your behalf.') }}</p>
            <ul style="list-style: disc; margin-left: 2rem; color: #cbd5e1; margin-bottom: 1rem;">
                <li>{!! __('Navigate to <strong>Workspaces</strong> and add your IMAP/SMTP credentials (e.g., App Passwords for Gmail/Outlook).') !!}</li>
                <li>{{ __('Once connected, go to the Email Scanner page. LeadOS will scan your recent emails to detect unregistered contacts, infer their company names, and let you 1-click add them to the CRM.') }}</li>
            </ul>
        </div>

        <!-- Sequences -->
        <div class="card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-light);">
                <i class="fa-solid fa-paper-plane"></i> {{ __('4. Automated Sequences') }}
            </h2>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('Put your follow-ups on autopilot.') }}</p>
            <ul style="list-style: disc; margin-left: 2rem; color: #cbd5e1; margin-bottom: 1rem;">
                <li>{!! __('Go to Sequences and create a new Campaign (e.g. "Cold Outreach 1").') !!}</li>
                <li>{{ __('Add Steps (e.g., Step 1: Initial Email, Step 2: Delay 3 days, Step 3: Follow-up Email).') }}</li>
                <li>{!! __('You can use placeholders like <code>{name}</code> and <code>{company}</code> in the subject and body.') !!}</li>
                <li>{{ __('Open any Contact, and use the "Add to Drip Campaign" widget to enroll them. The system\'s background worker will handle sending the emails sequentially via your connected SMTP account.') }}</li>
            </ul>
        </div>

        <!-- Pipeline -->
        <div class="card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-light);">
                <i class="fa-solid fa-table-columns"></i> {{ __('5. The Kanban Pipeline') }}
            </h2>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('Visualize your deals.') }}</p>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('Using the Pipeline tab, you can drag and drop your leads across stages (Cold -> Outreach -> Meeting Set -> Proposal Sent -> Won). The state is saved instantly without refreshing the page.') }}</p>
        </div>

        <!-- Diagnostic Tool -->
        <div class="card">
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-light);">
                <i class="fa-solid fa-stethoscope"></i> {{ __('6. Diagnostic Tool') }}
            </h2>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('Are your current acquisition strategies working?') }}</p>
            <p style="margin-bottom: 1rem; color: #cbd5e1;">{{ __('Take the 6-question quiz in the Diagnostic Tool. Based on your answers about ICP definition, outreach targets, and conversion tracking, the tool will analyze your "Revenue Risk" and provide actionable next steps to patch the holes in your sales funnel.') }}</p>
        </div>

    </div>
</body>
</html>
