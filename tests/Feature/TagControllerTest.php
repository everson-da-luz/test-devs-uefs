<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TagControllerTest extends TestCase
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
    public function it_returns_all_tags()
    {
        $apiToken = $this->getApiToken();

        Tag::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson('/api/tags');
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
    public function it_creates_a_tag_successfully()
    {
        $apiToken = $this->getApiToken();
        $payload = [
            'name' => 'HTML'
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->postJson('/api/tags', $payload);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Tag criada com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseHas('tags', ['name' => 'HTML']);
    }

    #[Test]
    public function it_fails_to_create_tag_with_invalid_data()
    {
        $apiToken = $this->getApiToken();
        $payload = [
            'name' => ''
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->postJson('/api/tags', $payload);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 422,
                'message' => [],
                'data' => []
            ]);
    }

    #[Test]
    public function it_returns_a_tag_by_id()
    {
        $apiToken = $this->getApiToken();
        $tag = Tag::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson("/api/tags/{$tag->id}");
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => '',
                'data' => []
            ]);
    }

    #[Test]
    public function it_returns_404_if_tag_not_found()
    {
        $apiToken = $this->getApiToken();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson('/api/tags/999');
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'code' => 404,
                'message' => 'Tag não encontrada.',
                'data' => []
            ]);
    }

    #[Test]
    public function it_updates_a_tag_successfully()
    {
        $apiToken = $this->getApiToken();
        $tag = Tag::factory()->create();
        $payload = [
            'name' => 'HTML atualizado'
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->putJson("/api/tags/{$tag->id}", $payload);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Tag atualizada com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseHas('tags', ['name' => 'HTML atualizado']);
    }

    #[Test]
    public function it_deletes_a_tag_successfully()
    {
        $apiToken = $this->getApiToken();
        $tag = Tag::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->deleteJson("/api/tags/{$tag->id}");
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Tag excluida com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
