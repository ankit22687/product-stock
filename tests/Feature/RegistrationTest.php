<?php

namespace Tests\Feature;

use Tests\TestCase;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
    }

    /** @test */
    public function test_required_fields_for_registration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "success"=> false,
                "message"=> "Failed to validate data",
                "data" => [
                    "name" => [
                        "The name field is required."
                    ],
                    "email"=> [
                        "The email field is required."
                    ],
                    "password"=> [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    /** @test */
    public function test_successful_registration()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "john@test.com",
            "password" => "test12345",
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
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
    }
}
