<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'current_file_path',
        'current_mime_type',
        'current_file_size',
    ];

    public function revisions()
    {
        return $this->hasMany(DocumentRevision::class);
    }
}
