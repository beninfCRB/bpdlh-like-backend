<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtTokenController extends ApiController
{
    public function generate(Request $request)
    {
        $sub = $request->input('sub', 'ocr');
        $exp = ($sub === 'ocr') ? (time() + 60) : (time() + 3600); // 1 menit untuk ocr, 1 jam lainnya
        $payload = [
            'iss' => config('app.url'),
            'iat' => time(),
            'exp' => $exp,
            'sub' => $sub,
            'user' => $request->input('user', null),
        ];
        $jwtSecret = env('JWT_SECRET');
        $token = JWT::encode($payload, $jwtSecret, 'HS256');
         return $this->sendSuccess(['token' => $token], 'Token generated successfully', 200);
    }
}
