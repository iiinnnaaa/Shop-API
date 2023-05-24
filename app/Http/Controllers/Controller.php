<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param bool $status
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public function responseBody(bool $status = true, $message = null, int $code = 200, $body = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'body' => $body,
        ], $code);
    }
}
