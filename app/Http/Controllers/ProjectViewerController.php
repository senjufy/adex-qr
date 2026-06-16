<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectViewerController extends Controller
{
    /**
     * Display the public project landing page (Isolated File System).
     */
    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
            ->with(['documents' => function ($query) {
                $query->orderBy('title');
            }])
            ->firstOrFail();

        return view('projects.viewer', compact('project'));
    }
}
