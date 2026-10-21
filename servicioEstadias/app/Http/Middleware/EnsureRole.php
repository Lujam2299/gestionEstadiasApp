<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $email = $request->user()?->email;
        $allowed = match ($role) {
            'admin' => $email === 'desarrolloacademico@tecvalles.mx',
            'vinculacion' => $email === 'vinculacion@tecvalles.mx',
            default => false,
        };
        abort_unless($allowed, 403);

        return $next($request);
    }
}
