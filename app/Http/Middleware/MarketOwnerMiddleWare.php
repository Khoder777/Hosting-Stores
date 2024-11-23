<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarketOwnerMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->tokenCan('role:marketOwner')) {

            $currentToken = auth()->user()->currentAccessToken(); // استخدم currentAccessToken

            if ($currentToken) {
                $expirationDate = Carbon::parse($currentToken->expires_at);

                // تحقق مما إذا كان التوكن منتهي
                if ($expirationDate->isPast()) {
                    return response()->json('Your session is expired. Log in again.', 401);
                }

                return $next($request);
            }
        }
        return response()->json('Not Authorized Market Owner Only Can Check that', 401);
    }
}