<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    protected $table = 'posts_tags';

    protected $fillable = [
        'posts_id',
        'tags_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function tags(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
