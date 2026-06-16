@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <h1 class="mb-2">Edit Project</h1>

        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-1">
                <label for="name">Project Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label for="sop_number">SOP Number</label>
                <input type="text" name="sop_number" id="sop_number" value="{{ old('sop_number', $project->sop_number) }}" required>
                @error('sop_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label for="description">Description (Optional)</label>
                <textarea name="description" id="description" rows="4">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn">Update Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
