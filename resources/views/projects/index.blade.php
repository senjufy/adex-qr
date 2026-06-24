@extends('layouts.app')

@section('content')
    <a href="{{ route('home') }}" class="btn-back">← Back to Dashboard</a>
    <div class="card">
        <div class="mb-2" style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
            <div>
                <h1 style="margin:.2rem 0;">Projects</h1>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <a class="btn btn-secondary" href="{{ route('projects.print') }}">Bulk Print QRs</a>
                <a class="btn" href="{{ route('projects.create') }}">New Project</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>QR</th>
                    <th>Name</th>
                    <th>SOP Number</th>
                    <th>Description</th>
                    <th>Documents</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td>
                            <img
                                src="{{ route('projects.qr', ['project' => $project->id, 'size' => 100]) }}"
                                alt="QR {{ $project->slug }}"
                                width="80"
                                height="80"
                            >
                        </td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->sop_number }}</td>
                        <td>{{ Str::limit($project->description, 50) }}</td>
                        <td>{{ $project->documents_count }}</td>
                        <td>
                            <div class="actions-grid">
                                <a class="btn btn-secondary" href="{{ route('projects.edit', $project) }}">Edit</a>
                                <a class="btn" href="{{ route('project.show', $project->slug) }}" target="_blank" rel="noopener">View Landing</a>
                                <a class="btn btn-secondary" href="{{ route('projects.print.single', $project) }}" target="_blank" rel="noopener">Print QR</a>
                                <button type="button" class="btn btn-danger" disabled title="Contact software department for project deletion">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No projects yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:1rem;">
            {{ $projects->links() }}
        </div>
    </div>
@endsection
