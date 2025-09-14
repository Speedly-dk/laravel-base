<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isTwoFactorRequired()) {
            return $next($request);
        }

        if (! $user->hasTwoFactorEnabled()) {
            session()->flash('two_factor_required', true);

            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}
