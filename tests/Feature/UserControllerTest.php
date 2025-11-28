<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function getApiToken()
    {
        $user = User::factory()->create([
            'password' => Hash::make('123456')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '123456',
        ]);

        $loginResponse->assertStatus(200);
        $dataResponse = $loginResponse->json('data');
        $token = $dataResponse['token'];

        return $token;
    }

    #[Test]
    public function it_returns_all_users()
    {
        $apiToken = $this->getApiToken();

        User::factory()->count(2)->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson('/api/users');
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => '',
                'data' => []
            ])
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function it_creates_a_user_successfully()
    {
        $apiToken = $this->getApiToken();
        $payload = [
            'name' => 'Usuario 01',
            'email' => 'usuario01@example.com',
            'password' => 'usuario123'
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->postJson('/api/users', $payload);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Usuário criado com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseHas('users', ['email' => 'usuario01@example.com']);
    }

    #[Test]
    public function it_fails_to_create_user_with_invalid_data()
    {
        $apiToken = $this->getApiToken();
        $payload = [
            'name' => '',
            'email' => 'usuario01',
            'password' => ''
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->postJson('/api/users', $payload);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 422,
                'message' => [],
                'data' => []
            ]);
    }

    #[Test]
    public function it_returns_a_user_by_id()
    {
        $apiToken = $this->getApiToken();
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson("/api/users/{$user->id}");
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => '',
                'data' => []
            ]);
    }

    #[Test]
    public function it_returns_404_if_user_not_found()
    {
        $apiToken = $this->getApiToken();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson('/api/users/999');
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'code' => 404,
                'message' => 'Usuário não encontrado.',
                'data' => []
            ]);
    }

    #[Test]
    public function it_updates_a_user_successfully()
    {
        $apiToken = $this->getApiToken();
        $user = User::factory()->create();
        $payload = [
            'name' => 'Usuario 01 atualizado',
            'email' => $user->email,
            'password' => 'novasenha123'
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->putJson("/api/users/{$user->id}", $payload);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Usuário atualizado com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseHas('users', ['name' => 'Usuario 01 atualizado']);
    }

    #[Test]
    public function it_deletes_a_user_successfully()
    {
        $apiToken = $this->getApiToken();
        $user = User::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->deleteJson("/api/users/{$user->id}");
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Usuário excluido com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}