<?php

namespace App\Services\GitHubIntegration\Sources;

use App\Exceptions\GitHubUserNotFoundException;
use App\Services\GitHubIntegration\Models\GitHubUser;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubUsersApi implements GitHubUsersSource
{
    /**
     * The GitHub URI for fetching users
     */
    private const USER_URI = "https://api.github.com/users/";

    /**
     * The required headers for GitHub API request
     *
     * @return string[]
     */
    private function getRequestHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.github.v3+json'
        ];
    }

    /**
     * Fetches the GitHub personal credentials from
     * the env
     *
     * @return array
     */
    private function getBasicAuthCredentials(): array
    {
        return [
            env('GITHUB_PERSONAL_USERNAME'),
            env('GITHUB_PERSONAL_ACCESS_TOKEN')
        ];
    }

    /**
     * Returns a collection of GitHubUser
     *
     * @param array $logins
     * @return Collection
     */
    public function get(array $logins): Collection
    {
        $gitHubUsers = collect([]);

        // fetch from GitHub API
        $responses = $this->fetchGitHubUsersConcurrently($logins);

        /** @var Response $response */
        // iterate responses and try to create instance
        foreach ($responses as $login => $response) {
            try {
                $gitHubUser = $this->createGitHubUser($login, $response);
                $gitHubUsers->add($gitHubUser);
            } catch (GitHubUserNotFoundException $exception) {

                Log::alert("GitHub API cannot find user [{$login}].");
            }
        }

        return $gitHubUsers;
    }

    /**
     * Fetch all users in the logins array from the GitHub API. Each logins
     * will be requested concurrently to improve performance. Returns the
     * array of responses and the "login" as the array key.
     *
     * Note: This does not request logins one-by-one.
     *
     * @param array $logins
     * @return array
     */
    private function fetchGitHubUsersConcurrently(array $logins): array
    {
        $headers = $this->getRequestHeaders();
        list ($username, $token) = $this->getBasicAuthCredentials();

        return Http::pool(function (Pool $pool) use ($logins, $headers, $username, $token) {
            $requests = [];

            foreach ($logins as $login) {
                $requests[] = $pool
                    ->as($login)
                    ->withBasicAuth($username, $token)
                    ->withHeaders($headers)
                    ->get(self::USER_URI . $login);
            }

            return $requests;
        });
    }

    /**
     * Creates an instance of a GitHubUser coming from a Response
     * of the GitHub API. If response has errors, this will throw
     * GitHubUserNotFoundException.
     *
     * @param string $login
     * @param Response $response
     * @return GitHubUser
     * @throws GitHubUserNotFoundException
     */
    private function createGitHubUser(string $login, Response $response): GitHubUser
    {
        if ($response->failed()) {
            throw new GitHubUserNotFoundException($login);
        }

        return new GitHubUser($response);
    }
}
