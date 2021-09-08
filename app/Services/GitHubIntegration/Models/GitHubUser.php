<?php

namespace App\Services\GitHubIntegration\Models;

use Illuminate\Http\Client\Response;

class GitHubUser
{
    /**
     * The list of fields we are going to store
     */
    private const FIELDS = [
        'id',
        'login',
        'name',
        'company',
        'followers',
        'public_repos'
    ];

    /**
     * Checks if this GithubUser is from cache
     *
     * @var bool
     */
    public bool $is_fetched_from_cache;

    /**
     * The GithubUser requires the Response object from the
     * HTTP Client of the GitHub API
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $responseArray = $response->json();

        // only set the fields that we need
        foreach (self::FIELDS as $field) {
            $this->{$field} = $responseArray[$field] ?? null;
        }

        $this->is_fetched_from_cache = false;
        $this->average_followers = $this->getAveragePublicRepositoryFollowers();
    }

    /**
     * The average number of followers
     *
     * @return float|int
     */
    private function getAveragePublicRepositoryFollowers()
    {
        if ($this->public_repos > 0) {
            return $this->followers / $this->public_repos;
        }

        return 0;
    }

    /**
     * Checks if this came from the cache
     *
     * @return bool
     */
    public function isFetchedFromCache(): bool
    {
        return $this->is_fetched_from_cache;
    }

    /**
     * Sets if it came from cache or not
     *
     * @param bool $isFetchedFromCache
     */
    public function setIsFetchedFromCache(bool $isFetchedFromCache): void
    {
        $this->is_fetched_from_cache = $isFetchedFromCache;
    }

    /**
     * Generates a cache key for this
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return "github_user:$this->login";
    }
}
