<?php

namespace Swis\LaravelApi\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Route;

trait HasPermissionChecks
{
    use AuthorizesRequests;

    public function checkIfUserHasPermissions(Route $route, string $model, $requestedObject = null, $policyActionName = null)
    {
        $actionName = $this->extractActionName($route, $policyActionName);

        if ($requestedObject == !null) {
            $this->authorize($actionName, $requestedObject);
            return;
        }

        $this->authorize($actionName, $model);
    }

    protected function extractActionName(Route $route, $policyActionName)
    {
        if ($policyActionName ==! null) {
            return $policyActionName;
        }

        $actionName = substr($route->getActionName(), strpos($route->getActionName(), '@') + 1);
        return $actionName;
    }
}
