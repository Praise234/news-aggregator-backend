<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\UserService;
use App\Models\User;








class UserServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test if UserService creates User
     */
    public function test_it_creates_a_user(): void
    {
        $userService = new UserService();

        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        $user= $userService->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johndoe@example.com', $user->email);
    }
}
