@extends('layouts.app')

@section('content')
    <a href="{{ route('home') }}" class="btn-back">← Back to Dashboard</a>
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
                <label for="project_display">Project / SOP Search</label>
                <div class="autocomplete-container">
                    <input type="text" id="project_display" placeholder="Search by SOP or Name..." autocomplete="off" required>
                    <input type="hidden" id="project_id" name="project_id" value="{{ old('project_id', $document->project_id) }}">
                    <div id="autocomplete_results" class="autocomplete-results"></div>
                </div>
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
        <p class="muted">File deletion is restricted. Please contact the software department if you need to remove a file.</p>
        <div class="mb-2">
            <strong>Current File:</strong> {{ basename($document->current_file_path) }} ({{ number_format($document->current_file_size / 1024, 1) }} KB)
        </div>
        <div style="display: flex; align-items: center;">
            <button type="button" class="btn btn-danger" disabled>Delete Document Permanently</button>
            <span class="help-icon" title="Please contact software department for file deletion">?</span>
        </div>
    </div>

    <script>
        const projects = @json($projects);
        const searchInput = document.getElementById('project_display');
        const hiddenInput = document.getElementById('project_id');
        const resultsBox = document.getElementById('autocomplete_results');

        searchInput.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            resultsBox.innerHTML = '';
            
            if (!val) {
                resultsBox.style.display = 'none';
                return;
            }

            const filtered = projects.filter(p => 
                p.name.toLowerCase().includes(val) || 
                p.sop_number.toLowerCase().includes(val)
            );

            if (filtered.length > 0) {
                filtered.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-item';
                    div.innerHTML = `<span class="sop-tag">${p.sop_number}</span> ${p.name}`;
                    div.onclick = function() {
                        searchInput.value = p.name;
                        hiddenInput.value = p.id;
                        resultsBox.style.display = 'none';
                    };
                    resultsBox.appendChild(div);
                });
                resultsBox.style.display = 'block';
            } else {
                resultsBox.style.display = 'none';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target !== searchInput) {
                resultsBox.style.display = 'none';
            }
        });

        // Initialize state on load
        window.addEventListener('load', () => {
            if (hiddenInput.value) {
                const selected = projects.find(p => p.id == hiddenInput.value);
                if (selected) searchInput.value = selected.name;
            }
        });
    </script>
@endsection
