<?php

namespace Swis\LaravelApi\Traits;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasPermissionChecks
{
    use AuthorizesRequests;

    public function checkIfUserHasPermissions(
        $policyMethod,
        string $model,
        $requestedObject = null
    ) {
        if (null !== $requestedObject) {
            if ($requestedObject instanceof Paginator) {
                foreach ($requestedObject->items() as $item) {
                    $this->authorize($policyMethod, $item);
                }

                return;
            }

            $this->authorize($policyMethod, $requestedObject);

            return;
        }

        $this->authorize($policyMethod, $model);
    }
}
