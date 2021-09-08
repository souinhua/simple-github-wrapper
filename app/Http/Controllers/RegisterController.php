<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Factories\UserFactory;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /**
     * The UserFactory for User model creation
     *
     * @var UserFactory
     */
    private UserFactory $factory;

    /**
     * Creates the RegisterController instance
     *
     * @param UserFactory $factory
     */
    public function __construct(UserFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * The user registration end-point.
     *
     * @param RegisterRequest $request
     * @return ApiResponse
     */
    public function register(RegisterRequest $request): ApiResponse
    {
        // create the User model
        $user = $this->factory->create($request);

        // create a new token for this newly registered token
        $token = $user->getPlainTextToken();

        // initialize the response data
        $data = [
            'user' => $user,
            'token' => $token
        ];

        return new ApiResponse('User has been successfully registered.', $data, Response::HTTP_CREATED);
    }
}
