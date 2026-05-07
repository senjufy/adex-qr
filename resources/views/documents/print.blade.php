<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 1rem; color: #111; }
        .toolbar { margin-bottom: 1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(520px, 1fr)); gap: 1rem; }
        .label { border: 1px solid #ddd; border-radius: 8px; padding: .8rem; break-inside: avoid; }
        .qr-pair { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; align-items: start; }
        .qr-block { text-align: center; }
        .meta { margin-top: .4rem; font-size: .9rem; line-height: 1.35; word-break: break-word; }
        .print-action { margin-top: .8rem; text-align: center; }
        img { display: block; margin: 0 auto; }
        @media print {
            .toolbar { display: none; }
            .print-action { display: none; }
            body { margin: 0; }
            .label { padding: .3rem; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('documents.index') }}">Back</a>
    </div>

    <div class="grid">
        @foreach ($documents as $document)
            <div class="label">
                <div class="qr-pair">
                    <div class="qr-block">
                        <img
                            src="{{ route('documents.qr', ['document' => $document->id, 'size' => 220]) }}"
                            alt="QR {{ $document->slug }}"
                            width="180"
                            height="180"
                        >
                        <div class="meta">
                            <div><strong>{{ $document->project_name }}</strong></div>
                            <div>SOP: {{ $document->sop_number }}</div>
                        </div>
                    </div>

                    <div class="qr-block">
                        <img
                            src="{{ route('documents.qr', ['document' => $document->id, 'size' => 220]) }}"
                            alt="QR {{ $document->slug }} plain"
                            width="180"
                            height="180"
                        >
                    </div>
                </div>

                <div class="print-action">
                    <button type="button" onclick="window.print()">Print</button>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
