@extends('layouts.app')

@section('content')
    <div style="text-align: center; margin-top: 2rem;">
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

        <a href="{{ route('projects.index') }}" class="dash-card">
            <div class="icon">📱</div>
            <h2>Client FileManager</h2>
            <p>Preview how your projects look to your clients and end-users.</p>
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
