<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mails\ConfirmationMail;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function verify($code)
    {
        $token = Token::query()->where('token', $code)->get()->first();

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token not found',
            ], 500);
        }

        $token_exp = $token->expiry_date;
        if ($token_exp > now()) {
            User::query()->find($token->user_id)->update([
                'is_verified' => TRUE,
            ]);

            $token->delete();

            return response()->json([
                'status' => true,
                'message' => 'Your email is verified',
            ], 201);


        } elseif ($token_exp < now()) {
            $token->delete();
            return response()->json([
                'status' => true,
                'message' => 'Token is expired, resend',
            ], 201);

        }
    }

    public function resend()
    {
        if (!auth()->user()->is_verified) {

            $token = Token::query()->create([
                'token' => rand(100000, 999999),
                'user_id' => auth()->id(),
                'expiry_date' => now()->add('1hour'),
            ]);

            $data = [
                'name' => auth()->user()->name,
                'code' => $token->token,
            ];

            Mail::to('isoyan.inna@gmail.com')->send(new ConfirmationMail($data));

            return response()->json([
                'status' => true,
                'message' => 'Verification token is sent to your email',
            ], 201);

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your email is already verified',
            ]);
        }
    }
}
