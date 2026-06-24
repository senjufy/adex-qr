<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project QR Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1rem; color: #111; }
        .toolbar { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(520px, 1fr)); gap: 1.5rem; }
        .label { border: 2px solid #333; border-radius: 12px; padding: 1.5rem; break-inside: avoid; background: #fff; }
        .qr-pair { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: center; }
        .qr-block { text-align: center; }
        .meta { margin-top: 1rem; font-size: 1.1rem; line-height: 1.4; word-break: break-word; }
        .btn { background: #2563eb; color: #fff; border: 0; border-radius: .35rem; padding: .55rem .85rem; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 600; }
        img { display: block; margin: 0 auto; }
        @media print {
            .toolbar { display: none; }
            body { margin: 0; background: #fff; }
            .label { border: 1px solid #000; margin-bottom: 1rem; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('projects.index') }}" class="btn">Back to Projects</a>
        <button type="button" class="btn" onclick="window.print()">Print Labels</button>
    </div>

    <div class="grid">
        @foreach ($projects as $project)
            <div class="label">
                <div class="qr-pair">
                    <div class="qr-block">
                        <img
                            src="{{ route('projects.qr', ['project' => $project->id, 'size' => 240]) }}"
                            alt="QR {{ $project->slug }}"
                            width="200"
                            height="200"
                        >
                        <div class="meta">
                            <div style="font-size: 1.3rem; font-weight: bold;">{{ $project->name }}</div>
                            <div style="color: #444;">SOP: {{ $project->sop_number }}</div>
                            <div style="font-size: 0.8rem; color: #888; margin-top: 0.5rem;">SCAN TO VIEW DOCUMENTS</div>
                        </div>
                    </div>

                    <div class="qr-block">
                        <img
                            src="{{ route('projects.qr', ['project' => $project->id, 'size' => 240]) }}"
                            alt="QR {{ $project->slug }} plain"
                            width="200"
                            height="200"
                        >
                        <div class="meta">
                            <img src="{{ asset('navLogo.webp') }}" alt="Logo" style="height: 35px; width: auto; filter: grayscale(1);">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
