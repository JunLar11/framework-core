<?php

namespace Chomsky\Providers;

use Chomsky\Database\Drivers\DatabaseDriver;
use Chomsky\Database\Drivers\PdoDriver;

class DatabaseDriverServiceProvider implements ServiceProvider
{
    public function registerServices()
    {
        match (config("database.connection", "mysql")) {
            "mysql","pgsql" => singleton(DatabaseDriver::class, PdoDriver::class),
        };
    }
}