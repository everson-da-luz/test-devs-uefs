<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    #[Test]
    public function it_returns_all_users()
    {
        $mock = Mockery::mock(User::class)->makePartial();
        $mock->shouldReceive('all')
            ->once()
            ->andReturn(new Collection([new User(['id' => 1]), new User(['id' => 2])]));

        $result = $mock->getAll();

        $this->assertCount(2, $result);
    }

    #[Test]
    public function it_returns_user_by_id()
    {
        $user = new User();
        $user->setAttribute('id', 10);

        $mock = Mockery::mock(User::class)->makePartial();
        $mock->shouldReceive('find')
            ->with(10)
            ->once()
            ->andReturn($user);

        $result = $mock->getById(10);

        $this->assertEquals(10, $result->id);
    }

    #[Test]
    public function it_returns_user_by_email()
    {
        $user = new User();
        $user->setAttribute('email', 'test@example.com');

        $mock = Mockery::mock(User::class)->makePartial();
        $mock->shouldReceive('where')
            ->with('email', 'test@example.com')
            ->once()
            ->andReturnSelf();
        $mock->shouldReceive('first')
            ->once()
            ->andReturn($user);

        $result = $mock->getByEmail('test@example.com');

        $this->assertEquals('test@example.com', $result->email);
    }

    #[Test]
    public function it_returns_user_by_api_token()
    {
        $user = new User();
        $user->setAttribute('api_token', 'abc123');

        $mock = Mockery::mock(User::class)->makePartial();
        $mock->shouldReceive('where')
            ->with('api_token', 'abc123')
            ->once()
            ->andReturnSelf();
        $mock->shouldReceive('first')
            ->once()
            ->andReturn($user);

        $result = $mock->getByApiToken('abc123');

        $this->assertEquals('abc123', $result->api_token);
    }
}