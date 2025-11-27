<?php

namespace App\Models;

use App\Models\Post;
use App\Models\PostTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            (new PostTag())->getTable(),
            'tags_id',
            'posts_id'
        )->using(PostTag::class);
    }

    public function posts_tags(): HasMany
    {
        return $this->hasMany(PostTag::class, 'tags_id');
    }
}
