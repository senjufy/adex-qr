<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sop_number',
        'slug',
        'description',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
