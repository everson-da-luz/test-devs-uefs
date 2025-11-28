<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostControllerTest extends TestCase
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
    public function it_returns_all_posts()
    {
        $apiToken = $this->getApiToken();
        $user = User::factory()->create();

        Post::factory()->count(3)->create([
            'users_id' => $user->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson('/api/posts');
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
    public function it_creates_a_post_successfully()
    {
        $apiToken = $this->getApiToken();
        $payload = [
            'users_id' => '1',
            'title' => 'Post 01',
            'slug' => 'post-01',
            'content' => 'Conteúdo do post 01'
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->postJson('/api/posts', $payload);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Postagem criada com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseHas('posts', ['slug' => 'post-01']);
    }

    #[Test]
    public function it_fails_to_create_post_with_invalid_data()
    {
        $apiToken = $this->getApiToken();
        $payload = [
            'users_id' => '',
            'title' => 'Post 01',
            'slug' => '',
            'content' => 'Conteúdo do post 01'
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->postJson('/api/posts', $payload);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'code' => 422,
                'message' => [],
                'data' => []
            ]);
    }

    #[Test]
    public function it_returns_a_post_by_id()
    {
        $apiToken = $this->getApiToken();
        $post = Post::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson("/api/posts/{$post->id}");
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => '',
                'data' => []
            ]);
    }

    #[Test]
    public function it_returns_404_if_post_not_found()
    {
        $apiToken = $this->getApiToken();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->getJson('/api/posts/999');
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'code' => 404,
                'message' => 'Post não encontrado.',
                'data' => []
            ]);
    }

    #[Test]
    public function it_updates_a_post_successfully()
    {
        $apiToken = $this->getApiToken();
        $post = Post::factory()->create();
        $payload = [
            'users_id' => $post->users_id,
            'title' => 'Post 01 atualizado',
            'slug' => $post->slug,
            'content' => 'Conteúdo do post 01 atualizado'
        ];

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->putJson("/api/posts/{$post->id}", $payload);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Postagem atualizada com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseHas('posts', ['title' => 'Post 01 atualizado']);
    }

    #[Test]
    public function it_deletes_a_post_successfully()
    {
        $apiToken = $this->getApiToken();
        $post = Post::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $apiToken")->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 200,
                'message' => 'Postagem excluida com sucesso.',
                'data' => []
            ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}