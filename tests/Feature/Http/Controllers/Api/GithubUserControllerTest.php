<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class GithubUserControllerTest extends TestCase
{
    /**
     * Test a successful fetch
     *
     * @return void
     */
    public function test_successful_fetch()
    {
        $token = $this->getUserCreatedToken();
        $headers = [
            'Authorization' => "Bearer $token"
        ];

        $response = $this
            ->withHeaders($headers)
            ->json('GET', '/api/github-users', [
                'logins' => [
                    'souinhua',
                    'taylorotwell',
                    'no_one_151asd@'
                ]
            ]);

        $response->assertSuccessful();
    }

    /**
     * Generate a token
     *
     * @return string
     */
    private function getUserCreatedToken(): string
    {
        /** @var User $user */
        $user = User::factory()->make();
        $user->save();

        return $user->getPlainTextToken();
    }

    /**
     * Test an unauthorized fetch
     */
    public function test_unauthorized_fetch()
    {
        $headers = [
            'Authorization' => "Bearer None"
        ];

        $response = $this
            ->withHeaders($headers)
            ->json('GET', '/api/github-users', [
                'logins' => [
                    'souinhua',
                    'taylorotwell',
                    'no_one_151asd@'
                ]
            ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
