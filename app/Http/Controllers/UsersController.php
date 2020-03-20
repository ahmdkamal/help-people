<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function register(ProfileRequest $registerRequest)
    {
        request()->merge(['password' => Hash::make(request()->password)]);
        $user = User::create(request()->all());
        $user['token'] = $user->createToken(env('APP_NAME'))->accessToken;

        return response()->json([
            'data' => $user,
            'message' => 'Successfully Created',
            "pagination" => null,
        ], 201);
    }

    public function login(LoginRequest $loginRequest)
    {
        $user = User::where('phone', request('phone'))->first();
        $validator = Validator::make([], []);
        if ($user == null) {
            $validator->errors()->add('phone/password', 'invalid data');
            throw new ValidationException($validator);
        }
        if (!Hash::check(request('password'), $user->password)) {
            $validator->errors()->add('phone/password', 'invalid data');
            throw new ValidationException($validator);
        }
        $user['token'] = $user->createToken(env('APP_NAME'))->accessToken;

        return response()->json([
            'data' => $user,
            'message' => 'Successfully Logging',
            "pagination" => null,
        ], 200);
    }

    public function show()
    {
        $token = request()->bearerToken();
        $user = auth()->user();
        $user->update(request()->all());
        $user['token'] = $token;

        return response()->json([
            'data' => $user,
            'message' => 'Successfully Created',
            "pagination" => null,
        ], 200);
    }

    public function update(ProfileRequest $registerRequest)
    {
        $token = request()->bearerToken();

        if (request()->password) {
            request()->merge(['password' => Hash::make(request()->password)]);
        }

        $user = auth()->user();
        $user->update(request()->all());
        $user['token'] = $token;

        return response()->json([
            'data' => $user,
            'message' => 'Successfully Created',
            "pagination" => null,
        ], 200);
    }
}
