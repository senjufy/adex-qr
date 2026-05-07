@extends('layouts.app')

@section('content')
    <div class="card">
        <h1 class="mb-2" style="margin-top:0;">Create Document</h1>

        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-2">
                <div class="muted">Slug will be generated automatically from the title for QR route usage.</div>
            </div>

            <div class="mb-2">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required>
                @error('title')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="sop_number">SOP Number</label>
                <input id="sop_number" name="sop_number" type="text" value="{{ old('sop_number') }}" required>
                @error('sop_number')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="project_name">Project Name</label>
                <input id="project_name" name="project_name" type="text" value="{{ old('project_name') }}" required>
                @error('project_name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="mb-2">
                <label for="pdf">PDF File</label>
                <input id="pdf" name="pdf" type="file" accept="application/pdf" required>
                @error('pdf')<div class="error">{{ $message }}</div>@enderror
            </div>

            <button type="submit">Create Document</button>
        </form>
    </div>
@endsection
