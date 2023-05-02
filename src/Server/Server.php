<?php

namespace Chomsky\Server;

use Chomsky\Http\Request;
use Chomsky\Http\Response;

interface Server
{
    /**
     * Get the request sent by the client.
     *
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * Send a response to the client
     *
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response);
}
