<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CloudflareMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Mendeteksi IP asli pengguna di belakang Cloudflare
     * dan memvalidasi Turnstile CAPTCHA jika diperlukan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Mendapatkan IP asli dari header Cloudflare
        if ($request->server->has('HTTP_CF_CONNECTING_IP')) {
            $request->server->set('REMOTE_ADDR', $request->server->get('HTTP_CF_CONNECTING_IP'));
        }

        // Validasi Turnstile untuk form submissions
        if ($request->isMethod('POST') && $request->has('cf-turnstile-response')) {
            $turnstileResponse = $request->input('cf-turnstile-response');
            $secretKey = env('CLOUDFLARE_TURNSTILE_SECRET_KEY');

            if (!$secretKey) {
                return $next($request);
            }

            $verify = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, [
                'secret' => $secretKey,
                'response' => $turnstileResponse,
                'remoteip' => $request->ip(),
            ]);
            $result = json_decode(curl_exec($verify), true);
            curl_close($verify);

            if (!$result['success']) {
                return back()->withErrors(['captcha' => 'Verifikasi Turnstile gagal. Silakan coba lagi.']);
            }
        }

        return $next($request);
    }
}

