<?php

namespace App\Services\GitHubIntegration\Repositories;

use App\Services\GitHubIntegration\Sources\GitHubUsersCache;
use App\Services\GitHubIntegration\Sources\GitHubUsersSource;
use Illuminate\Support\Collection;

class GitHubUsersRepository
{
    /**
     * The cache source where GitHubUser is
     * cached
     *
     * @var GitHubUsersCache
     */
    private GitHubUsersCache $cache;

    /**
     * The list of sources to find GitHubUser
     *
     * @var array
     */
    private array $sources;

    /**
     * Constructs the GitHubUsersRepository object
     *
     * @param GitHubUsersSource ...$sources
     */
    public function __construct(GitHubUsersSource ...$sources)
    {
        $this->cache = new GitHubUsersCache();
        $this->sources = [];

        foreach ($sources as $source) {
            $this->addSource($source);
        }
    }

    /**
     * Adds a GitHubUsersSource for this repository to
     * search GitHubUsers on.
     *
     * Note: The sequence of the source takes precedence. Meaning
     * the first source that came in is the first source we are
     * going to search on.
     *
     * @param GitHubUsersSource $source
     */
    public function addSource(GitHubUsersSource $source)
    {
        $this->sources [] = $source;
    }

    /**
     * @param array $logins
     * @return GitHubUsersList
     */
    public function fetch(array $logins): GitHubUsersList
    {
        // sanitize the logins
        $cleanLogins = $this->sanitizeLogins($logins);

        // instantiate the list resource
        $list = new GitHubUsersList($cleanLogins);

        /** @var GitHubUsersSource $source */
        foreach ($this->sources as $source) {
            // fetch the logins that has not been found yet.
            $logins_to_fetch = $list->notFoundLogins();

            // fetch the missing logins from the GitHubUsersSource
            $gitHubUsers = $source->get($logins_to_fetch);

            // adds the found GitHubUsers to the resource list
            $list->addGitHubUsers($gitHubUsers);
        }

        // store the new list of GitHubUsers to the cache
        $this->storeGitHubUsersInCache($list->getGitHubUsers());

        return $list;
    }

    /**
     * Store the new list of GitHubUsers to the cache. All the existing GitHubUsers
     * in the cache will refresh to 2 minutes from now.
     *
     * @param Collection $gitHubUsers
     */
    private function storeGitHubUsersInCache(Collection $gitHubUsers) {
        foreach ($gitHubUsers as $gitHubUser) {
            $this->cache->add($gitHubUser);
        }
    }

    /**
     * Cleans the array of logins by removing empty string items
     * and making all the items are unique.
     *
     * @param array $logins
     * @return array
     */
    private function sanitizeLogins(array $logins): array
    {
        return array_filter(array_unique($logins));
    }
}
