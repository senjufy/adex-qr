<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->name }} - Project Documents</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('adex_head.jpg') }}">
    <style>
        :root { color-scheme: light; --primary: #2563eb; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; margin: 0; background: #f9fafb; color: #111827; line-height: 1.5; }
        .header { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 1.5rem 1rem; text-align: center; }
        .header img { height: 48px; margin-bottom: 1rem; }
        .container { max-width: 800px; margin: 0 auto; padding: 1.5rem 1rem; }
        .project-info { background: #fff; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; }
        .project-info h1 { margin: 0 0 0.5rem; font-size: 1.5rem; color: #111827; }
        .project-info .sop { display: inline-block; background: #eff6ff; color: #2563eb; font-weight: 600; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; margin-bottom: 1rem; }
        .project-info p { margin: 0; color: #4b5563; font-size: 1rem; }
        
        .file-list { display: grid; gap: 1rem; }
        .file-card { 
            background: #fff; 
            border-radius: 0.75rem; 
            padding: 1.25rem; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
            text-decoration: none;
            color: inherit;
            transition: transform 0.1s;
        }
        .file-card:active { transform: scale(0.98); background: #f3f4f6; }
        .file-meta { display: flex; align-items: center; gap: 1rem; overflow: hidden; }
        .file-icon { 
            background: #fee2e2; 
            color: #b91c1c; 
            width: 48px; 
            height: 48px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 0.75rem; 
            flex-shrink: 0;
            font-size: 1.25rem;
        }
        .file-details { overflow: hidden; }
        .file-title { font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
        .file-size { font-size: 0.875rem; color: #6b7280; }
        
        .btn-view { 
            background: var(--primary); 
            color: #fff; 
            padding: 0.5rem 1rem; 
            border-radius: 0.5rem; 
            font-weight: 600; 
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        @media (max-width: 480px) {
            .file-card { flex-direction: column; align-items: stretch; gap: 1rem; }
            .btn-view { text-align: center; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; padding: 0 1rem;">
            <img src="{{ asset('navLogo.webp') }}" alt="QR Manager" style="margin-bottom: 0;">
            <div style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">TECHNICAL DOCUMENTATION</div>
        </div>
    </header>

    <main class="container">
        <section class="project-info">
            <!-- <span class="sop">SOP: {{ $project->sop_number }}</span> -->
            <h1>{{ $project->name }}</h1>
            @if($project->description)
                <p>{{ $project->description }}</p>
            @endif
        </section>

        <h2 style="font-size: 1.125rem; margin-bottom: 1rem; color: #374151;">Available Documents</h2>

        <div class="file-list">
            @forelse($project->documents as $document)
                <a href="{{ route('scan.show', $document->slug) }}" class="file-card" target="_blank">
                    <div class="file-meta">
                        <div class="file-icon">PDF</div>
                        <div class="file-details">
                            <span class="file-title">{{ $document->title }}</span>
                            <span class="file-size">{{ number_format($document->current_file_size / 1024, 1) }} KB</span>
                        </div>
                    </div>
                    <div class="btn-view">View File</div>
                </a>
            @empty
                <div style="text-align: center; padding: 3rem; color: #6b7280; background: #fff; border-radius: 1rem; border: 1px dashed #d1d5db;">
                    No documents available for this project.
                </div>
            @endforelse
        </div>
    </main>

    <footer style="text-align: center; margin-top: 3rem; padding-bottom: 2rem; color: #9ca3af; font-size: 0.75rem;">
        &copy; {{ date('Y') }} QR Manager Isolated File System
    </footer>
</body>
</html>
