<?php

namespace Chomsky\Providers;

use Chomsky\Cryptic\Bcryptic;
use Chomsky\Cryptic\Hasher;

class HasherServiceProvider implements ServiceProvider
{
    public function registerServices()
    {
        match(config("hashing.hasher","bcryptic")) {
            "bcryptic" => singleton(Hasher::class,Bcryptic::class)
        };
    }

}
