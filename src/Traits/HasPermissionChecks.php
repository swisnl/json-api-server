<?php

namespace Swis\LaravelApi\Traits;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Route;

trait HasPermissionChecks //TODO: route weghalen en method mee geven verplicht
{
    use AuthorizesRequests;

    public function checkIfUserHasPermissions(
        Route $route,
        string $model,
        $requestedObject = null,
        $policyActionName = null
    ) {
        $actionName = $this->extractActionName($route, $policyActionName);

        if (null !== $requestedObject) {
            if($requestedObject instanceof Paginator) {
                foreach ($requestedObject->items() as $item) {
                    $this->authorize($actionName, $item);
                }
                return;
            }

            $this->authorize($actionName, $requestedObject);

            return;
        }

        $this->authorize($actionName, $model);
    }

    protected function extractActionName(Route $route, $policyActionName)
    {
        if (null !== $policyActionName) {
            return $policyActionName;
        }

        $actionName = substr($route->getActionName(), strpos($route->getActionName(), '@') + 1);

        return $actionName;
    }
}
