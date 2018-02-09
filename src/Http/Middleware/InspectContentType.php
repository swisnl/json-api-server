<?php

namespace Swis\LaravelApi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Swis\LaravelApi\Exceptions\ContentTypeNotSupportedException;

class InspectContentType
{
    public function handle(Request $request, Closure $next)
    {
        if ('application/vnd.api+json' !== $request->header('Content-Type')) {
            throw new ContentTypeNotSupportedException('Your request should be in json api format. (Content-Type: application/vnd.api+json)');
        }

        return $next($request);
    }
}
