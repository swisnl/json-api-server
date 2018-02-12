<?php
/**
 * Created by PhpStorm.
 * User: dtulp
 * Date: 7-2-2018
 * Time: 13:23.
 */

namespace Swis\JsonApi\Server\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class LaravelJsonExceptionHandler extends ExceptionHandler
{
    protected $errors = [];

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception                $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        $renderer = $this->getRenderer($exception);

        if (null !== $renderer) {
            $this->errors[] = $renderer->formatErrors($exception);
        }

        return response()->json(['errors' => $this->errors]);
    }

    /**
     * @param $exception
     *
     * @return mixed
     */
    protected function getRenderer($exception)
    {
        $knownExceptions = $this->getKnownRenderers();
        /** @var Renderer $knownRenderer */
        foreach ($knownExceptions as $knownException => $knownRenderer) {
            if (!($exception instanceof $knownException)) {
                continue;
            }

            return app()->make($knownRenderer);
        }
    }

    /**
     * @return array
     */
    protected function getKnownRenderers(): array
    {
        //Todo a better solution for managing these exceptions
        $knownExceptions = [
            JsonException::class => JsonExceptionRenderer::class,
            AuthorizationException::class => AuthorizationExceptionRenderer::class,
            ValidationException::class => JsonExceptionRenderer::class,
        ];

        return $knownExceptions;
    }
}
