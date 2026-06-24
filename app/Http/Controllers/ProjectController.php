<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProjectController extends Controller
{
    public function dashboard()
    {
        $health = $this->checkSystemIntegrity();
        return view('dashboard', compact('health'));
    }

    private function checkSystemIntegrity(): array
    {
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $checks = [
            'project_route' => false,
            'document_route' => false,
        ];

        foreach ($routes as $route) {
            if ($route->getName() === 'project.show' && str_starts_with($route->uri(), 'p/')) {
                $checks['project_route'] = true;
            }
            if ($route->getName() === 'scan.show' && str_starts_with($route->uri(), 'd/')) {
                $checks['document_route'] = true;
            }
        }

        $allOk = !in_array(false, $checks, true);

        return [
            'is_healthy' => $allOk,
            'details' => $checks,
        ];
    }

    public function index()
    {
        $projects = Project::query()
            ->withCount('documents')
            ->latest()
            ->paginate(15);

        return view('projects.index', compact('projects'));
    }

    public function print()
    {
        $projects = Project::query()
            ->orderBy('name')
            ->get();

        return view('projects.print', compact('projects'));
    }

    public function printSingle(Project $project)
    {
        $projects = collect([$project]);

        return view('projects.print', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sop_number' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = $this->generateUniqueSlug($validated['name']);

        Project::create([
            'name' => $validated['name'],
            'sop_number' => $validated['sop_number'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('projects.index')
            ->with('status', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sop_number' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project->update([
            'name' => $validated['name'],
            'sop_number' => $validated['sop_number'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('projects.index')
            ->with('status', 'Project updated successfully.');
    }

    public function qr(Project $project, Request $request)
    {
        $size = (int) $request->query('size', 240);
        $size = max(120, min(1000, $size));

        $svg = QrCode::format('svg')
            ->errorCorrection('H')
            ->size($size)
            ->margin(1)
            ->generate(route('project.show', $project->slug));

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function destroy(Project $project)
    {
        abort(403, 'Project deletion is disabled. Please contact the software department.');
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? Str::limit($base, 100, '') : 'project';
        $slug = $base;
        $counter = 2;

        while (Project::query()->where('slug', $slug)->exists()) {
            $slug = Str::limit($base, 94, '') . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
