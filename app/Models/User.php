<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }

    public static function rules(string $context = 'insert', $id = null): array
    {
        $rules = [
            'insert' => [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ],
            'update' => [
                'id' => 'integer|exists:users,id',
                'name' => 'sometimes|required',
                'email' => [
                    'sometimes',
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($id)
                ],
                'password' => 'sometimes|required',
            ],
            'delete' => [
                'id' => 'required|integer|exists:users,id',
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
                ],
                'email' => [
                    'required' => 'E-mail obrigatório.',
                    'email' => 'O e-mail está no formato errado.',
                    'unique' => 'Usuário já cadastrado.'
                ],
                'password' => [
                    'required' => 'Senha obrigatória.'
                ],
            ],
            'update' => [
                'id' => [
                    'required' => 'ID obrigatório.',
                    'integer' => 'ID deve ser um número.',
                    'exists' => 'Usuário não encontrado.'
                ],
                'name' => [
                    'required' => 'Nome obrigatório.'
                ],
                'email' => [
                    'required' => 'E-mail obrigatório.',
                    'password' => 'O e-mail está no formato errado.',
                    'unique' => 'Usuário já cadastrado.'
                ],
                'password' => [
                    'required' => 'Senha obrigatória.'
                ],
            ],
            'delete' => [
                'id' => [
                    'required' => 'ID obrigatório.',
                    'integer' => 'ID deve ser um número.',
                    'exists' => 'Usuário não encontrado.'
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

    public function getById(int $id):? User
    {        
        return $this->find($id);
    }

    public function updateUser($data): bool
    {
        if (!isset($data['id']) || empty($data['id'])) {
            return false;
        }

        $user = self::find($data['id']);

        if (empty($user)) {
            return false;
        }

        return $user->update($data);
    }
}
