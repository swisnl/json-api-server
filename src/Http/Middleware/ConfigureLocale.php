<?php

namespace Swis\JsonApi\Server\Http\Middleware;

use Closure;

class ConfigureLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $defaultLocale = env('APP_DEFAULT_LOCALE', 'en');
        $user = $request->user();
        if ($user && property_exists($user, 'locale')) {
            $defaultLocale = $user->locale;
        }
        app()->setLocale($request->get('lang', $defaultLocale));

        return $next($request);
    }
}
