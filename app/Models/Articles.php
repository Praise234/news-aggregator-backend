<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'source',
        'author',
        'description',
        'url',
        'url_to_image',
        'published_at',
        'summary',
        'category'
    ];
}
