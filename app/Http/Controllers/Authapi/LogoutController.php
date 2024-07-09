<?php

namespace App\Http\Controllers\Authapi;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class LogoutController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {

        try {
            $request->user()->tokens()->delete();

            return $this->sendSuccess(null, 'Success logout');
        } catch (\Throwable $th) {

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
