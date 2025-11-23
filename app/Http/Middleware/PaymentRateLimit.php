<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PaymentRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Different limits for different endpoints
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->getMaxAttempts($request);
        $decayMinutes = $this->getDecayMinutes($request);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again in ' . $seconds . ' seconds.',
                'retry_after' => $seconds,
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', max(0, $maxAttempts - RateLimiter::attempts($key)));

        return $response;
    }

    /**
     * Resolve the rate limiting signature for the request
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $userId = $request->user()?->id ?? 'anonymous';
        $ip = $request->ip();
        $route = $request->route()?->getName() ?? $request->path();
        
        return "payment-rate-limit:{$route}:{$userId}:{$ip}";
    }

    /**
     * Get the maximum number of attempts for the request
     */
    protected function getMaxAttempts(Request $request): int
    {
        $route = $request->route()?->getName();
        
        return match($route) {
            'api.payment.create' => 5,      // 5 payment creations per window
            'api.payment.cancel' => 10,     // 10 cancellations per window
            'api.payment.status' => 60,     // 60 status checks per window
            'payment.webhook' => 1000,      // High limit for webhooks
            default => 20,                  // Default limit
        };
    }

    /**
     * Get the decay time in minutes
     */
    protected function getDecayMinutes(Request $request): int
    {
        $route = $request->route()?->getName();
        
        return match($route) {
            'api.payment.create' => 60,     // 1 hour window for creation
            'api.payment.cancel' => 15,     // 15 minutes for cancellation
            'api.payment.status' => 5,      // 5 minutes for status checks
            'payment.webhook' => 1,         // 1 minute for webhooks
            default => 15,                  // 15 minutes default
        };
    }
}
