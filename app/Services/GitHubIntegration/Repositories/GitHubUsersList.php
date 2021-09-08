<?php

namespace App\Services\GitHubIntegration\Repositories;

use App\Services\GitHubIntegration\Models\GitHubUser;
use Illuminate\Support\Collection;

class GitHubUsersList
{
    /**
     * The list of logins we are trying to fetch
     *
     * @var array
     */
    private array $logins;

    /**
     * The collection of GitHubUser
     *
     * @var Collection
     */
    private Collection $gitHubUsers;


    /**
     * Constructs the GitHubUsersList object
     *
     * @param array $logins
     */
    public function __construct(array $logins)
    {
        $this->logins = $logins;

        $this->gitHubUsers = collect();
    }

    /**
     * Returns the list of logins that
     * cannot be found.
     *
     * @return array
     */
    public function notFoundLogins(): array
    {
        $found_logins = $this->gitHubUsers->pluck('login')->toArray();

        return array_diff($this->logins, $found_logins);
    }

    /**
     * Return the collection of GitHubUser
     *
     * @param Collection $gitHubUsers
     */
    public function addGitHubUsers(Collection $gitHubUsers)
    {
        foreach ($gitHubUsers as $gitHubUser) {
            $this->gitHubUsers->add($gitHubUser);
        }
    }

    /**
     * @return Collection
     */
    public function getGitHubUsers(): Collection
    {
        return $this->gitHubUsers->sortBy(function (GitHubUser $gitHubUser) {
            return $gitHubUser->login;
        })->values();
    }
}
