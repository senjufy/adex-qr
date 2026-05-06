<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class QrScanController extends Controller
{
    public function show(string $slug)
    {
        $document = Document::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $absolutePath = Storage::disk('public')->path($document->current_file_path);
        abort_unless(file_exists($absolutePath), 404);

        return response()->file($absolutePath, [
            'Content-Type' => $document->current_mime_type ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($document->current_file_path) . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
