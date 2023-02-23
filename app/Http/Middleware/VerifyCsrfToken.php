<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/callback_url',
        '/return_url'
    ];

    public function handle($request, Closure $next)
{
    if ($request->getHost() == 'https://secure.paytabs.sa') {
        // skip CSRF check
        return $next($request);
    }

    return parent::handle($request, $next);
}
}
