<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyReCaptcha
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
        if ($request->isMethod('post')) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $secretKey = env('RECAPTCHA_SECRET_KEY');
            $response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
            ]);
            $responseBody = json_decode($response->getBody());

            if (!$responseBody->success || $responseBody->score < 0.5) {
                return redirect()->back()->with(['error' => 'Invalid reCAPTCHA']);
            }
        }

        return $next($request);
    }
}
