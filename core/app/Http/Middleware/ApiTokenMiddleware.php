<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $b64 = $request->header('Authorization') ?? $request->header('X-Auth');
        if (!$b64) {
            return response()->json(['message' => 'Unauthorized: missing header'], 401);
        }

        $cipher = base64_decode($b64, true);
        if ($cipher === false) {
            return response()->json(['message' => 'Unauthorized: bad base64'], 401);
        }

        $key = 'my-secret-key-16';
        $plain = openssl_decrypt($cipher, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

        if ($plain === false) {
            Log::warning('AES decrypt failed (ECB).');
            return response()->json(['message' => 'Unauthorized: decrypt failed'], 401);
        }

        if ($plain !== 'Abc123') {
            return response()->json(['message' => 'Unauthorized: token mismatch'], 401);
        }

        return $next($request);
    }
}
