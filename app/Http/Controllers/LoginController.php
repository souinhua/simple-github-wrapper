<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * The login end-point
     *
     * @param LoginRequest $request
     * @return ApiResponse
     */
    public function login(LoginRequest $request): ApiResponse
    {
        // get credentials from current request
        $credentials = $request->getCredentials();

        // attempt to login
        if (Auth::attempt($credentials)) {

            /** @var User $user */
            $user = Auth::user();

            // create a new token for this newly logged in user
            $token = $user->getPlainTextToken();

            $data = [
                'user' => $user,
                'token' => $token
            ];

            return new ApiResponse('Successfully logged in. You may now use the API with Bearer token', $data, Response::HTTP_OK);
        }

        return new ApiResponse('Cannot login with the current credentials.', null, Response::HTTP_UNAUTHORIZED);
    }
}
