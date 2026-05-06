<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentRevision;
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
            ->withCount('revisions')
            ->latest()
            ->paginate(15);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $slug = $this->generateUniqueSlug($validated['title']);

        DB::transaction(function () use ($validated, $request, $slug) {
            $file = $request->file('pdf');
            $version = 1;
            $filePath = $this->storeRevisionFile($file, $slug, $version);

            $document = Document::create([
                'slug' => $slug,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'current_file_path' => $filePath,
                'current_mime_type' => $file->getMimeType() ?: 'application/pdf',
                'current_file_size' => $file->getSize(),
            ]);

            DocumentRevision::create([
                'document_id' => $document->id,
                'version' => $version,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType() ?: 'application/pdf',
                'file_size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        });

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document created and QR route is ready.');
    }

    public function edit(Document $document)
    {
        $document->load(['revisions' => function ($query) {
            $query->latest('version');
        }]);

        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        DB::transaction(function () use ($document, $validated, $request) {
            $document->title = $validated['title'];
            $document->description = $validated['description'] ?? null;

            if ($request->hasFile('pdf')) {
                $file = $request->file('pdf');
                $nextVersion = ((int) $document->revisions()->max('version')) + 1;
                $filePath = $this->storeRevisionFile($file, $document->slug, $nextVersion);

                DocumentRevision::create([
                    'document_id' => $document->id,
                    'version' => $nextVersion,
                    'file_path' => $filePath,
                    'mime_type' => $file->getMimeType() ?: 'application/pdf',
                    'file_size' => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                ]);

                $document->current_file_path = $filePath;
                $document->current_mime_type = $file->getMimeType() ?: 'application/pdf';
                $document->current_file_size = $file->getSize();
            }

            $document->save();
        });

        return redirect()
            ->route('documents.edit', $document)
            ->with('status', 'Document updated.');
    }

    public function print()
    {
        $documents = Document::query()
            ->orderBy('slug')
            ->get();

        return view('documents.print', compact('documents'));
    }

    public function printSingle(Document $document)
    {
        $documents = collect([$document]);

        return view('documents.print', compact('documents'));
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
        DB::transaction(function () use ($document) {
            $paths = $document->revisions()->pluck('file_path')->push($document->current_file_path)->filter()->unique();

            foreach ($paths as $path) {
                Storage::disk('public')->delete($path);
            }

            $document->delete();
        });

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document deleted.');
    }

    private function storeRevisionFile($file, string $slug, int $version): string
    {
        $safeOriginal = preg_replace('/[^A-Za-z0-9\._-]/', '-', $file->getClientOriginalName());
        $fileName = 'v' . $version . '_' . time() . '_' . $safeOriginal;

        return $file->storeAs('documents/' . $slug, $fileName, 'public');
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
