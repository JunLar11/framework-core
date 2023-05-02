<?php

namespace Chomsky\Providers;

use Chomsky\App;
use Chomsky\Storage\Drivers\DiskFileStorage;
use Chomsky\Storage\Drivers\FileStorageDriver;

class FileStorageDriverServiceProvider
{
    public function registerServices() {
        match (config("storage.driver", "disk")) {
            "disk" => singleton(
                FileStorageDriver::class,
                fn () => new DiskFileStorage(
                    App::$root . "/storage",
                    "storage",
                    config("app.url")
                )
            ),
        };
    }
}
