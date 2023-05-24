<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mails\ConfirmationMail;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $token = Token::query()->where('token', $request->input('code'))->get()->first();

        if (!$token) {
            return $this->responseBody(false, 'Token not found', 500);
        }

        $token_exp = $token->expiry_date;
        if ($token_exp > now()) {
            User::query()->find($token->user_id)->update([
                'is_verified' => TRUE,
            ]);

            $token->delete();

            return $this->responseBody(message: 'Your email is verified');


        } elseif ($token_exp < now()) {
            $token->delete();
            return $this->responseBody(false, 'Token is expired, please try to resend', 422);
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

            return $this->responseBody(message: 'Verification token is sent to your email');

        } else {
            return $this->responseBody(false, 'Your email is already verified', 422);
        }
    }
}
