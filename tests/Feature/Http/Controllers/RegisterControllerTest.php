<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful registration
     *
     * @return void
     */
    public function test_successful_register()
    {
        $email = 'first@kumu.ph';
        $response = $this->json('POST', '/register', [
            'name' => 'The First User',
            'email' => $email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.user.email', $email);
    }

    /**
     * Test failed registration
     *
     * @return void
     */
    public function test_failed_register()
    {
        $email = 'invalid.email214';
        $response = $this->json('POST', '/register', [
            'name' => 'The Failed User',
            'email' => $email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
