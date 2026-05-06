<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version',
        'file_path',
        'mime_type',
        'file_size',
        'original_name',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
