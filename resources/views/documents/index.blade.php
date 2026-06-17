@extends('layouts.app')

@section('content')
    <a href="{{ route('home') }}" class="btn-back">← Back to Dashboard</a>
    
    <div class="mb-2" style="display:flex; justify-content:space-between; align-items:center;">
        <h1 style="margin:0;">All Documents</h1>
        <a class="btn" href="{{ route('documents.create') }}">Add Document</a>
    </div>

    @forelse ($projects as $project)
        <div class="card mb-2" style="border-left: 5px solid #2563eb;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 1rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.5rem;">
                <div>
                    <h2 style="margin:0; color: #111827;">{{ $project->name }}</h2>
                    <div class="muted">SOP: <strong>{{ $project->sop_number }}</strong></div>
                </div>
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-secondary" style="padding: .3rem .6rem; font-size: .8rem;">Edit Project</a>
            </div>

            <table style="margin-top: 0;">
                <thead>
                    <tr>
                        <th style="width: 100px;">QR</th>
                        <th>Title</th>
                        <th>File Info</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->documents as $document)
                        <tr>
                            <td>
                                <img
                                    src="{{ route('documents.qr', ['document' => $document->id, 'size' => 100]) }}"
                                    alt="QR {{ $document->slug }}"
                                    width="60"
                                    height="60"
                                >
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $document->title }}</div>
                                <div class="muted" style="font-size: .8rem;">Slug: {{ $document->slug }}</div>
                            </td>
                            <td>
                                <div>{{ number_format($document->current_file_size / 1024, 1) }} KB</div>
                                <div class="muted" style="font-size: .8rem;">{{ $document->current_mime_type }}</div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                                    <a class="btn btn-secondary" href="{{ route('documents.edit', $document) }}" style="padding: .4rem .7rem; font-size: .85rem;">Edit</a>
                                    <a class="btn" href="{{ route('scan.show', $document->slug) }}" target="_blank" rel="noopener" style="padding: .4rem .7rem; font-size: .85rem;">View</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="card" style="text-align: center; padding: 3rem;">
            <div class="muted">No documents found in any project.</div>
            <a href="{{ route('documents.create') }}" class="btn" style="margin-top: 1rem;">Upload Your First Document</a>
        </div>
    @endforelse
@endsection
