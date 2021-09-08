<?php

namespace Tests\Unit\Services\GitHubIntegration\Repositories;

use App\Services\GitHubIntegration\Models\GitHubUser;
use App\Services\GitHubIntegration\Repositories\GitHubUsersRepository;
use App\Services\GitHubIntegration\Sources\GitHubUsersApi;
use App\Services\GitHubIntegration\Sources\GitHubUsersCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GitHubUserRepositoryTest extends TestCase
{
    /**
     * GitHubUserRepository instance
     *
     * @var GitHubUsersRepository
     */
    private $repository;

    /**
     * Constructs the GitHubUserRepositoryTest object
     */
    public function __construct()
    {
        parent::__construct();

        // add in the sources
        $this->repository = new GitHubUsersRepository(
            new GitHubUsersCache(),
            new GitHubUsersApi()
        );
    }

    /**
     * This function is called before every test case
     */
    public function setUp(): void
    {
        parent::setUp();

        // flush the cache every test
        Cache::flush();
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_fetch_single_user()
    {
        $list = $this->repository->fetch(['souinhua']);

        $gitHubUsers = $list->getGitHubUsers();

        // assert that there is 1 in the list
        $this->assertEquals(1, $gitHubUsers->count());

        $souinhua = $gitHubUsers->first();

        // assert that it really fetched the name from GitHub API
        $this->assertEquals("Janssen Canturias", $souinhua->name);
    }

    /**
     * Test multi user fetch
     */
    public function test_fetch_multi_user()
    {
        $list = $this->repository->fetch(['souinhua', 'fabpot', 'taylorotwell', 'andrew']);

        $gitHubUsers = $list->getGitHubUsers();

        // assert that there is 4 in the list
        $this->assertEquals(4, $gitHubUsers->count());
    }

    /**
     * Test multi user fetch
     */
    public function test_fetch_multi_user_with_non_existing()
    {
        $list = $this->repository->fetch(['souinhua', 'fabpot', 'taylorotwell', 'andrew', 'asd12sprak']);

        $gitHubUsers = $list->getGitHubUsers();

        // assert that there is 4 in the list and the other one does not exist
        $this->assertEquals(4, $gitHubUsers->count());

        // check in not found items
        $last_item = collect($list->notFoundLogins())->first();
        $this->assertEquals('asd12sprak', $last_item);
    }

    /**
     * Fetch not existing user
     */
    public function test_not_existing_user()
    {
        $list = $this->repository->fetch(['souinhua123asd']);

        $gitHubUsers = $list->getGitHubUsers();

        // assert that there is 1 in the list
        $this->assertEquals(0, $gitHubUsers->count());
    }

    /**
     * Caching test
     */
    public function test_user_is_stored_in_cache()
    {
        $logins = ['souinhua', 'fabpot', 'taylorotwell', 'andrew'];

        $list = $this->repository->fetch($logins);
        $gitHubUsers = $list->getGitHubUsers();

        /** @var GitHubUser $gitHubUser */
        foreach ($gitHubUsers as $gitHubUser) {
            // here we assert the all the users were fetched
            // from GitHub API
            $this->assertFalse($gitHubUser->isFetchedFromCache());
        }

        // fetch the same set of users again
        $cached_list = $this->repository->fetch($logins);
        $cached_gitHubUsers = $cached_list->getGitHubUsers();

        /** @var GitHubUser $gitHubUser */
        foreach ($cached_gitHubUsers as $gitHubUser) {
            // assert that the fetched users were coming
            // from the system cache
            $this->assertTrue($gitHubUser->isFetchedFromCache());
        }
    }

    /**
     * Test result is sorted
     */
    public function test_results_is_sorted() {
        $logins = ['souinhua', 'fabpot', 'taylorotwell', 'andrew'];
        $list = $this->repository->fetch($logins);

        $gitHubUsers = $list->getGitHubUsers();
        $sorted_result_logins = $gitHubUsers->pluck('login')->toArray();

        // assert that it is sorted
        sort($logins);
        $this->assertEquals($logins, $sorted_result_logins);
    }
}
