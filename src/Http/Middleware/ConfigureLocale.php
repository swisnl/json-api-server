<?php

namespace Swis\LaravelApi\Http\Middleware;

use Closure;

class ConfigureLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app()->setLocale($request->get('lang', $request->user()->locale));

        return $next($request);
    }
}
