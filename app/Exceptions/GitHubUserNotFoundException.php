<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class GitHubUserNotFoundException extends Exception
{
    /**
     * The GitHub login/username that does not exist
     *
     * @var string
     */
    private string $login;

    /**
     * Constructs GitHubUserNotFoundException
     *
     * @param string $login
     */
    public function __construct(string $login)
    {
        $this->login = $login;

        parent::__construct("GitHub user [$login] does not exist.", Response::HTTP_NOT_FOUND);
    }
}
