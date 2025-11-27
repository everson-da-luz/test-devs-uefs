<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Validation\Rule;

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

    public static function rules(string $context = 'insert', $postId = null): array
    {
        $rules = [
            'insert' => [
                'posts_id' => 'required|integer|exists:posts,id',
                'tags_id' => [
                    'required',
                    'integer',
                    'exists:tags,id',
                    Rule::unique('posts_tags')->where(function($query) use ($postId) {
                        return $query->where('posts_id', $postId);
                    })
                ]
            ],
            'delete' => [
                'posts_id' => 'required|integer|exists:posts,id',
                'tags_id' => 'required|integer|exists:posts_tags,tags_id'
            ]
        ];

        if (!array_key_exists($context, $rules)) {
            return [];
        }

        return $rules[$context];
    }

    public static function errorMessage(string $context = 'insert'): array
    {
        $errorMessages = [
            'insert' => [
                'posts_id' => [
                    'required' => 'ID da postagem obrigatória.',
                    'integer' => 'ID da postagem deve ser um número.',
                    'exists' => 'Postagem não encontrada.'
                ],
                'tags_id' => [
                    'required' => 'ID da tag obrigatória.',
                    'integer' => 'ID da tag deve ser um número.',
                    'exists' => 'Tag não encontrada.',
                    'unique' => 'Tag já adicionada a essa postagem.'
                ]
            ],
            'delete' => [
                'posts_id' => [
                    'required' => 'ID da postagem obrigatória.',
                    'integer' => 'ID da postagem deve ser um número.',
                    'exists' => 'Postagem não encontrada.'
                ],
                'tags_id' => [
                    'required' => 'ID da tag obrigatória.',
                    'integer' => 'ID da tag deve ser um número.',
                    'exists' => 'Tag não encontrada.'
                ]
            ]
        ];

        if (!array_key_exists($context, $errorMessages)) {
            return [];
        }

        return $errorMessages[$context];
    }
}
