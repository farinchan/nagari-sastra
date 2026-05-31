<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackLastSeen
{
    /**
     * Update last_seen_at for authenticated users.
     * Throttled to once per minute to avoid excessive DB writes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Only update once per minute to reduce DB writes
            if (!$user->last_seen_at || $user->last_seen_at->diffInSeconds(now()) >= 60) {
                $user->forceFill(['last_seen_at' => now()])->saveQuietly();
            }
        }

        return $next($request);
    }
}
