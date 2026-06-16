@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="mb-2" style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
            <div>
                <h1 style="margin:.2rem 0;">All Documents</h1>
            </div>
            <a class="btn" href="{{ route('documents.create') }}">New Document</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>QR</th>
                    <th>Title</th>
                    <th>SOP Number</th>
                    <th class="hidden">Project Name</th>
                    <th>Current File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr>
                        <td>
                            <img
                                src="{{ route('documents.qr', ['document' => $document->id, 'size' => 100]) }}"
                                alt="QR {{ $document->slug }}"
                                width="80"
                                height="80"
                            >
                        </td>
                        <td>{{ $document->title }}</td>
                        <td>{{ $document->project->sop_number ?? 'N/A' }}</td>
                        <td class="hidden">{{ $document->project_name }}</td>
                        <td>
                            <div>{{ number_format($document->current_file_size / 1024, 1) }} KB</div>
                            <div class="muted">{{ $document->current_mime_type }}</div>
                        </td>
                        <td>
                            <div class="actions-grid">
                                <a class="btn btn-secondary" href="{{ route('documents.edit', $document) }}">Edit</a>
                                <a class="btn" href="{{ route('scan.show', $document->slug) }}" target="_blank" rel="noopener">Open PDF</a>
                                <a class="btn btn-secondary" href="{{ route('documents.print.single', $document) }}" target="_blank" rel="noopener">Print QR</a>
                                <form class="inline-form" method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Delete this document and all revisions?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No documents yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:1rem;">
            {{ $documents->links() }}
        </div>
    </div>
@endsection
