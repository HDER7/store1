<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_success(): void
    {
        $response = $this->post('/api/v1/register', [
            "name" => "Juan",
            "email" => "juan@gmail.com",
            "password" => "Hola123"
        ]);
        $response->assertStatus(201);
    }

    public function test_register_user_fail(): void{
        $response = $this->post('/api/v1/register', []) ->assertStatus(400);
    }

    public function test_login_user_success(): void{
        $this->test_register_user_success();
        $response = $this->post('/api/v1/login', [
            "email" => "juan@gmail.com",
            "password" => "Hola123"
        ]);

        $r = $response->getContent();
        $data = json_decode($r, true);

        $this->assertArrayHasKey("token", $data);
        $response->assertStatus(200);
    }
}
