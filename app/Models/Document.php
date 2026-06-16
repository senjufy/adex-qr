<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'slug',
        'title',
        'project_name',
        'description',
        'current_file_path',
        'current_mime_type',
        'current_file_size',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
