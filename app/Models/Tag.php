<?php

namespace App\Models;

use App\Models\Post;
use App\Models\PostTag;
use Illuminate\Database\Eloquent\Collection;
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

    public static function rules(string $context = 'insert'): array
    {
        $rules = [
            'insert' => [
                'name' => 'required'
            ],
            'update' => [
                'id' => 'integer|exists:tags,id',
                'name' => 'sometimes|required'
            ],
            'delete' => [
                'id' => 'required|integer|exists:tags,id',
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
                'name' => [
                    'required' => 'Nome obrigatório.'
                ]
            ],
            'update' => [
                'id' => [
                    'required' => 'ID obrigatório.',
                    'integer' => 'ID deve ser um número.',
                    'exists' => 'Tag não encontrada.'
                ],
                'name' => [
                    'required' => 'Nome obrigatório.'
                ]
            ],
            'delete' => [
                'id' => [
                    'required' => 'ID obrigatório.',
                    'integer' => 'ID deve ser um número.',
                    'exists' => 'Tag não encontrada.'
                ]
            ]
        ];

        if (!array_key_exists($context, $errorMessages)) {
            return [];
        }

        return $errorMessages[$context];
    }

    public function getAll():? Collection
    {
        return $this->all();
    }

    public function getById(int $id):? Tag
    {        
        return $this->find($id);
    }

    public function updateTag($data): bool
    {
        if (!isset($data['id']) || empty($data['id'])) {
            return false;
        }

        $tag = self::find($data['id']);

        if (empty($tag)) {
            return false;
        }

        return $tag->update($data);
    }
}
