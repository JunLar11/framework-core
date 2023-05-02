<?php

namespace Chomsky\Providers;

use Chomsky\Auth\Authenticators\Authenticator;
use Chomsky\Auth\Authenticators\SessionAuthenticator;

class AuthenticationServiceProvider implements ServiceProvider
{
    public function registerServices(){
        match(config("auth.method","session")){
            "session"=>singleton(Authenticator::class,SessionAuthenticator::class)
        };
    }
}
