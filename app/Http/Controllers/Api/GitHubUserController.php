<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FetchGithubUsers;
use App\Http\Responses\ApiResponse;
use App\Services\GitHubIntegration\Repositories\GitHubUsersRepository;
use App\Services\GitHubIntegration\Sources\GitHubUsersApi;
use App\Services\GitHubIntegration\Sources\GitHubUsersCache;
use Illuminate\Http\Response;

class GitHubUserController extends Controller
{
    /**
     * The GitHub users repository
     *
     * @var GitHubUsersRepository
     */
    private GitHubUsersRepository $repository;

    public function __construct(GitHubUsersRepository $repository)
    {
        // initialize the injected GitHubUsersRepository
        $this->repository = $repository;

        // First, we need to provide the cache source for the repository
        $this->repository->addSource(new GitHubUsersCache());

        // Lastly, we need to provide the GitHub API source for the repository
        $this->repository->addSource(new GitHubUsersApi());
    }

    public function index(FetchGithubUsers $request)
    {
        $logins = $request->get('logins');

        $list = $this->repository->fetch($logins);

        $data = [
            'github_users' => $list->getGitHubUsers(),
            'invalid_logins' => array_values($list->notFoundLogins())
        ];

        return new ApiResponse('GitHub users successfully fetched', $data, Response::HTTP_OK);
    }
}
