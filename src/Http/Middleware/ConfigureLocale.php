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
        //TODO: check of user al een locale heeft wanneer het niet in de url staat. Kan wanneer front
        // gebruiker gebruikt word in plaats van user.
        app()->setLocale($request->get('lang', 'nl'));

        return $next($request);
    }
}
