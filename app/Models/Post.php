<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'users_id',
        'title',
        'slug',
        'content'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            (new PostTag())->getTable(),
            'posts_id',
            'tags_id'
        )->using(PostTag::class);
    }

    public function posts_tags(): hasMany
    {
        return $this->hasMany(PostTag::class, 'posts_id');
    }

    public static function rules(string $context = 'insert'): array
    {
        $rules = [
            'insert' => [
                'users_id' => 'required|exists:users,id',
                'title' => 'required',
                'slug' => 'required'
            ],
            'update' => [
                'id' => 'integer|exists:posts,id',
                'users_id' => 'sometimes|required|exists:users,id',
                'title' => 'sometimes|required',
                'slug' => 'sometimes|required'
            ],
            'delete' => [
                'id' => 'required|integer|exists:posts,id',
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
                'users_id' => [
                    'required' => 'Usuário obrigatório.',
                    'exists' => 'Usuário não encontrado.'
                ],
                'title' => [
                    'required' => 'Título obrigatório.'
                ],
                'slug' => [
                    'required' => 'Slug obrigatório.'
                ]
            ],
            'update' => [
                'id' => [
                    'required' => 'ID obrigatório.',
                    'integer' => 'ID deve ser um número.',
                    'exists' => 'Postagem não encontrada.'
                ],
                'users_id' => [
                    'required' => 'Usuário obrigatório.',
                    'exists' => 'Usuário não encontrado.'
                ],
                'title' => [
                    'required' => 'Título obrigatório.'
                ],
                'slug' => [
                    'required' => 'Slug obrigatório.'
                ]
            ],
            'delete' => [
                'id' => [
                    'required' => 'ID obrigatório.',
                    'integer' => 'ID deve ser um número.',
                    'exists' => 'Postagem não encontrada.'
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

    public function getById(int $id):? Post
    {        
        return $this->find($id);
    }

    public function updatePost($data): bool
    {
        if (!isset($data['id']) || empty($data['id'])) {
            return false;
        }

        $post = self::find($data['id']);

        if (empty($post)) {
            return false;
        }

        return $post->update($data);
    }
}
