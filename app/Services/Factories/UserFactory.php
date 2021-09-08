<?php

namespace App\Services\Factories;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    /**
     * Create a User Model. Note that this is inserted into
     * the database
     *
     * @param RegisterRequest $request
     * @return User
     */
    public function create(RegisterRequest $request): User
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return $user;
    }
}
