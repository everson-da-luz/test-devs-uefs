<?php

namespace Tests\Unit;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TagModelTest extends TestCase
{
    #[Test]
    public function it_returns_all_tags()
    {
        $mock = Mockery::mock(Tag::class)->makePartial();
        $mock->shouldReceive('all')
            ->once()
            ->andReturn(new Collection([new Tag(['id' => 1]), new Tag(['id' => 2])]));

        $result = $mock->getAll();

        $this->assertCount(2, $result);
    }

    #[Test]
    public function it_returns_tag_by_id()
    {
        $tag = new Tag();
        $tag->setAttribute('id', 10);

        $mock = Mockery::mock(Tag::class)->makePartial();
        $mock->shouldReceive('find')
            ->with(10)
            ->once()
            ->andReturn($tag);

        $result = $mock->getById(10);

        $this->assertEquals(10, $result->id);
    }
}