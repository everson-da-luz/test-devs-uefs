<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    #[Test]
    public function it_returns_all_posts()
    {
        $mock = Mockery::mock(Post::class)->makePartial();
        $mock->shouldReceive('all')
            ->once()
            ->andReturn(new Collection([new Post(['id' => 1]), new Post(['id' => 2])]));

        $result = $mock->getAll();

        $this->assertCount(2, $result);
    }

    #[Test]
    public function it_returns_post_by_id()
    {
        $post = new Post();
        $post->setAttribute('id', 10);

        $mock = Mockery::mock(Post::class)->makePartial();
        $mock->shouldReceive('find')
            ->with(10)
            ->once()
            ->andReturn($post);

        $result = $mock->getById(10);

        $this->assertEquals(10, $result->id);
    }
}