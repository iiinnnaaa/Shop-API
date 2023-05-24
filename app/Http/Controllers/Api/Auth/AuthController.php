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
use PHPUnit\Exception;

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

//            $user->roles()->attach([1,3]);

//            Mail::to('isoyan.inna@gmail.com')->send(new ConfirmationMail($data));

            auth()->guard()->login($user);

            return $this->responseBody(message: 'User Created Successfully', body: ['user_id'=>$user->id]);

        } catch (\Exception $exception) {
            return $this->responseBody(false, $exception->getMessage(), 422);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $user = User::query()->where('email', $request->input('email'))->first();

            if (auth()->attempt($credentials)) {
                if(!$user->is_verified){
                    return $this->responseBody(false,"Your account is not verified", 422);
                }
                $token = auth()->user()->createToken("API TOKEN")->plainTextToken;
                return $this->responseBody(message: "Logged in successfully", body: ['token' => $token]);
            }
            return $this->responseBody(false, "Wrong email or password", 422);
        } catch (Exception$exception) {
            return $this->responseBody(false, $exception->getMessage(), 422);
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->responseBody(message: 'Logged out successfully');
    }
}
