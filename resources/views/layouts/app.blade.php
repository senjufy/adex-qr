<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adex - Qr Manager</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('adex_head.jpg') }}">
    <style>
        :root { color-scheme: light; }
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6fb; color: #1a1a1a; }
        .container { max-width: 1100px; margin: 0 auto; padding: 1rem; }
        .nav { background: #111827; color: #fff; }
        .nav .container { display: flex; gap: 1rem; align-items: center; }
        .brand { display: flex; align-items: center; margin-right: auto; }
        .brand img { display: block; height: 42px; width: auto; }
        .nav a { color: #fff; text-decoration: none; padding: .9rem 0; }
        .card { background: #fff; border-radius: .5rem; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .mb-1 { margin-bottom: .75rem; }
        .mb-2 { margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; text-align: left; padding: .6rem; vertical-align: top; }
        label { display: block; font-weight: 600; margin-bottom: .35rem; }
        input[type="text"], select, textarea, input[type="file"] { width: 100%; box-sizing: border-box; padding: .6rem; border: 1px solid #d1d5db; border-radius: .35rem; }
        button, .btn { background: #2563eb; color: #fff; border: 0; border-radius: .35rem; padding: .55rem .85rem; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-secondary { background: #4b5563; }
        .btn-danger { background: #b91c1c; }
        .btn-back { display: inline-flex; align-items: center; gap: .4rem; text-decoration: none; color: #6b7280; font-weight: 500; font-size: .9rem; margin-bottom: 1rem; transition: color 0.2s; }
        .btn-back:hover { color: #111827; }
        .btn-danger:disabled { background: #fca5a5; cursor: not-allowed; }
        .help-icon { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            width: 20px; 
            height: 20px; 
            background: #e5e7eb; 
            color: #4b5563; 
            border-radius: 50%; 
            font-size: 12px; 
            font-weight: bold; 
            cursor: help; 
            margin-left: 0.5rem;
            vertical-align: middle;
        }
        .help-icon:hover { background: #d1d5db; color: #111827; }
        .muted { color: #6b7280; font-size: .9rem; }
        .error { color: #b91c1c; font-size: .9rem; margin-top: .3rem; }
        .status { background: #dcfce7; color: #166534; padding: .7rem; border-radius: .35rem; margin-bottom: 1rem; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
        .dash-card { 
            background: #fff; 
            padding: 2rem; 
            border-radius: .75rem; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06); 
            text-align: center; 
            text-decoration: none; 
            color: inherit; 
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
        }
        .dash-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); border-color: #2563eb; }
        .dash-card h2 { margin: 1rem 0 .5rem; color: #111827; }
        .dash-card p { color: #6b7280; margin: 0; }
        .dash-card .icon { 
            font-size: 2.5rem; 
            background: #eff6ff; 
            color: #2563eb; 
            width: 64px; 
            height: 64px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 1rem; 
        }
        .actions { display: flex; gap: .4rem; flex-wrap: wrap; }
        .actions-grid { display: grid; grid-template-columns: repeat(2, minmax(120px, 1fr)); gap: .4rem; }
        .actions-grid > .btn,
        .actions-grid > .inline-form > .btn { width: 100%; text-align: center; box-sizing: border-box; }
        .inline-form { margin: 0; }
        .hidden { display: none; }

        /* Autocomplete Styles */
        .autocomplete-container { position: relative; }
        .autocomplete-results { 
            position: absolute; 
            top: 100%; 
            left: 0; 
            right: 0; 
            background: #fff; 
            border: 1px solid #d1d5db; 
            border-radius: 0 0 .35rem .35rem; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1); 
            z-index: 1000; 
            max-height: 250px; 
            overflow-y: auto; 
            display: none; 
        }
        .autocomplete-item { 
            padding: .75rem; 
            cursor: pointer; 
            border-bottom: 1px solid #f3f4f6; 
            transition: background 0.2s;
            text-align: left;
        }
        .autocomplete-item:hover { background: #eff6ff; }
        .autocomplete-item:last-child { border-bottom: 0; }
        .autocomplete-item .sop-tag { color: #2563eb; font-weight: bold; font-size: 0.85rem; margin-right: 0.5rem; background: #eff6ff; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
    <body>
    <nav class="nav">
        <div class="container" style="justify-content: space-between; align-items: center;">
            <a class="brand" href="{{ route('home') }}">
                <img src="{{ asset('navLogo.webp') }}" alt="QR Manager">
            </a>
            <div>
                @if(session()->has('admin_authenticated'))
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="padding: .4rem .7rem; font-size: .8rem; background: #374151;">Logout</button>
                    </form>
                @endif
            </div>
        </div>
    </nav>
    <main class="container">
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
