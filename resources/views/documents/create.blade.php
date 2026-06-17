@extends('layouts.app')

@section('content')
    <a href="{{ route('home') }}" class="btn-back">← Back to Dashboard</a>
    <div class="card">
        <h1 class="mb-2" style="margin-top:0;">Create Document</h1>

        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-2">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required>
                @error('title')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="project_display">Project / SOP Search</label>
                <div class="autocomplete-container">
                    <input type="text" id="project_display" placeholder="Search by SOP or Name..." autocomplete="off" required>
                    <input type="hidden" id="project_id" name="project_id" value="{{ old('project_id') }}">
                    <div id="autocomplete_results" class="autocomplete-results"></div>
                </div>
                @error('project_id')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="pdf">Select PDF File</label>
                <input id="pdf" name="pdf" type="file" accept="application/pdf" required>
                @error('pdf')<div class="error">{{ $message }}</div>@enderror
            </div>

            <button type="submit">Create Document</button>
        </form>
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

        // Handle browser autofill or back-button state
        window.addEventListener('load', () => {
            if (hiddenInput.value) {
                const selected = projects.find(p => p.id == hiddenInput.value);
                if (selected) searchInput.value = selected.name;
            }
        });
    </script>
@endsection
