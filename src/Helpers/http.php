<?php

/**
 * Global helper functions
 */

use Chomsky\Http\Request;
use Chomsky\Http\Response;

function json(array $data): Response
{
    ($data);
    return Response::json($data);
}

function redirect(string $uri): Response
{
    return Response::redirect($uri);
}

function back(): Response
{
    return redirect(session()->get('_previous', '/'));
}

function view(string $view, array $parameters = [], string $layout = null): Response
{
    return Response::view($view, $parameters, $layout);
}

function Request(): Request
{
    return app()->request;
}

/*function fromJson(mixed $json): array
{
    $original = $json;
    if (is_string($json)) {
        json_decode($json);
        if (!(json_last_error() == JSON_ERROR_NONE)) {
            return json_decode($json);
        }
    }
    return $original;
}*/
