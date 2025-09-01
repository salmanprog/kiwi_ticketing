<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;

class BaseAPIController extends Controller
{
    protected function sendResponse(int $code = 200, string $message = 'success', $data = [])
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $code);
    }
}
