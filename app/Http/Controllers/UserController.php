<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserControllerRegisterRequest;
use App\Models\OauthClient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public function register(UserControllerRegisterRequest $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        /*
         *  This section is making test easier. This approach should not be used
         *  in production.
         */
        $clientSecret = OauthClient::find(1);

        if (!$clientSecret) {
            return response()->json(
                [
                    'message' => 'Client secret key has not been retrieved!',
                    'error_code' => 404,
                ],
                404
            );
        }
        /**
         * -------------------------------------------------------------------
         */

        $user = User::query()->create(
            [
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]
        );

        if ($user) {
            request()->request->add(
                [
                    'grant_type' => 'password',
                    'username' => $email,
                    'password' => $password,
                    'client_id' => 1,
                    'client_secret' => $clientSecret->secret,
                    'scope' => '*',
                ]
            );

            // Fire off the internal request.
            $token = Request::create(
                'oauth/token',
                'POST'
            );

            return Route::dispatch($token);
        }

        return response()->json(
            [
                'message' => 'Something went wrong!',
                'error_code' => 403,
            ],
            403
        );
    }
}
