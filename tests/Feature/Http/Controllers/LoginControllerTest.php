<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * Test successful login
     *
     * @return void
     */
    public function test_successful_login()
    {
        $test_user_factory = User::factory();

        // create a user we want to login
        $user = $test_user_factory->make();
        $user->save();

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertSuccessful();
    }

    /**
     * Test failed login
     *
     * @return void
     */
    public function test_failed_login()
    {
        $response = $this->json('POST', '/login', [
            'email' => 'invalid@email.thi',
            'password' => 'password42'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
