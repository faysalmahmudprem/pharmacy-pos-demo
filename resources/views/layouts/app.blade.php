<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PharmacyPOS' }}</title>
    <style>
        :root {
            --bg: #f4f6f8;
            --panel: #ffffff;
            --panel-soft: #f9fbfc;
            --text: #18212b;
            --muted: #667483;
            --border: #d6dde5;
            --accent: #0f766e;
            --accent-dark: #115e59;
            --danger: #b42318;
            --warn: #b54708;
            --ok: #027a48;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        a { color: inherit; text-decoration: none; }
        .shell { min-height: 100vh; }
        .topbar {
            background: #10333c;
            color: #fff;
            padding: 14px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .brand { font-size: 22px; font-weight: 700; }
        .brand small { display: block; font-size: 12px; font-weight: 500; color: #c3d2d7; }
        .nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .nav a {
            padding: 10px 14px;
            border-radius: 8px;
            background: rgba(255,255,255,0.08);
            font-size: 14px;
            font-weight: 600;
        }
        .nav a.active { background: #fff; color: #10333c; }
        .container { max-width: 1400px; margin: 0 auto; padding: 22px; }
        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }
        .page-head h1 { margin: 0; font-size: 28px; }
        .page-head p { margin: 6px 0 0; color: var(--muted); }
        .grid { display: grid; gap: 18px; }
        .grid-2 { grid-template-columns: 1.3fr .9fr; }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 18px;
        }
        .panel.soft { background: var(--panel-soft); }
        .panel h2, .panel h3 { margin: 0 0 12px; }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .stat {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
        }
        .stat-label { color: var(--muted); font-size: 13px; margin-bottom: 8px; }
        .stat-value { font-size: 28px; font-weight: 700; }
        .row { display: flex; gap: 12px; flex-wrap: wrap; }
        .row > * { flex: 1 1 160px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            font: inherit;
            color: var(--text);
        }
        textarea { min-height: 84px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border-bottom: 1px solid var(--border);
            padding: 10px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .table-wrap { overflow-x: auto; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 14px;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-weight: 600;
            background: #dfe7ee;
            color: var(--text);
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-dark); }
        .btn-danger { background: #fee4e2; color: var(--danger); }
        .btn-soft { background: #edf2f7; }
        .btn-full { width: 100%; }
        .pill {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .pill-ok { background: #dcfae6; color: var(--ok); }
        .pill-warn { background: #fef0c7; color: var(--warn); }
        .pill-danger { background: #fee4e2; color: var(--danger); }
        .message {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .message-success { background: #dcfae6; color: var(--ok); }
        .message-error { background: #fee4e2; color: var(--danger); }
        .meta { color: var(--muted); font-size: 13px; }
        .stack { display: grid; gap: 12px; }
        .split {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .numeric { text-align: right; white-space: nowrap; }
        .invoice-box {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 26px;
        }
        .errors { margin: 0 0 16px; padding-left: 18px; color: var(--danger); }
        @media (max-width: 980px) {
            .grid-2, .grid-4, .stats { grid-template-columns: 1fr; }
        }
        .app-footer {
    text-align: center;
    padding: 18px;
    font-size: 13px;
    color: var(--muted);
    border-top: 1px solid var(--border);
    background: var(--panel-soft);
    margin-top: 40px;
}

.app-footer a {
    color: var(--accent);
    font-weight: 600;
    transition: 0.2s;
}

.app-footer a:hover {
    color: var(--accent-dark);
}
    </style>
</head>
<body>
<div class="shell">
    <header class="topbar">
        <div class="brand">
            PharmacyPOS
            <small>Fast offline-friendly pharmacy counter for Bangladesh</small>
        </div>
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.index') ? 'active' : '' }}">POS</a>
            <a href="{{ route('medicines.index') }}" class="{{ request()->routeIs('medicines.*') ? 'active' : '' }}">Medicines</a>
            <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">Customers</a>
        </nav>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="message message-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <ul class="errors">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @yield('content')
    </main>
    <footer class="app-footer">
    Developed by 
    <a href="https://github.com/faysalmahmudprem" target="_blank">
        Faysal Mahmud Prem
    </a>
</footer>
</body>
</html>
