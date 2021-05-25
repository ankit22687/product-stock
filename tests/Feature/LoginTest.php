<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
    }

    /** @test */
    public function test_must_enter_email_and_password()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                "success"=> false,
                "message"=> "Failed to validate data",
                "data" => [
                    "email" => [
                        "The email field is required."
                    ],
                    "password"=> [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_successful_login()
    {
        $user = factory(User::class)->create([
           'email' => 'ankit@test.com',
           'password' => bcrypt('test123'),
        ]);

        $loginData = ['email' => 'ankit@test.com', 'password' => 'test123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data" => [
                    "user" => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                    "token",
                ],
                "message"
            ]);

        $this->assertAuthenticated();
    }
}
