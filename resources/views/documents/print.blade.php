<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1rem; color: #111; }
        .toolbar { margin-bottom: 1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
        .label { border: 0; padding: .8rem; break-inside: avoid; text-align: center; }
        img { display: block; margin: 0 auto; }
        @media print {
            .toolbar { display: none; }
            body { margin: 0; }
            .label { padding: .3rem; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">Print</button>
        <a href="{{ route('documents.index') }}">Back</a>
    </div>

    <div class="grid">
        @foreach ($documents as $document)
            <div class="label">
                <img
                    src="{{ route('documents.qr', ['document' => $document->id, 'size' => 220]) }}"
                    alt="QR {{ $document->slug }}"
                    width="180"
                    height="180"
                >
            </div>
        @endforeach
    </div>
</body>
</html>
