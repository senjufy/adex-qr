@extends('layouts.app')

@section('content')
    <div class="card mb-2">
        <h1 style="margin-top:0;">Edit Document</h1>
        <div class="muted mb-2">Slug is permanent: <strong>{{ $document->slug }}</strong></div>

        <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-2">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $document->title) }}" required>
                @error('title')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="sop_number">SOP Number</label>
                <input id="sop_number" name="sop_number" type="text" value="{{ old('sop_number', $document->sop_number) }}" required>
                @error('sop_number')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="project_name">Project Name</label>
                <input id="project_name" name="project_name" type="text" value="{{ old('project_name', $document->project_name) }}" required>
                @error('project_name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $document->description) }}</textarea>
                @error('description')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="pdf">Upload New PDF Revision (optional)</label>
                <input id="pdf" name="pdf" type="file" accept="application/pdf">
                @error('pdf')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="actions">
                <button type="submit">Save Changes</button>
                <a class="btn btn-secondary" href="{{ route('scan.show', $document->slug) }}" target="_blank" rel="noopener">Open Current PDF</a>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-top:0;">Revision History</h2>
        <table>
            <thead>
                <tr>
                    <th>Version</th>
                    <th>File</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($document->revisions as $revision)
                    <tr>
                        <td>v{{ $revision->version }}</td>
                        <td>{{ $revision->original_name }}</td>
                        <td>{{ number_format($revision->file_size / 1024, 1) }} KB</td>
                        <td>{{ $revision->created_at }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">No revisions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
