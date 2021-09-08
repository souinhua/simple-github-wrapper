<?php

namespace App\Services\GitHubIntegration\Sources;

use App\Services\GitHubIntegration\Models\GitHubUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GitHubUsersCache implements GitHubUsersSource
{
    /**
     * The tag that contains all the GitHubUser cache
     *
     * @var string
     */
    private const TAG = 'github_users';

    /**
     * Stores the GitHubUser in the cache for 2 minutes
     *
     * @param GitHubUser $gitHubUser
     */
    public function add(GitHubUser $gitHubUser)
    {
        Cache::tags(self::TAG)->put($gitHubUser->getCacheKey(), $gitHubUser, now()->addMinutes(2));
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
        foreach ($logins as $login) {
            /** @var GitHubUser $gitHubUser */
            $gitHubUser = Cache::tags(self::TAG)->get("github_user:{$login}");
            if ($gitHubUser) {
                // flag this GitHubUser that it came from the cache
                $gitHubUser->setIsFetchedFromCache(true);

                // add to list
                $gitHubUsers->add($gitHubUser);
            }
        }

        return $gitHubUsers;
    }
}
