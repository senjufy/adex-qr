@extends('layouts.app')

@section('content')
    <div class="card mb-2">
        <h1 style="margin-top:0;">Edit Document</h1>
        <div class="muted mb-2">Internal Slug: <strong>{{ $document->slug }}</strong></div>

        <form method="POST" action="{{ route('documents.update', $document) }}">
            @csrf
            @method('PUT')

            <div class="mb-2">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $document->title) }}" required>
                @error('title')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="project_id">Project</label>
                <select id="project_id" name="project_id" required>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $document->project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $document->description) }}</textarea>
                @error('description')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="actions">
                <button type="submit">Save Info</button>
                <a class="btn btn-secondary" href="{{ route('scan.show', $document->slug) }}" target="_blank" rel="noopener">View PDF</a>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-top:0;">File Management</h2>
        <p class="muted">To change the file itself, delete this document and upload a new one.</p>
        <div class="mb-2">
            <strong>Current File:</strong> {{ basename($document->current_file_path) }} ({{ number_format($document->current_file_size / 1024, 1) }} KB)
        </div>
        <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Permanently delete this document and its file?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Document Permanently</button>
        </form>
    </div>
@endsection
