<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Manager</title>
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
        input[type="text"], textarea, input[type="file"] { width: 100%; box-sizing: border-box; padding: .6rem; border: 1px solid #d1d5db; border-radius: .35rem; }
        button, .btn { background: #2563eb; color: #fff; border: 0; border-radius: .35rem; padding: .55rem .85rem; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-secondary { background: #4b5563; }
        .btn-danger { background: #b91c1c; }
        .muted { color: #6b7280; font-size: .9rem; }
        .error { color: #b91c1c; font-size: .9rem; margin-top: .3rem; }
        .status { background: #dcfce7; color: #166534; padding: .7rem; border-radius: .35rem; margin-bottom: 1rem; }
        .actions { display: flex; gap: .4rem; flex-wrap: wrap; }
        .inline-form { margin: 0; }
    </style>
</head>
    <body>
    <nav class="nav">
        <div class="container">
            <a class="brand" href="{{ route('documents.index') }}">
                <img src="{{ asset('public/navLogo.webp') }}" alt="QR Manager">
            </a>
            <a href="{{ route('documents.index') }}">Documents</a>
            <a href="{{ route('documents.create') }}">Add Document</a>
            <a href="{{ route('documents.print') }}">Bulk Print</a>
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
