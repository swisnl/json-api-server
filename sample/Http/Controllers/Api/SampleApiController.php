<?php

namespace Sample\Http\Controllers\Api;

use Illuminate\Http\Request;
use Swis\LaravelApi\Http\Controllers\Api\BaseApiController;

class SampleApiController extends BaseApiController
{
    public function validateResource(Request $request, $id = null)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        return $request->all();
    }

    public function checkForPermissions(): bool
    {
        return false;
    }
}
