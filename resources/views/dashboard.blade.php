@extends('layouts.app')

@section('content')
    @if(!$health['is_healthy'])
        <div style="background: #fee2e2; border: 2px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: .75rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem;">⚠️</div>
            <div>
                <strong style="display: block; font-size: 1.1rem;">CRITICAL ERROR: Route Integrity Failure</strong>
                <span style="font-size: .95rem;">The permanent QR routes have been modified. Physical labels in the field may stop working!</span>
            </div>
        </div>
    @else
        <div style="text-align: right; margin-bottom: 1rem;">
            <span title="All the QR out in the field are safe" style="display: inline-flex; align-items: center; gap: .4rem; background: #f0fdf4; color: #166534; padding: .3rem .7rem; border-radius: 9999px; font-size: .8rem; font-weight: 600; border: 1px solid #dcfce7; cursor: help;">
                <span style="color: #22c55e;">●</span> QR Route Integrity: Verified Safe
            </span>
        </div>
    @endif

    <div style="text-align: center; margin-top: 1rem;">
        <h1 style="color: #111827; font-size: 2rem; margin-bottom: 0.5rem;">Welcome to QR Manager</h1>
        <p style="color: #6b7280; font-size: 1.1rem;">Select an action below to get started</p>
    </div>

    <div class="dashboard-grid">
        <a href="{{ route('projects.index') }}" class="dash-card">
            <div class="icon">📁</div>
            <h2>Project List</h2>
            <p>View and manage all existing projects and their QR codes.</p>
        </a>

        <a href="{{ route('projects.create') }}" class="dash-card">
            <div class="icon">➕</div>
            <h2>Create Project</h2>
            <p>Start a new project container for your technical documents.</p>
        </a>

        <a href="{{ route('documents.index') }}" class="dash-card">
            <div class="icon">📄</div>
            <h2>All Documents</h2>
            <p>Browse every document uploaded across all projects.</p>
        </a>

        <a href="{{ route('documents.create') }}" class="dash-card">
            <div class="icon">📤</div>
            <h2>Add Document</h2>
            <p>Upload a new PDF and assign it to an existing project.</p>
        </a>
    </div>
@endsection
