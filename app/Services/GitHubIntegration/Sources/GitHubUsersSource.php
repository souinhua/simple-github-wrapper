<?php

namespace App\Services\GitHubIntegration\Sources;

use Illuminate\Support\Collection;

interface GitHubUsersSource
{
    /**
     * Returns a collection of GitHubUser
     *
     * @param array $logins
     * @return Collection
     */
    public function get(array $logins): Collection;
}
