<?php
/**
 * Global Delivered Logistics - Rate Limiting Middleware
 * 
 * Limits API request frequency per IP/user to prevent abuse.
 */

namespace App\Middleware;

class RateLimitMiddleware
{
    private int $maxRequests = 60;
    private int $windowMinutes = 1;

    public function handle(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'rate_limit_' . md5($ip . ($_SESSION['user_id'] ?? ''));
        
        $requests = $_SESSION[$key] ?? ['count' => 0, 'reset' => time() + ($this->windowMinutes * 60)];
        
        // Reset if window expired
        if (time() > $requests['reset']) {
            $requests = ['count' => 0, 'reset' => time() + ($this->windowMinutes * 60)];
        }
        
        $requests['count']++;
        $_SESSION[$key] = $requests;
        
        // Set rate limit headers
        header('X-RateLimit-Limit: ' . $this->maxRequests);
        header('X-RateLimit-Remaining: ' . max(0, $this->maxRequests - $requests['count']));
        header('X-RateLimit-Reset: ' . $requests['reset']);
        
        if ($requests['count'] > $this->maxRequests) {
            $retryAfter = $requests['reset'] - time();
            header('Retry-After: ' . $retryAfter);
            
            json_response([
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter,
            ], 429);
        }
    }
}
