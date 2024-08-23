<?php

namespace App\Http\Middleware;

use Closure;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = config('app.api_key');
        // List of valid API keys
        $validApiKeys = [
            $apiKey
        ];

        // Get the API key from the request (e.g., from a header)
        $apiKey = $request->header('X-API-KEY');

        // Check if the provided API key is valid
        if (!in_array($apiKey, $validApiKeys)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
