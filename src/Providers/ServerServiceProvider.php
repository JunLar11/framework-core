<?php

namespace Chomsky\Providers;

use Chomsky\Server\PhpNativeServer;
use Chomsky\Server\Server;

class ServerServiceProvider implements ServiceProvider {
    public function registerServices() {
        singleton(Server::class, PhpNativeServer::class);
    }
}