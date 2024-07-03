<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureHeaderIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->hasHeader('id') &&
            $request->hasHeader('secret') &&
            $request->header('id') !== null &&
            $request->header('secret') !== null
        ) {
            # code...
            $client_id          = (string)$request->header('id');
            $client_secret      = (string)$request->header('secret');

            $credential = DB::connection('mysql_api')->table('api_credentials')
                ->where([
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'status' => true
                ])
                ->first();

            if (!$credential) {
                # code...
                return response()->json([
                    'code'      => 403,
                    'status'    => false,
                    'message'   => 'Headers not correct',
                ], 403);
            }

            return $next($request);
        } else {
            return response()->json([
                'code'      => 403,
                'status'    => false,
                'message'   => 'Headers not correct',
                'data'      => null,
            ], 403);
        }
    }
}
