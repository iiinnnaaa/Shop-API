<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mails\ConfirmationMail;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::query()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Create a token with 1 hour expiration time
            $token = Token::query()->create([
                'token' => rand(100000, 999999),
                'user_id' => $user->id,
                'expiry_date' => now()->add('1hour'),
            ]);

            $data = [
                'name' => $request->name,
                'code' => $token->token,
            ];

            $user->roles()->attach(1);

            dd($user);
//            $user->roles()->attach([1,3]);

            Mail::to('isoyan.inna@gmail.com')->send(new ConfirmationMail($data));

            auth()->guard()->login($user);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully, please check and confirm your email',
            ], 201);


        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return response()->json([
               'status' => true,
                'message' => 'Logged in successfully',
                'token' => auth()->user()->createToken("API TOKEN")->plainTextToken], 200
            );
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User Logged Out Successfully',
        ], 201);
    }
}
