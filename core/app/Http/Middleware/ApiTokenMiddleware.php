<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Helper;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $b64 = $request->header('Authorization') ?? $request->header('X-Auth');
        if (!$b64) {
            return response()->json([
                'success' => false,
                'error' => 'Authorization Error',
                'message' => 'Missing authorization header.'
            ], 401);
        }

        $cipher = base64_decode($b64, true);
        if ($cipher === false) {
            return response()->json([
                'success' => false,
                'error' => 'Authorization Error',
                'message' => 'Invalid base64 encoding in token.'
            ], 401);
        }

        $key = 'my-secret-key-16';
        $plain = openssl_decrypt($cipher, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

        if ($plain === false) {
            Log::warning('AES decrypt failed (ECB).');
            return response()->json([
                'success' => false,
                'error' => 'Authorization Error',
                'message' => 'Token decryption failed.'
            ], 401);
        }

        if ($plain !== Helper::GeneralSiteSettings('auth_code_en')) {
            return response()->json([
                'success' => false,
                'error' => 'Authorization Error',
                'message' => 'Invalid or mismatched token.'
            ], 401);
        }   

        return $next($request);
    }
}
