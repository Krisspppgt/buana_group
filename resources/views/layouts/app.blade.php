<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Buana Group')</title>
    <style>
        :root { --brand:#1a6b82; --bg:#f6f8fa; --card:#fff; --text:#222; --muted:#6b7280; --err:#b42318; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif; background:var(--bg); color:var(--text); }
        header { background:var(--brand); color:#fff; padding:14px 16px; font-weight:700; }
        main { max-width:480px; margin:0 auto; padding:20px 16px 40px; }
        .card { background:var(--card); border-radius:12px; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,.08); }
        h1 { font-size:1.35rem; margin:0 0 4px; color:var(--brand); }
        p.sub { margin:0 0 18px; color:var(--muted); font-size:.92rem; }
        label { display:block; font-size:.88rem; margin:12px 0 4px; font-weight:600; }
        input[type=text], input[type=tel], input[type=password] {
            width:100%; padding:12px; border:1px solid #d0d5dd; border-radius:8px; font-size:1rem; }
        .err { color:var(--err); font-size:.85rem; margin-top:4px; }
        button, .btn { display:block; width:100%; margin-top:18px; padding:13px; border:0; border-radius:8px;
            background:var(--brand); color:#fff; font-size:1rem; font-weight:600; text-align:center; text-decoration:none; }
        .btn-ghost { background:transparent; color:var(--brand); border:1px solid var(--brand); }
        .link { text-align:center; margin-top:16px; font-size:.92rem; }
        .link a { color:var(--brand); font-weight:600; }
        .grid { display:grid; gap:12px; margin-top:16px; }
        .unit { display:block; padding:16px; background:var(--card); border-radius:12px; text-decoration:none;
            color:var(--text); box-shadow:0 1px 3px rgba(0,0,0,.08); }
        .unit b { color:var(--brand); }
        .unit span { display:block; color:var(--muted); font-size:.85rem; margin-top:2px; }
        .check { display:flex; align-items:center; gap:8px; margin-top:12px; font-size:.9rem; }
    </style>
</head>
<body>
    @php $inApp = str_contains(request()->userAgent() ?? '', 'BuanaApp'); @endphp

    {{-- Navbar disembunyikan saat dibuka dari aplikasi (user-agent mengandung "BuanaApp") --}}
    @unless ($inApp)
        <header>Buana Group</header>
    @endunless

    <main>
        @yield('content')
    </main>
</body>
</html>
