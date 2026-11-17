<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Buana Group - Super App Ekosistem Digital')</title>
    <style>
        :root {
            --brand: #0f766e;
            --brand-dark: #115e59;
            --brand-light: #ccfbf1;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --accent: #f59e0b;
            --accent-light: #fef3c7;
            --err: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }
        header {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            padding: 14px 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }
        header .brand-title {
            font-size: 1.15rem;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        header .sub-brand {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 400;
        }
        main {
            max-width: 520px;
            margin: 0 auto;
            padding: 16px 16px 40px;
        }
        .card {
            background: var(--card);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 1px 2px rgba(0,0,0,.04);
            border: 1px solid var(--border);
        }
        h1 { font-size: 1.35rem; margin: 0 0 4px; color: #0f172a; font-weight: 700; }
        p.sub { margin: 0 0 16px; color: var(--muted); font-size: 0.92rem; }
        label { display: block; font-size: 0.88rem; margin: 12px 0 4px; font-weight: 600; color: #334155; }
        input[type=text], input[type=tel], input[type=password] {
            width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 1rem;
            transition: border-color .15s ease;
        }
        input:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15); }
        .err { color: var(--err); font-size: 0.85rem; margin-top: 4px; }
        button, .btn {
            display: block; width: 100%; margin-top: 14px; padding: 13px; border: 0; border-radius: 10px;
            background: var(--brand); color: #fff; font-size: 0.98rem; font-weight: 600; text-align: center;
            text-decoration: none; cursor: pointer; transition: background .15s ease;
        }
        button:hover, .btn:hover { background: var(--brand-dark); }
        .btn-ghost {
            background: transparent; color: #475569; border: 1px solid #cbd5e1;
        }
        .btn-ghost:hover { background: #f1f5f9; color: #1e293b; }
        .btn-admin {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-admin:hover { background: #d97706; }
        .link { text-align: center; margin-top: 16px; font-size: 0.92rem; }
        .link a { color: var(--brand); font-weight: 600; text-decoration: none; }
        .grid { display: grid; gap: 14px; margin-top: 16px; }
        .unit-card {
            display: block;
            padding: 16px;
            background: var(--card);
            border-radius: 14px;
            text-decoration: none;
            color: var(--text);
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
            border: 1px solid var(--border);
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .unit-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px -2px rgba(0,0,0,.06);
            border-color: #cbd5e1;
        }
        .unit-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }
        .unit-icon {
            font-size: 1.6rem;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            border-radius: 12px;
            flex-shrink: 0;
        }
        .unit-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .unit-desc {
            color: var(--muted);
            font-size: 0.86rem;
            margin: 0 0 10px;
            line-height: 1.4;
        }
        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .tag {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #475569;
        }
        .tag-highlight {
            background: var(--brand-light);
            color: var(--brand-dark);
        }
        .user-box {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,.03);
        }
        .user-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-civitas {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-admin {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-customer {
            background: #f1f5f9;
            color: #475569;
        }
        .badge-courier {
            background: #f3e8ff;
            color: #6b21a8;
        }
        .points-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #b45309;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 12px;
            margin-top: 8px;
        }
        .eco-banner {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    @php $inApp = str_contains(request()->userAgent() ?? '', 'BuanaApp'); @endphp

    @unless ($inApp)
        <header>
            <div>
                <div class="brand-title">🏢 Buana Group</div>
                <div class="sub-brand">PT Buana Sejahtera Semarang | Yayasan Wahid Hasyim</div>
            </div>
            @auth
                <a href="{{ route('home') }}" style="color:#fff; text-decoration:none; font-size:0.85rem; opacity:0.9;">Beranda</a>
            @endauth
        </header>
    @endunless

    <main>
        @yield('content')
    </main>
</body>
</html>
