@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <h1 class="mb-2">New Project</h1>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="mb-1">
                <label for="name">Project Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label for="sop_number">SOP Number</label>
                <input type="text" name="sop_number" id="sop_number" value="{{ old('sop_number') }}" required>
                @error('sop_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label for="description">Description (Optional)</label>
                <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn">Create Project</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
