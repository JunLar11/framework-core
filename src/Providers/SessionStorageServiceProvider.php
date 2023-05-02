<?php

namespace Chomsky\Providers;

use Chomsky\Session\PhpNativeSessionStorage;
use Chomsky\Session\SessionStorage;

class SessionStorageServiceProvider implements ServiceProvider
{
    public function registerServices()
    {
        match (config("session.storage", "native")) {
            "native" => singleton(SessionStorage::class, PhpNativeSessionStorage::class),
        };
    }
}