<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::query()
            ->latest()
            ->paginate(15);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('documents.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $slug = $this->generateUniqueSlug($validated['title']);

        $file = $request->file('pdf');
        $filePath = $this->storeDocumentFile($file, $project->slug, $slug);

        Document::create([
            'project_id' => $project->id,
            'slug' => $slug,
            'title' => $validated['title'],
            'project_name' => $project->name,
            'description' => $validated['description'] ?? null,
            'current_file_path' => $filePath,
            'current_mime_type' => $file->getMimeType() ?: 'application/pdf',
            'current_file_size' => $file->getSize(),
        ]);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document created successfully.');
    }

    public function printSingle(Document $document)
    {
        $documents = collect([$document]);

        return view('documents.print', compact('documents'));
    }

    public function edit(Document $document)
    {
        $projects = Project::orderBy('name')->get();
        return view('documents.edit', compact('document', 'projects'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::findOrFail($validated['project_id']);

        $document->update([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'project_name' => $project->name,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document info updated.');
    }

    public function qr(Document $document, Request $request)
    {
        $size = (int) $request->query('size', 240);
        $size = max(120, min(1000, $size));

        $svg = QrCode::format('svg')
            ->errorCorrection('H')
            ->size($size)
            ->margin(1)
            ->generate(route('scan.show', $document->slug));

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function destroy(Document $document)
    {
        if ($document->current_file_path) {
            Storage::disk('public')->delete($document->current_file_path);
        }

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document deleted.');
    }

    private function storeDocumentFile($file, string $projectSlug, string $docSlug): string
    {
        $safeOriginal = preg_replace('/[^A-Za-z0-9\._-]/', '-', $file->getClientOriginalName());
        $fileName = time() . '_' . $safeOriginal;

        return $file->storeAs('projects/' . $projectSlug . '/' . $docSlug, $fileName, 'public');
    }

    private function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $base = $base !== '' ? Str::limit($base, 100, '') : 'document';
        $slug = $base;
        $counter = 2;

        while (Document::query()->where('slug', $slug)->exists()) {
            $slug = Str::limit($base, 94, '') . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
