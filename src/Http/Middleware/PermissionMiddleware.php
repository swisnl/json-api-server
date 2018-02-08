<?php

namespace Swis\LaravelApi\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Swis\LaravelApi\Exceptions\ForbiddenException;

class PermissionMiddleware
{
    /**
     * This handle method handles the permissions of the incoming user. It checks if the user has permissions to
     * call the given route, if not an error will be returned.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->checkPermissions($request);

        return $next($request);
    }

    private function checkPermissions(Request $request)
    {
        $routeName = $request->route()->getName();

        try {
            if (!isset($routeName)) {
                throw new Exception('You have to assign a name to the route');
            }

            if (!$request->user()->hasPermissionTo($routeName)) {
                throw new ForbiddenException('You do not have the required permissions to access this route: '
                    .$routeName);
            }
        } catch (PermissionDoesNotExist $doesNotExist) {
            throw new ForbiddenException('There is no existing permission for this route: '.$routeName);
        }
    }
}
